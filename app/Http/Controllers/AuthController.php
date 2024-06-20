<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function proseslogin(Request $request){
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');
        if(Auth::guard('karyawan')->attempt($credentials, $remember)){
            return redirect('/dashboard');
        } else {
            return redirect('/')->with(['warning'=>'Email / Password Anda Salah']);
        }
    }

    public function proseslogout(){
        if(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
            return redirect('/');
        }
    }

    public function proseslogoutadmin(){
        if(Auth::guard('user')->check()){
            Auth::guard('user')->logout();
            return redirect('/panel');
        }
    }

    public function prosesloginadmin(Request $request){
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if(Auth::guard('user')->attempt($credentials, $remember)){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Email / Password Anda Salah']);
        }
    }
}

