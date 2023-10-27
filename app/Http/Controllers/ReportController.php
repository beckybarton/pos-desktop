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

        $allcollections = Payment::whereBetween('created_at', [$startdate, $enddate])
            ->select('method', DB::raw('SUM(amount) as totalpayment'))
            ->groupBy('method') 
            ->get();

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
        $totalcollections = $allcollections->sum('totalpayment');

        $solditems = $this->solditems($startdate, $enddate);

            
        return ['sumOrdersAmount' => $sumOrdersAmount,
            'categorizedSales' => $categorizedSales,
            'collectionsDateSalesbyMethod' => $collectionsDateSalesbyMethod,
            'collectionsPreviousSalesbyMethod' => $collectionsPreviousSalesbyMethod,
            'listunpaidcustomers' => $listunpaidcustomers,
            'totalunpaid' => $totalunpaid,
            'allcollections' => $allcollections,
            'totalcollections' => $totalcollections
            ];
    }   

    public function solditems($start, $end){
        $solditems = DB::table('order_items')
            ->join('items', 'order_items.item_id', '=', 'items.id')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$start, $end])
            ->select('items.name as item_name',
                'categories.name as category_name',
                'items.selling_price as price',
                DB::raw('SUM(order_items.quantity) as quantity'),
                DB::raw('SUM(order_items.quantity * items.selling_price) as total_price'))
            ->orderBy('order_items.created_at', 'desc')
            ->groupBy('items.name', 'categories.name', 'items.selling_price')
            ->orderBy('item_name', 'asc') 
            ->get();

        
        
        return ['solditems' => $solditems];        
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

        // foreach ($denominations as $index => $denomination) {
        //     if($pieces[$index] != '0'){
        //         $cashonhand = new CashOnHand();
        //         $cashonhand->report_id = $report->id;
        //         $cashonhand->denomination = $denomination;
        //         $cashonhand->pcs = $pieces[$index];
        //         $cashonhand->amount = $pieces[$index] * $denomination;
        //         $cashonhand->save();
        //     }
            
        // }

        return $this->download($report->id);

    }

    public function download($report){
        $report = Report::find($report);
        if ($report->type == "Daily Report"){
            $solditems = json_decode(json_encode($this->solditems($report->start, $report->end)));
            $dailyreport = json_decode(json_encode($this->dailyreport($report->start, $report->end)));
            return view('pdfs.dailyreport', compact('dailyreport', 'report', 'solditems'));
            dd($solditems);
        }

    }
    
            // $pdf = PDF::loadView('pdfs.dailyreport', compact('dailyreport', 'report', 'solditems'));
            // return $pdf->download('dailyreport.pdf');

    public function getPrevious(){
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);
        return view('reports.previous', compact('reports', 'locations', 'categories', 'setting'));
    }
}
