<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller{
    function policies(){
        return view('pages.policies');
    }
}
