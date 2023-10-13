<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Item;
use App\Models\Location;

class PosController extends Controller
{
    public function index() {
        $setting = Setting::first();
        $locations = Location::all();
        return view('pos.index', compact('setting','locations'));
    }

    public function searchItems(Request $request){
        $searchQuery = $request->input('search_query');
        $items = Item::where('name', 'like', "%$searchQuery%")->get();
        $itemsWithoutQuotes = $items->map(function($item) {
            $item['name'] = str_replace("'", "", $item['name']);
            return $item;
        });
        return response()->json($itemsWithoutQuotes);
    }

}