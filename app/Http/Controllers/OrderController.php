<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller{
    public function index(Request $request){
        $loop = Order::latest();
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('order_id', 'LIKE', '%'.$param_search.'%')
                ->orWhere('name', 'LIKE', '%'.$param_search.'%')
                ->orWhere('status', 'LIKE', '%'.$param_search.'%')
                ->orWhere('transaction_id', 'LIKE', '%'.$param_search.'%')
                ->orWhere('order_id', 'LIKE', '%'.$param_search.'%')
                ->orWhere('user_id', 'LIKE', '%'.$param_search.'%');
            });
        }
        $data = [
            'loop' => $loop->paginate(15)
        ];
        return view('admin.orders.index', $data);
    }
    public function create(){}
    public function store(Request $request){}
    public function show($id){}

    public function edit($id){
        $edit = Order::find($id);
        return view('admin.orders.edit', [
            'edit' => $edit,
        ]);
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => ['string', 'max:255'],
            'email' => ['string', 'email', 'regex:/^.+@.+$/i','max:255'],
            'status' => ['required','string'],
        ]);

        $update = Order::find($id);
        $update->status = $request->status;

        if($update->save()){
            return redirect()->route('orders.index')->with('success', 'Orden actualizado correctamente');
        }
        return redirect()->route('orders.index')->with('error', 'Ups, se complico la cosa');
    }

    public function destroy($id){
        $delete = Order::destroy($id);
        if($delete){
            return response()->json([
                'status' => "success",
                'msg' => "Eliminado correctamente"
            ]);
        }
        return response()->json([
            'status' => "error",
            'msg' => "Ups, algo paso",
        ]);
    }
}
