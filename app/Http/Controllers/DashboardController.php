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
      $setting = Setting::first();
      
      return view('dashboard.index', compact('items', 'locations', 'categories', 'setting'));
    }
}
