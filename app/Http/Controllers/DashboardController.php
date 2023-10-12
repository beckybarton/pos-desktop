<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index() {
      $items = Item::orderBy('name', 'desc')
        ->paginate(10);
      
      return view('dashboard.index', compact('items'));
    }
}
