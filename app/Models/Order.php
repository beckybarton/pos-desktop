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
        'amount',
        'payment',
        'remaining_due',
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

    public static function getAllReceivables(){
        $orders = Order::select('orders.customer_id', 
                    'customers.name as customer_name', 
                    DB::raw('SUM(orders.remaining_due) as total_remaining_due')
                )
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->groupBy('orders.customer_id', 'customers.name')
            ->get();

        return response()->json(['orders' => $orders]);
    }

    public static function getCustomerReceivables($customer){
        $orders = Order::select(
                'orders.id',
                'orders.created_at',
                'locations.name as location_name',
                'items.name as item_name',
                'order_items.price',
                'order_items.quantity',
            )
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('payments', 'orders.customer_id', '=', 'payments.customer_id') // Ensure this join condition is correct
            ->leftJoin('items', 'order_items.item_id', '=', 'items.id')
            ->leftJoin('locations', 'orders.location_id', '=', 'locations.id') 
            ->where('orders.customer_id', $customer)
            ->whereNot('orders.status', 'paid')
            ->groupBy('orders.id', 'orders.created_at', 'items.name', 'order_items.price', 'order_items.quantity', 'orders.amount', 'locations.name') 
            ->get();

        $unpaidorders = Order::select(
                'orders.id',
                'orders.created_at',
                'orders.remaining_due as remaining_due',
                'orders.payment as payment',
                'orders.amount as payable',
            )
            ->where('orders.customer_id', $customer)
            ->whereNot('orders.status', 'paid')
            ->groupBy('orders.id', 'orders.created_at', 'orders.remaining_due', 'orders.payment', 'orders.amount') 
            ->get();
        

        $total_remaining_due = $unpaidorders->sum('remaining_due');
        $total_payable = $unpaidorders->sum('payable');
        $total_payment = $unpaidorders->sum('payment');
        $customer_name = ucwords(Customer::find($customer)->name);

        return (['orders' => $orders, 
            'total_remaining_due' => $total_remaining_due, 
            'customer_name' => $customer_name, 
            'total_payable' => $total_payable,
            'total_payment' => $total_payment,
            'unpaidorders' => $unpaidorders
        ]);
    }

    public static function totalOrders($customer){
        $orders = Order::select(
            'orders.id',
            'orders.created_at',
            'orders.remaining_due as remaining_due',
            'items.name as item_name',
            'order_items.price',
            'order_items.quantity'
        )
        ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
        ->leftJoin('items', 'order_items.item_id', '=', 'items.id')
        ->where('orders.status', 'unpaid')
        ->where('orders.customer_id', $customer)
        ->groupBy('orders.id', 'orders.created_at', 'items.name', 'order_items.price', 'order_items.quantity') // Group by customers.name
        ->get();

        $totalPriceSum = $orders->sum('remaining_due');
        return $totalPriceSum;
    }
}
