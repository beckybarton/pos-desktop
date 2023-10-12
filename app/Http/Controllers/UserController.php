<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request){
        
        // Validate the form data
        $validatedData = $request->validate([
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'role' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ]);

        $password = strtolower($validatedData['first_name'] . $validatedData['last_name']);
        $password = str_replace(' ', '', $password);

        $existingUser = User::where('username', $password)->first();
        if ($existingUser){
            return back()->with('error', 'Item already exists!');
        }

        else{
            $user = new User();
            $user->first_name = $validatedData['first_name'];
            $user->last_name = $validatedData['last_name'];
            $user->username = strtolower($password);
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];
            $user->password = Hash::make($password);
            
            if($user->save()){
                return back()->with('success', 'User added successfully');
            }
        }
        
        
    }

    public function index() {
        $users = User::orderBy('username', 'desc')
          ->paginate(10);
        
        return view('users.index', compact('users'));
    }
}
