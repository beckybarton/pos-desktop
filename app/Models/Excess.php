<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Excess extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_id',
        'method',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
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
