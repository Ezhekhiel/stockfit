<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.registrasi');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'=>'required|max:125',
            'role_id'=>'required',
            'email'=>'required|email:dns|unique:users',
            'password'=>'required',
        ]);
        // $validatedData['password']= bcrypt($validatedData['password']);
        $validatedData['password'] = Hash::make($validatedData['password']);
        user::create($validatedData);

        // $request->session()->flash('success','Registration Success Full!');
        return redirect('/register')->with('success','Registration Success Full!');
    }
}
