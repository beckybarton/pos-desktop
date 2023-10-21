<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Location;
use App\Models\Category;
use App\Models\Setting;

class SettingController extends Controller
{
    public function setting() {
        $setting = Setting::first();
        $items = Item::orderBy('name', 'desc')
          ->paginate(10);
        $locations = Location::all();
        $categories = Category::all();
        
        return view('settings.company', compact('items', 'locations', 'categories', 'setting'));
    }

    public function storesetting(Request $request){
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
        ]);
        $settingexist = Setting::first();
        if ($settingexist){
            if($settingexist->update($data)){
            return back()->with('success', 'Company Information Updated Successfully!');
            }
            else{
            return back()->with('error', 'Company Information Update Not Successful!');
            }
        }
        else{
            if(Setting::create($data)){
            return back()->with('success', 'Company Information Updated Successfully!');
            }
            else{
            return back()->with('error', 'Company Information Update Not Successful!');
            }
        }
    }

    public function denomination(){
        $denominations = Denomination::all();
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();

        return view('settings.denominations', compact('denominations', 'locations', 'categories', 'setting'));
    }

    public function storedenomiation(Request $request){
        
    }


}
