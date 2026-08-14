<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        $url = url()->previous();
        return view('auth.login',['previous_link'=>$url]);
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email:dns',
            'password' => ['required'],
        ]);
        $link = $request->link;
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended($link);
        }

        return back()->with('login_error','LOGIN FAILLED!');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $url = url()->previous();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($url);
    }
}
