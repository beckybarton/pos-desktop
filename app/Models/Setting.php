<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable =[
        'company_name',
        'address'
    ];

    public function companydetails(){
        $companydetails = Setting::get();
        return $companydetails;
    }
}
