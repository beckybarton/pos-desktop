<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Location;

class DashboardController extends Controller
{
    public function index() {
      $items = Item::orderBy('name', 'desc')
        ->paginate(10);
      $locations = Location::all();
      
      return view('dashboard.index', compact('items', 'locations'));
    }
}
