<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\googleUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use \App\Http\Middleware\homeAuth;
use Session;

class GoogleController extends Controller
{
    public function __construct(){
        $this->middleware(homeAuth::class);
    }
    public function loginwithgoogle(){

        return Socialite::driver('google')->redirect();
     }


     public function callbackFromGoogle(Request $request){
        try {
           // dd($request->all());
          $user= Socialite::driver('google')->stateless()->user();
          //dd($user);
         $is_user= googleUser::where('email',$user->getEmail())->first();
         if(!$is_user){
          $saveUser = googleUser::updateOrCreate(
               ['google_id'=>$user->getId()],
               [
                   'name'=>$user->getName(),
                   'email'=>$user->getEmail(),
                   'password'=>Hash::make($user->getName().'@'.$user->getId())
               ]
           );
          // dd($saveUser);
         }else{
         $saveUser =  googleUser::where('email',$user->getEmail())->update([
                   'google_id'=>$user->getId(),
               ]);
              $saveUser= googleUser::where('email',$user->getEmail())->first();
         }
        // dd($saveUser->email);
         Auth::loginUsingId($saveUser->id);
         Session::put('id',$saveUser->id);
         return redirect()->route('home');

       } catch (\Throwable $th) {
           throw $th;
       }
   }

}
