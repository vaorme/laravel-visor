<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller{
    public function index(){
        $loop = Product::latest()->paginate(15);
        return view('ecommerce.shop', ['loop' => $loop]);
    }
}
