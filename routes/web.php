<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/',[homeController::class,'homepage']);
Route::get('auth/google',[GoogleController::class,'loginwithgoogle'])->name('login');

Route::any('auth/google/callback',[GoogleController::class,'callbackFromGoogle'])->name('callback');

 Route::get('home',[ProfileController::class,'logedIN'])->name('home');
// Route::get('home',function(){
//          return view('home');
//      })->name('home');

Route::get('logout',[ProfileController::class,'logout'])->name('logout');
