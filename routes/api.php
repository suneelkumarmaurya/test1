<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/getapi',[apiController::class,'index']);

Route::post('/user/registers',[UserController::class,'store']);

Route::put('user/{id}',[UserController::class,'updatedata']);

Route::patch('user/forgetPassword',[UserController::class,'verifyEmailOtp']);

Route::patch('user/{id}',[UserController::class,'change_password']);

 Route::post('user/forgetPassword',[UserController::class,'forget_password']);



 Route::get('find',function(){
    p('working');
 });
