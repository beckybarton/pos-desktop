<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function payments(){
        return $this->hasMany(Payment::class);
    }

    public function customer_credits(){
        return $this->hasMany(CustomerCredit::class);
    }
}
