<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function setCreatedAtAttribute($value){
        $timezone = Setting::first()->timezone;
        $this->attributes['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $value, date_default_timezone_get())
            ->timezone($timezone) 
            ->toDateTimeString();

            $this->attributes['updated_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $value, date_default_timezone_get())
            ->timezone($timezone) 
            ->toDateTimeString();
    }
}
