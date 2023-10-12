<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function store(Request $request)
    {
        $existingItem = Item::where('name', $request->input('name'))->first();
        if ($existingItem){
            return back()->with('error', 'Item already exists!');
        }
        else {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:items,name',
                'selling_price' => 'required|numeric|min:0'
            ]);

            if(Item::create($data)){
                return back()->with('success', 'Item Created Successfully!');
            }
            else{
                return back()->with('error', 'Item Created Not Successful!');
            }
        }

        
    }
}
