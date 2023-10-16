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
        if($request->input('method') === "0"){
            $status = 'unpaid';
        }
        else{
            $status = 'paid';
        }
        $order = new Order();
        $order->customer_id = $request->input('customer');
        $order->user_id = $request->input('cashier');
        $order->location_id = $request->input('location');
        $order->payment_status = $request->input('method');
        $order->amount = $request->input('dueAmount');
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
                    $success = false; // Set success flag to false if saving fails
                    break; // Exit the loop early, as there is no need to continue
                }
                
            }
            return response()->json(['message' => "Thank you!"]);
        
        }

        else{
            return response()->json(['message' => "Sorry."]);
        }
    }

    public function getUnpaids(){
        $orders = Order::getUnpaidOrders();
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
        // $payment->save();
        $totalOrders = Order::totalOrders($request->input('customer_id'));



        if ($payment->save()) {
            $unpaid_orders = Order::where('customer_id', $request->input('customer_id'))
                ->where('status', 'unpaid')
                ->orderBy('created_at', 'asc'); // Assuming you want to update the oldest orders first
                // ->take($totalOrders) // Take the first $totalOrders rows
                // ->update(['payment_status' => 1]);
            foreach ($unpaid_orders as $unpaid_order){
                
            }
            
        }

        return back()->with('success', 'Customer Created Successfully!');
    }
}
