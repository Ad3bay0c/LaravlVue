<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email Field is required',
            'password.required' => 'Password cannot be empty'
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Login Successfully',
                'success' => true
            ], 201);
        } else {
            return response()->json([
                'message' => 'Invalid Login Details',
                'success' => false
            ], 401);
        }
    }

    public function logout()
    {
        Auth::logout();
    }
}
