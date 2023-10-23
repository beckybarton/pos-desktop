<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'start',
        'end',
        'user_id',
    ];

    public function cash_on_hand(){
        return $this->hasMany(CashOnHand::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
