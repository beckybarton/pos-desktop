<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOnHand extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'denomination',
        'pcs',
        'amount'
    ];

    public function report(){
        return $this->belongsTo(Report::class);
    }
}
