<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function order_item(){
        return $this->hasMany(OrderItem::class);
    }

    public function user(){
        return $this->belongs(User::class);
    }
}
