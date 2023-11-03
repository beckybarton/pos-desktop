<?php

namespace App\Http\Controllers;

use App\Models\CashOnHand;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Item;
use App\Models\Location;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Payment;
use App\Models\CustomerCredit;
use App\Models\CustomerCreditDeduction;
use Carbon\Carbon;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use PDF;

class ReportController extends Controller
{
    public function getAll(){
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();
        return [$locations, $categories, $setting];
    }
    public function index(){
        $items = Item::orderBy('name', 'desc')
        ->paginate(10);
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();

        return view('reports.index', compact('items','locations', 'categories', 'setting'));
    }

    public function jsondailyreport($startdate, $enddate){
        $dailyreport = $this->dailyreport($startdate, $enddate);
        return response()->json($dailyreport);
    }

    public function dailyreport($startdate, $enddate) {
        $sumOrdersAmount = Order::whereBetween('created_at', [$startdate, $enddate])->sum('amount');

        $datecollections = Payment::join('orders', 'payments.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startdate, $enddate])
            ->whereNot('payments.method', 'credit')
            ->select('payments.method', DB::raw('SUM(payments.amount) as totalpayment'))
            ->groupBy('payments.method') 
            ->get();

        $allcollections = DB::table(DB::raw('(SELECT method, amount FROM payments WHERE method <> "credit"
                UNION ALL 
                SELECT method, amount FROM excesses) AS combined_data'))
            ->select('method', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('method')
            ->get();

        // $allcollections = DB::table(DB::raw('(SELECT method, amount, created_at FROM payments WHERE method <> "credit")
        //     UNION ALL 
        //     (SELECT method, amount, created_at FROM excesses)
        //     AS combined_data'))
        //     ->select('method', DB::raw('SUM(amount) as total_amount'))
        //     ->whereRaw('combined_data.created_at BETWEEN :startdate AND :enddate', ['start' => $startdate, 'end' => $enddate])
        //     ->groupBy('method')
        //     ->get();




        $categorizedSales = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$startdate, $enddate])
            ->select('categories.name as category_name',
                DB::raw('SUM(order_items.price * order_items.quantity) as amount'))
            ->orderBy('order_items.created_at', 'desc')
            ->groupBy('categories.name')
            ->orderBy('category_name', 'asc') 
            ->get();

        $collectionsDateSalesbyMethod = DB::table('payments')
            ->join('orders', 'payments.order_id', "=", 'orders.id')
            ->whereBetween('orders.created_at', [$startdate, $enddate])
            ->select('payments.method as method',
                        DB::raw('SUM(payments.amount) as totalpayment')
                    )
            ->orderBy('payments.method')
            ->groupBy('payments.method')
            ->get();
        
        $collectionsDateSalesCash = $collectionsDateSalesbyMethod->where('method', 'Cash')->sum('totalpayment');
        $collectionsDateSalesGcash = $collectionsDateSalesbyMethod->where('method', 'Gcash')->sum('totalpayment');


        $collectionsPreviousSalesbyMethod = DB::table('payments')
        ->join('orders', 'payments.order_id', "=", 'orders.id')
        ->where('orders.created_at', '<', $startdate)
        ->whereBetween('payments.created_at', [$startdate, $enddate])
        ->select('payments.method as method',
                    DB::raw('SUM(payments.amount) as totalpayment')
                )
        ->orderBy('payments.method')
        ->groupBy('payments.method')
        ->get();

        $collectionsPreviousSalesCash = $collectionsPreviousSalesbyMethod->where('method', 'Cash')->sum('totalpayment');
        $collectionsPreviousSalesGcash = $collectionsPreviousSalesbyMethod->where('method', 'Gcash')->sum('totalpayment');

        // get all payments where payments.order_id with orders.created_at is less than the start date, and where payment.created_at is between startdate and end date. group this by payments.customer_id and get the customer name
        $collectionsPreviousSalesbyCustomer = DB::table('payments')
            ->join('orders', 'payments.order_id', "=", 'orders.id')
            ->join('customers', 'payments.customer_id', "=", 'customers.id')
            ->where('orders.created_at', '<', $startdate)
            ->whereBetween('payments.created_at', [$startdate, $enddate])
            ->select('orders.customer_id as customer_id',
                        'customers.name as name',
                        'payments.method as method',
                        // 'orders.id as order_id',
                        // DB::raw('GROUP_CONCAT(orders.id) as order_ids'), 
                        // DB::raw('GROUP_CONCAT(payments.id) as payment_ids'), 
                        DB::raw('SUM(payments.amount) as totalpayment')
                    )
            ->orderBy('orders.customer_id')
            // ->groupBy('orders.customer_id', 'customers.name', 'orders.id', 'payments.id')
            ->groupBy('orders.customer_id', 'customers.name', 'payments.method')
            ->get();


        $listunpaidcustomers = DB::table('customers')
            ->join('orders', 'customers.id', "=", 'orders.customer_id')
            ->whereBetween('orders.created_at',[$startdate, $enddate])
            ->where('orders.status', '<>', 'paid')
            ->select('customers.name as name',
                    DB::raw('SUM(orders.remaining_due) as amount')
                )
            ->groupBy('customers.name')
            ->get();

        $totalunpaid = $listunpaidcustomers->sum('amount');

        $solditems = $this->solditems($startdate, $enddate);

        $cashcollections_noexcess = $collectionsDateSalesCash + $collectionsPreviousSalesCash;
        $Gcashcollections_noexcess = $collectionsDateSalesGcash + $collectionsPreviousSalesGcash;

            
        return ['sumOrdersAmount' => $sumOrdersAmount,
            'categorizedSales' => $categorizedSales,
            'collectionsDateSalesbyMethod' => $collectionsDateSalesbyMethod,
            'collectionsPreviousSalesbyMethod' => $collectionsPreviousSalesbyMethod,
            'listunpaidcustomers' => $listunpaidcustomers,
            'totalunpaid' => $totalunpaid,
            'allcollections' => $allcollections,
            // 'totalcollections' => $totalcollections,
            'collectionsPreviousSalesbyCustomer' => $collectionsPreviousSalesbyCustomer,
            'datecollections' => $datecollections,
            'solditems' => $solditems,
            'cashcollections_noexcess' => $cashcollections_noexcess,
            'Gcashcollections_noexcess' => $Gcashcollections_noexcess
            ];
    }   

    public function solditems($start, $end){
        $solditems = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('locations', 'orders.location_id', '=', 'locations.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$start, $end])
            ->select('items.name as item_name',
                'locations.name as location',
                'categories.name as category_name',
                'order_items.price as price',
                'order_items.cost as cost',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.quantity * items.selling_price) as total_price'))
            ->groupBy('items.name', 'categories.name', 'order_items.price', 'order_items.cost', 'locations.name')
            ->orderBy('category_name', 'asc')
            ->orderBy('item_name', 'asc')
            ->get();



        
        return ['solditems' => $solditems];    
    }  
        
    public function excesspayments($start, $end){
        $excesspaymentsbycustomers = DB::table('excesses')
            ->join('payments', 'excesses.payment_id', '=', 'payments.id')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('excesses.created_at', [$start, $end])
            ->select('orders.id as order_id',
                'customers.name as customer_name',
                'excesses.amount as amount',
                'excesses.method as method',
                'excesses.created_at as created_at')
            ->orderBy('orders.id', 'asc')
            ->get();

        
        $excesspaymentsbymethods = DB::table('excesses')
            ->join('payments', 'excesses.payment_id', '=', 'payments.id')
            ->join('orders', 'payments.order_id', '=', 'orders.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereBetween('excesses.created_at', [$start, $end])
            ->select(
                'excesses.method as method',
                DB::raw('SUM(excesses.amount) as total_amount'))
            ->groupBy('excesses.method') 
            ->get();

        $excesspaymentscash = $excesspaymentsbymethods->where('method', 'cash')->sum('total_amount');
        $excesspaymentsgcash = $excesspaymentsbymethods->where('method', 'Gcash')->sum('total_amount');


        return ['excesspaymentsbycustomers' => $excesspaymentsbycustomers, 
                'excesspaymentsbymethods' => $excesspaymentsbymethods,
                'excesspaymentscash' => $excesspaymentscash,
                'excesspaymentsgcash' => $excesspaymentsgcash
            ];
    }

    public function orderitemspercustomer($start, $end){
        $orderitemspercustomer = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('locations', 'orders.location_id', '=', 'locations.id')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('payments', 'orders.id', '=', 'payments.order_id')
            ->whereBetween('order_items.created_at', [$start, $end])
            ->select(
                'orders.id as order_id',
                'locations.name as location',
                'customers.name as customer_name',
                'items.name as item_name',
                'order_items.price as price',
                'order_items.cost as cost',
                'orders.payment as payment',
                'orders.created_at as created_at',
                'orders.remaining_due as remaining_due',
                'payments.method as method',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_price')
            )
            ->groupBy('orders.id', 'payments.method', 'order_items.cost', 'locations.name', 'customers.name', 'items.name', 'order_items.price', 'orders.payment', 'orders.remaining_due', 'orders.created_at', 'locations.name')
            // ->groupBy('order_items.price', 'order_items.cost', 'orders.id', 'customers.name', 'items.name', 'locations.name', 'orders.payment')
            ->orderBy('orders.id', 'asc')
            ->get();

            // nabikil sa payments

        // $orderitemspercustomer = OrderItem::all();



        
        return ['orderitemspercustomer' => $orderitemspercustomer];
    }

    public function getCashonHand($report){
        $cashonhand = CashOnHand::where('report_id', $report)->get();
        $totalcashonhand = $cashonhand->sum('amount');
        return ['cashonhand' => $cashonhand,
            'totalcashonhand' => $totalcashonhand
        ];
    }

    public function dailysave (Request $request){
        $report = new Report();
        $report->start = $request->startdate;
        $report->end = $request->enddate;
        $report->type = $request->reporttype;
        $report->user_id = auth()->user()->id;
        $report->save();

        $denominations = $request->input('denominations');
        $pieces = $request->input('pieces');

        foreach ($denominations as $index => $denomination) {
            if($pieces[$index] != '0'){
                $cashonhand = new CashOnHand();
                $cashonhand->report_id = $report->id;
                $cashonhand->denomination = $denomination;
                $cashonhand->pcs = $pieces[$index];
                $cashonhand->amount = $pieces[$index] * $denomination;
                $cashonhand->save();
            }
            
        }

        return $this->downloadreport($report->id);

    }

    public function downloadreport($report){
        $report = Report::find($report);
        if ($report->type == "Daily Report"){
            $solditems = json_decode(json_encode($this->solditems($report->start, $report->end)));
            $dailyreport = json_decode(json_encode($this->dailyreport($report->start, $report->end)));
            $orderitemspercustomer = json_decode(json_encode($this->orderitemspercustomer($report->start, $report->end)));
            $cashonhand = json_decode(json_encode($this->getCashonHand($report->id)));
            $excesspayments = json_decode(json_encode($this->excesspayments($report->start, $report->end)));
            $pdf = PDF::loadView('pdfs.dailyreport', compact('dailyreport', 'report', 'solditems', 'orderitemspercustomer', 'cashonhand', 'excesspayments'));
            $pdf->setPaper('letter', 'landscape');
            return $pdf->download('dailyreport.pdf');
            // dd([$orderitemspercustomer]);
            // dd($orderitemspercustomer);
        }

    }

    public function getPrevious(){
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);
        return view('reports.previous', compact('reports', 'locations', 'categories', 'setting'));
    }
}
