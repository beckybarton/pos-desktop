<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use PDF;


class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $existingCustomer = Customer::where('name', $request->input('name'))->first();
        if ($existingCustomer){
            return back()->with('error', 'Customer already exists!');
        }
        else {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:customers,name'
            ]);

            if(Customer::create($data)){
                if($request->route()->getName() == "pos.index"){
                    return view('pos.index');
                }
                else{
                    return back()->with('success', 'Customer Created Successfully!');
                }
                
            }
            else{
                return back()->with('error', 'Customer Creation Not Successful!');
            }
        }
    }

    public function index(){
        $customers = Customer::orderBy('name', 'asc')
            ->paginate(10);
        $locations = Location::all();
        $categories = Category::all();
        $items = Item::all();
        $setting = Setting::first();

        // $orders = Order::select('orders.customer_id', 
        //             'customers.name as customer_name', 
        //             DB::raw('SUM(orders.remaining_due) as total_remaining_due')
        //         )
        //     ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
        //     ->groupBy('orders.customer_id', 'customers.name')
        //     ->get();

        // $collectionsDateSales = DB::table('payments')
        //     ->join('orders', 'payments.order_id', "=", 'orders.id')
        //     ->whereBetween('orders.created_at', [$startdate, $enddate])
        //     ->select('payments.method as method',
        //                 DB::raw('SUM(payments.amount) as totalpayment')
        //             )
        //     ->orderBy('payments.method')
        //     ->groupBy('payments.method')
        //     ->get();

        // $customers = Customer::select('customers.name as name', 
        //                 'customers.id as id')
        //             ->join('orders', 'orders.customer_id', "=", 'customers.id')
        //             ->groupBy('customers.name', 'customers.id')
        //             ->paginate(10);

        $customers = Customer::select('customers.name as name', 
                    'customers.id as id',
                    DB::raw('SUM(orders.remaining_due) as total_remaining_due')) // Calculate the total remaining_due using SUM
                ->leftJoin('orders', 'orders.customer_id', '=', 'customers.id')
                ->groupBy('customers.name', 'customers.id')
                ->paginate(10);

      
        return view('customers.index', compact('items', 'locations', 'categories', 'customers', 'setting')); 
    }

    public function downloadSoa($customerId){
        $customerorders = Order::getCustomerReceivables($customerId);
        // dd($customerorders);
        $pdf = PDF::loadView('customers.billing', ['customerorders' => $customerorders]);
        return $pdf->download('billing_statement.pdf');
    }
}
