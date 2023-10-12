<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $locationExist = Location::where('name', $request->input('name'))->first();
        if ($locationExist){
            return back()->with('error', 'Location already exists!');
        }
        else {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:locations,name',
            ]);

            if(Location::create($data)){
                return back()->with('success', 'Location Created Successfully!');
            }
            else{
                return back()->with('error', 'Location Created Not Successful!');
            }
        }

        
    }

    public function index() {
        $locations = Location::orderBy('name', 'desc')
          ->paginate(10);
        
        return view('locations.index', compact('locations'));
      }
}
