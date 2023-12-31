<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Location;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Denomination;

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

    public function getdenominations(){
        $denominations = Denomination::all();
        return $denominations;
    }

    public function jsondenominations(){
        $denominations = $this->getdenominations();
        return response()->json($denominations);
    }

    public function denomination(){
        $denominations = $this->getdenominations();
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();

        return view('settings.denominations', compact('denominations', 'locations', 'categories', 'setting'));
    }

    public function storedenomination(Request $request){
        $amounts = $request->input('amounts');

        foreach ($amounts as $amount){
            $denimoninationExist = Denomination::where('amount', $amount)->exists();
            if (!$denimoninationExist){
                $denomination = New Denomination();
                $denomination->amount = $amount;
                $denomination->save();
            }
        }
        return back()->with('success', 'Denominations Updated Successfully!');

    }


}
