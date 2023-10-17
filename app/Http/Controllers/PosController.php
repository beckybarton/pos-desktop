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

class PosController extends Controller
{
    public function index() {
        $setting = Setting::first();
        $locations = Location::all();
        $categories = Category::all();
        return view('pos.index', compact('setting','locations', 'categories'));
    }

    public function searchItems(Request $request){
        $searchQuery = $request->input('search_query');
        $items = Item::where('name', 'like', "%$searchQuery%")->get();
        $itemsWithoutQuotes = $items->map(function($item) {
            $item['name'] = str_replace("'", "", $item['name']);
            return $item;
        });
        return response()->json($itemsWithoutQuotes);
    }

    public function searchCustomers(Request $request){
        $searchQuery = $request->input('search_query');
        $customers = Customer::where('name', 'like', "%$searchQuery%")->get();
        $customersWithoutQuotes = $customers->map(function($customer) {
            $customers['name'] = str_replace("'", "", $customer['name']);
            return $customers;
        });
        return response()->json($customers);
    }

    public function saveOrder(Request $request){
        $status = "";
        $remaining_due = 0;
        $payment_status = null;
        if($request->input('method') === "0"){
            $status = 'unpaid';
            $remaining_due =  $request->input('dueAmount');
        }
        else{
            if($request->input('received') < $request->input('dueAmount')){
                $status = 'partially paid';
                $remaining_due = $request->input('dueAmount') - $request->input('received');
            }
            else if($request->input('received') >= $request->input('dueAmount')){
                $status = 'paid';
                $remaining_due = 0;
            }
            else{
                $status = 'paid';
            }
            
        }

        $payment_amount = 0;
        if($request->input('received') > $request->input('dueAmount')){
            $payment_amount = $request->input('dueAmount');
        }
        else {
            $payment_amount = $request->input('received');
        }

        // $remaining_due = $payment_amount - $request->input('dueAmount');

        $order = new Order();
        $order->customer_id = $request->input('customer');
        $order->user_id = $request->input('cashier');
        $order->location_id = $request->input('location');
        $order->amount = $request->input('dueAmount');
        $order->payment = $payment_amount;
        $order->remaining_due = $remaining_due;
        $order->status = $status;


        if($order->save()){
            $success = true;
            $item_ids = $request->input('item_id');
            $quantities = $request->input('quantity');

            foreach ($item_ids as $index => $item_id) {
                $selling_price = Item::find($item_ids[$index])->selling_price;
                $order_item = new OrderItem();
                $order_item->order_id = $order->id;
                $order_item->item_id = $item_ids[$index];
                $order_item->price = $selling_price;
                $order_item->quantity = $quantities[$index];
                $order_item->save();

                if (!$order_item->save()) {
                    $success = false; 
                    break; 
                }
                
            }

            if($request->input('received')>0){
                $payment = new Payment();
                $payment->customer_id = $request->input('customer');
                $payment->amount = $payment_amount;
                $payment->method = $request->input('method');
                $payment->save();
            }
            return response()->json(['message' => "Thank you!"]);
        
        }

        else{
            return response()->json(['message' => "Sorry."]);
        }
    }

    public function allReceivables(){
        $orders = Order::getAllReceivables();
        return response()->json(['orders' => $orders]);
    }

    public function customerReceivables(Request $request){
        $customerId = $request->input('customerId');
        $customerorders = Order::getCustomerReceivables($customerId);
        return response()->json(['customerorders' => $customerorders]);
    }

    public function receivepayment(Request $request){
        $payment = new Payment();
        $payment->customer_id = $request->input('customer_id');
        $payment->amount = $request->input('payment_received');
        $payment->method = $request->input('method');
        // ($payment->save());

        // $unpaid_orders = Order::getCustomerReceivables($request->input('customer_id'));
        
        if($payment->save()){
            $unpaid_orders = Order::where('customer_id', $request->input('customer_id'))
                    ->whereNot('status', 'paid')
                    ->orderBy('created_at', 'asc')
                    ->get();
            // $unpaid_orders_sum = $unpaid_orders->sum('remaining_due');

            $payment_received = $request->input('payment_received');

            foreach ($unpaid_orders as $unpaid_order) {
                if ($payment_received >= $unpaid_order->remaining_due) {
                    $payment_received -= $unpaid_order->remaining_due;
                    $unpaid_order->payment += $unpaid_order->remaining_due;
                    $unpaid_order->remaining_due = 0;
                    $unpaid_order->status = "paid";
                } else {
                    $unpaid_order->payment += $payment_received;
                    $unpaid_order->remaining_due -= $payment_received;
                    $payment_received = 0;
                }

                $unpaid_order->save();

                // $payment_received -= $unpaid_order->remaining_due;
                if ($payment_received <= 0) {
                    break;
                }
            }
            // return back()->with('success', 'Customer Created Successfully!');
            // return back()->with('success', $unpaid_orders);
            return response()->json(['unpaid_orders' => $unpaid_orders]);
        }
    }
}
