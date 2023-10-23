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

class ReportController extends Controller
{
    public function index(){
        $items = Item::orderBy('name', 'desc')
        ->paginate(10);
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();
        return view('reports.index', compact('items','locations', 'categories', 'setting'));
    }

    public function dailyreport($startdate, $enddate) {
        $sumOrdersAmount = Order::whereBetween('created_at', [$startdate, $enddate])->sum('amount');
        // $sumOrdersAmount = Order::whereDate('created_at', $startdate)->sum('amount');

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

        $collectionsDateSales = DB::table('payments')
            ->join('orders', 'payments.order_id', "=", 'orders.id')
            ->whereBetween('orders.created_at', [$startdate, $enddate])
            ->select('payments.method as method',
                        DB::raw('SUM(payments.amount) as totalpayment')
                    )
            ->orderBy('payments.method')
            ->groupBy('payments.method')
            ->get();
        

        $collectionsPreviousSales = DB::table('payments')
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
            

        return response()->json(['sumOrdersAmount' => $sumOrdersAmount,
            'categorizedSales' => $categorizedSales,
            'collectionsDateSales' => $collectionsDateSales,
            'collectionsPreviousSales' => $collectionsPreviousSales,
            'listunpaidcustomers' => $listunpaidcustomers,
            'totalunpaid' => $totalunpaid
            ]);
    }   

    public function dailysave (Request $request){
        $report = new Report();
        $report->start = $request->startdate;
        $report->end = $request->enddate;
        $report->type = $request->reporttype;
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
        
        return redirect()->back();

    }
}
