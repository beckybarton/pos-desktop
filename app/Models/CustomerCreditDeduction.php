<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCreditDeduction extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'order_id',
        'used'
    ];

    public function customer_credit(){
        return $this->belongsTo(CustomerCredit::class);
    }
}
