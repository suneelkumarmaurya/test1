<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Middleware\homeAuth;

class homeController extends Controller
{

    public function __construct(){
        $this->middleware(homeAuth::class);
    }
    public function homepage(){
        return view('welcome');
    }
}
