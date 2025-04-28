<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email', // Ensure it's a valid email
            'password' => 'required|string',
        ]);

        // Retrieve the user by email
        $user = DB::table('registered_user')->where('u_email', $credentials['email'])->first();

        // Check if user exists and validate the password
        if ($user && Hash::check($credentials['password'], $user->pass_word)) {
            // Log the user in
            Auth::loginUsingId($user->u_id); // Assuming 'id' is the primary key in the table
            $request->session()->regenerate();
            return redirect()->intended('/homepage'); // Redirect to the dashboard or intended URL
        }

        // Return error if authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/homepage');
    }
}