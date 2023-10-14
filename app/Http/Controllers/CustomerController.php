<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Location;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $existingCustomer = Customer::where('name', $request->input('name'))->first();
        if ($existingCustomer){
            return back()->with('error', 'Customer already exists!');
        }
        else {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:customers,name'
            ]);

            if(Customer::create($data)){
                if($request->route()->getName() == "pos.index"){
                    return view('pos.index');
                }
                else{
                    return back()->with('success', 'Customer Created Successfully!');
                }
                
            }
            else{
                return back()->with('error', 'Customer Creation Not Successful!');
            }
        }

        
    }
}
