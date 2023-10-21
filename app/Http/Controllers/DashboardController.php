<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Location;
use App\Models\Category;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index() {
      $items = Item::orderBy('name', 'desc')
        ->paginate(10);
      $locations = Location::all();
      $categories = Category::all();
      
      return view('dashboard.index', compact('items', 'locations', 'categories'));
    }

    public function setting() {
      $items = Item::orderBy('name', 'desc')
        ->paginate(10);
      $locations = Location::all();
      $categories = Category::all();
      
      return view('dashboard.settings', compact('items', 'locations', 'categories'));
    }

    public function storesetting(Request $request){
      // dd($request);
      $data = $request->validate([
        'company_name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
      ]);
      if(Setting::create($data)){
        // return view('dashboard.settings');
        return back()->with('success', 'Company Information Updated Successfully!');
      }
      else{
          return back()->with('error', 'Company Information Update Not Successful!');
      }
    }
}
