<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\googleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use \App\Http\Middleware\userAuth;
use Session;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware(userAuth::class);
    }

   public function logedIN(){
    return view('home');
   }

   public function logout(){

    Auth::logout();
    Session::forget('id');

    return redirect('/');
   }

}
