<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller{
    public function index(){
        return view('ecommerce.checkout');
    }
}
