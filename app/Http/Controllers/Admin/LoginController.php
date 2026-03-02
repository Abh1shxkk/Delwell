<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    public function index(){
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        // Validate login input
        $validator = Validator::make($request->all(), [
            'login'    => 'required', // can be email or username
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.login')->withInput()->withErrors($validator);
        }

        // Determine if input is an email or username
        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Attempt login using either email or username
        if (Auth::guard('admin')->attempt([
            $login_type => $request->input('login'),
            'password' => $request->input('password')
        ])) {
            // Check if the authenticated user is really an admin
            if (Auth::guard('admin')->user()->role !== 'admin') {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error', 'You are not authorized to access this page.');
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'Invalid login credentials.');
    }


    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
