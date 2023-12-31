<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Location;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm(){
        $locations = Location::all();
        return view('users.login', compact('locations'));
    }

    public function login(Request $request){
        // Validate the form data
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'location' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('username', 'password'))) {
            // Save location to session
            session([
                'selected_location' => $request->input('location'),
                'login_time' => Carbon::now()->toDateTimeString()
            ]);
            Session::put('selected_location', $request->input('location'));

            return redirect()->intended('/');
        }

        // If authentication failed, redirect back with an error message
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }

    
}
