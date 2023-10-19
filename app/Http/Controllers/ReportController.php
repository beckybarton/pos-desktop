<?php

namespace App\Http\Controllers;

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

class ReportController extends Controller
{
    public function index(){
        $items = Item::orderBy('name', 'desc')
        ->paginate(10);
        $locations = Location::all();
        $categories = Category::all();
        return view('reports.index', compact('items','locations', 'categories'));
    }

    public function dailyreport($startdate) {
        $sumOrdersAmount = Order::whereDate('created_at', $startdate)->sum('amount');
        return response()->json($sumOrdersAmount);
    }   
}
