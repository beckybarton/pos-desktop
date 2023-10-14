<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function getLocationNameById($locationId){
        $location = self::find($locationId);

        if ($location) {
            return $location->name;
        }

        return null;
    }

    public function order(){
        return $this->has(Order::class);
    }
}
