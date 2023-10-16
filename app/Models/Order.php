<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location_id',
        'customer_id',
        'status',
        'payment_status',
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function order_items(){
        return $this->hasMany(OrderItem::class);
    }

    public function user(){
        return $this->belongs(User::class);
    }

    public function location(){
        return $this->belongsTo(Location::class);
    }

    public static function getUnpaidOrders(){

        $orders = Order::select('orders.customer_id', 'customers.name as customer_name', DB::raw('SUM(order_items.quantity * items.selling_price) as total_price'))
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.status', 'unpaid')
            ->groupBy('orders.customer_id', 'customers.name')
            ->get();

        // Sort the orders by customer_name
        $orders = $orders->sortBy(function ($order) {
            return strtoupper($order->customer_name);
        });

        return response()->json(['orders' => $orders]);
    }

    public static function getCustomerReceivables($customer){
        // $orders = Order::select(
        //         'orders.id',
        //         'orders.created_at',
        //         'items.name as item_name',
        //         'order_items.price',
        //         'order_items.quantity',
        //         DB::raw('SUM(order_items.quantity * order_items.price) as total_price')
        //     )
        //     ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
        //     ->leftJoin('items', 'order_items.item_id', '=', 'items.id')
        //     ->where('orders.status', 'unpaid')
        //     ->where('orders.customer_id', $customer)
        //     ->groupBy('orders.id', 'orders.created_at', 'items.name', 'order_items.price', 'order_items.quantity')
        //     ->get();
        $orders = Order::select(
                'orders.id',
                'orders.created_at',
                'items.name as item_name',
                'order_items.price',
                'order_items.quantity'
            )
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.status', 'unpaid')
            ->where('orders.customer_id', $customer)
            ->get();
        
        $formattedOrders = [];
        foreach ($orders as $order) {
            $formattedOrder = [
                'id' => $order->id,
                'created_at' => $order->created_at,
                'item_name' => $order->item_name,
                'price' => $order->price,
                'quantity' => $order->quantity,
            ];

            // Include order_items as an array within each order
            $formattedOrder['order_items'] = $order->order_items;

            $formattedOrders[] = $formattedOrder;
    }

    return response()->json(['orders' => $formattedOrders]);


        // return response()->json(['orders' => $orders]);
    }
}
