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

class PosController extends Controller
{
    public function index() {
        $setting = Setting::first();
        $locations = Location::all();
        $categories = Category::all();
        if($setting){
            return view('pos.index', compact('setting','locations', 'categories'));
        }
        else{
            return redirect()->route('dashboard.setting')->with('error', 'Setup company details first.');
        }
        
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
        $used_credit = 0;
        $remaining_credit = 0;
        $method = $request->input('method');

        $payment_amount = 0;

        $paymentInfo = $this->paymentInfo($request);

        $existingCredit = CustomerCredit::where('customer_id', $request->input('customer'))->first();
        if($request->input('received') > $request->input('dueAmount')){
            $payment_amount = $request->input('dueAmount');
        }
        else {
            $payment_amount = $request->input('received');
        }

        // IF UNPAID, AND UNPAID WITH EXISTING CREDITS
        if($request->input('method') === "Unpaid"){
            $status = 'unpaid';
            $remaining_due =  $request->input('dueAmount');

            if($paymentInfo && $existingCredit->remaining > 0){
                $status = $paymentInfo['status'];
                $remaining_due = $paymentInfo['remaining_due'];
                $remaining_credit = $paymentInfo['remaining_credit'];
                $used_credit = $paymentInfo['used_credit'] + $existingCredit->used;
                $payment_amount = $paymentInfo['payment'];
                $method = "Credit";
            }

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

            // SAVE ORDER_ITEMS
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

            if($request->input('method') !== "Unpaid" || $existingCredit){
                $payment = new Payment();
                $payment->customer_id = $request->input('customer');
                $payment->amount = $payment_amount;
                $payment->method = $method;
                $payment->order_id = $order->id;
                $payment->save();
            }
                

            // SAVE CUSTOMERCREDIT
            $newCreditDeduction = false;
            if($existingCredit){
                $newCreditDeduction = $this->newCustomerCreditDeduction($order->id, $request, $paymentInfo['used_credit'], $remaining_credit, $existingCredit);
            }


            return response()->json(['message' => "Thank you!"]);
        
        }

        else{
            return response()->json(['message' => "Sorry."]);
        }
    }

    public function newCustomerCreditDeduction($order_id, $request, $used_credit, $remaining_credit, $existingCredit){
        $creditDeduction = new CustomerCreditDeduction();
        $creditDeduction->order_id = $order_id;
        $creditDeduction->customer_id = $request->input('customer');
        $creditDeduction->used = $used_credit;
        $creditDeduction->save();

        //SAKTO RA ANG CREDIT NGA NAGAMIT
        $existingCredit->used = $existingCredit->used + $used_credit;
        // $existingCredit->remaining = $existingCredit->remaining - $used_credit;
        $existingCredit->remaining = $remaining_credit;
        $existingCredit->save();

        return true;
    }

    public function paymentInfo($request){
        $existingCredit = CustomerCredit::where('customer_id', $request->input('customer'))->first();
        $credit = null;
        if ($existingCredit){
            if ($existingCredit->remaining > $request->input('dueAmount')){
                $credit = [
                    'payment' => $request->input('dueAmount'),
                    'remaining_due' => 0,
                    'status' => 'paid',
                    'used_credit' => $request->input('dueAmount'),
                    'remaining_credit' => $existingCredit->remaining - $request->input('dueAmount'),
                ];
            }
            else{
                if($request->input('dueAmount') - $existingCredit->remaining == 0){
                    $status = 'paid';
                    $remaining_due = 0;
                }
                else{
                    $status = 'partially paid';
                    $remaining_due = $request->input('dueAmount') - $existingCredit->remaining;
                }
                $credit = [
                    'payment' => $existingCredit->remaining,
                    'remaining_due' => $remaining_due,
                    'status' => $status,
                    'used_credit' => $existingCredit->remaining,
                    'remaining_credit' => 0
                ];
            }
        }
        return $credit;
        
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
        $unpaid_orders = Order::where('customer_id', $request->input('customer_id'))
                ->whereNot('status', 'paid')
                ->orderBy('created_at', 'asc')
                ->get();

        $payment_received = $request->input('payment_received');
        $payment_amount = 0;
        $payment_remaining_due = 0;

        foreach ($unpaid_orders as $unpaid_order) {
            if ($payment_received <= 0) {
                break;
            }
        
            if ($payment_received >= $unpaid_order->remaining_due) {
                $payment_amount = $unpaid_order->remaining_due;
                $payment_received -= $unpaid_order->remaining_due;
                $unpaid_order->payment += $unpaid_order->remaining_due;
                $unpaid_order->remaining_due = 0;
                $unpaid_order->status = "paid";
            } else {
                $payment_amount = $payment_received;
                $unpaid_order->payment += $payment_received;
                $unpaid_order->remaining_due -= $payment_received;
                $payment_received = 0; // Set payment_received to 0 to exit the loop
            }
        
            $payment = new Payment();
            $payment->customer_id = $unpaid_order->customer_id;
            $payment->amount = $payment_amount;
            $payment->order_id = $unpaid_order->id;
            $payment->method = $request->input('method');

            $payment->save();
            $unpaid_order->save();
        }
        

        $unpaid_orders_sum = $unpaid_orders->sum('remaining_due');
        if ($payment_received > $unpaid_orders_sum){
            $existingCredit = CustomerCredit::where('customer_id', $request->input('customer_id'))->first();
            if ($existingCredit){
                $credit = $existingCredit;
                $credit->amount = $credit->amount + ($payment_received - $unpaid_orders_sum);
                $credit->remaining = $credit->remaining + ($payment_received - $unpaid_orders_sum);
            }
            else{
                $credit = new CustomerCredit();
                $credit->customer_id = $request->input('customer_id');
                $credit->amount = $payment_received - $unpaid_orders_sum;
                $credit->used = 0;
                $credit->remaining = $payment_received - $unpaid_orders_sum;
            }
            
            $credit->save();
        }
        return response()->json(['unpaid_orders' => $unpaid_orders]);
    }

    public function getCustomerCredit(Request $request){
        $credits = CustomerCredit::where('customer_id', $request->input('customer_id'))->first();
        return response()->json(['credits' => $credits]);
    }
}
