<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $loop = Product::get();
        return view('admin.products.index', ['loop' => $loop]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $types = ProductType::get();
        return view('admin.products.create', [
            'types' => $types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'coins' => ['numeric'],
            'quantity' => ['numeric'],
            'product_type_id' => ['required']
        ]);

        $store = new Product;
        
        $store->name = $request->name;
        $store->price = $request->price;
        $store->coins = $request->coins;
        $store->quantity = $request->quantity;
        $store->product_type_id = $request->product_type_id;

        if($store->save()){
            $response['success'] = [
                'msg' => "Producto creado correctamente.",
                'data' => $store
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $store
            ];
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $edit = Product::find($id);
        $types = ProductType::get();
        return view('admin.products.edit', [
            'edit' => $edit,
            'types' => $types
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $request->validate([
            'name' => ['required', 'max:255'],
            'price' => ['required', 'numeric'],
            'coins' => ['numeric'],
            'quantity' => ['numeric'],
            'product_type_id' => ['required']
        ]);

        $update = Product::find($id);
        
        $update->name = $request->name;
        $update->price = $request->price;
        $update->coins = $request->coins;
        $update->quantity = $request->quantity;
        $update->product_type_id = $request->product_type_id;

        if($update->save()){
            $response['success'] = [
                'msg' => "Producto actualizado correctamente.",
                'data' => $update
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $update
            ];
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $delete = Product::destroy($id);
        if($delete){
            $response['msg'] = "Producto eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}