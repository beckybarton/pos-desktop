<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'selling_price',
        'uom',
        'category_id',
        'cost'
    ];

    public function order_item(){
        return $this->hasMany(OrderItem::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
