<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MembersController extends Controller{
    public function index(){
        $users = User::orderBy('created_at', 'desc')->paginate(18);
        return view('users', ['loop' => $users]);
    }
}
