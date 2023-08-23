<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            // print_R($request->all());exit("hello");
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ];

            $customMessage = [
                'email.required' => 'Email field is required !!',
                'password.required' => 'Password field is required !!',
            ];

            $this->validate($request, $rules, $customMessage);
            $data = $request->all();
            // dd($data);
            if (auth()->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                auth()->user();
                return redirect('dashboard');
            } else {
                return redirect()->back()->with("error_message", "Invalid Username or Password !!");
            }
        }
        return view('login');
    }
    public function dashboard()
    {
        $getUser = User::get()->all();
        return view('welcome')->with(compact('getUser'));
    }
    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }

}