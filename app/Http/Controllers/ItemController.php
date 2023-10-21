<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Location;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

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
                'selling_price' => 'required|numeric|min:0',
                'uom' => 'required|string|max:255',
                'category_id' => 'required'
            ]);

            if(Item::create($data)){
                return back()->with('success', 'Item Created Successfully!');
            }
            else{
                return back()->with('error', 'Item Created Not Successful!');
            }
        }
    }
    public function storecategory(Request $request)
    {
        $existingCategory = Category::where('name', $request->input('name'))->first();
        if ($existingCategory){
            return back()->with('error', 'Item already exists!');
        }
        else {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:items,name'
            ]);

            if(Category::create($data)){
                return back()->with('success', 'Category Created Successfully!');
            }
            else{
                return back()->with('error', 'Category Creation Not Successful!');
            }
        }
    }

    public function index() {
        $items = Item::select('items.id as id', 'items.name as item_name', 'categories.name as category_name', 'items.selling_price', 'items.uom')
            ->orderBy('items.name', 'asc')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->paginate(10);
        $locations = Location::all();
        $categories = Category::all();
        $setting = Setting::first();
        
        return view('items.index', compact('items', 'locations', 'categories', 'setting'));
    }

    public function update(Request $request, Item $item)
    {
        $item = Item::findOrFail($request->id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'selling_price' => 'required|numeric|min:0',
            'uom' => 'required|string|max:255',
            'category_id' => 'required'
        ]);

        if ($item->update($data)) {
            return back()->with('status', 'Item Updated Successfully!');
        } 
        else {
            return back()->with('error', 'Failed to update item. Please try again.');
        }

        
    }

    public function categories(){
        // $items = Item::select('items.id as id', 'items.name as item_name', 'categories.name as category_name', 'items.selling_price', 'items.uom')
        //     ->orderBy('items.name', 'asc')
        //     ->join('categories', 'items.category_id', '=', 'categories.id')
        //     ->paginate(10);
        $locations = Location::all();
        $setting = Setting::first();
        $categories = Category::orderBy('name', 'desc')
          ->paginate(10);
        
        return view('items.categories', compact('locations', 'categories', 'setting'));
    }
}
