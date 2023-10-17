<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'amount',
        'used',
        'remaining'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function customer_credit_tracking(){
        return $this->hasMany(CustomerCreditTracking::class);
    }
}
