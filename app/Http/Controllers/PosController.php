<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Item;

class PosController extends Controller
{
    public function index() {
        $setting = Setting::first();
        return view('pos.index', compact('setting'));
    }

    public function searchItems(Request $request){
        $searchQuery = $request->input('search_query');
        // Implement your item search logic here based on the $searchQuery
        // For example, you might use Eloquent queries to search items in your database
        $items = Item::where('name', 'like', "%$searchQuery%")->get();
        return response()->json($items);
    }

}
