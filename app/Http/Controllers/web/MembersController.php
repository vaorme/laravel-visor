<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MembersController extends Controller{
    public function index(Request $request){
        $users = User::orderBy('created_at', 'desc');
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $users->where(function ($query) use ($param_search) {
                $query->where('username', 'LIKE', '%'.$param_search.'%')->orWhereHas('profile', function ($profileQuery) use ($param_search) {
                    $profileQuery->where('name', 'LIKE', '%'.$param_search.'%');
                });;
            });
        }
        return view('users', ['loop' => $users->paginate(18)]);
    }
}
