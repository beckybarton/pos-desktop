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
        // return "this";
        return Order::select('orders.customer_id', 'customers.name as customer_name', DB::raw('SUM(order_items.quantity * items.selling_price) as total_price'))
            ->leftJoin('customers', 'orders.customer_id', '=', 'customers.id')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('items', 'order_items.item_id', '=', 'items.id')
            ->where('orders.status', 'unpaid')
            ->groupBy('orders.customer_id', 'customers.name')
            ->get();
    }
}
