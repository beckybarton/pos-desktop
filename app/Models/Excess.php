<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Excess extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_id',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
