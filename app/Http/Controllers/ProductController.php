<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Product::latest();
        $types = ProductType::get();
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('name', 'LIKE', '%'.$param_search.'%');
            });
        }
        $data = [
            'loop' => $loop->paginate(15),
            'types' => $types
        ];
        return view('admin.products.index', $data);
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
            'image' => ['mimes:jpg,jpeg,png,gif'],
            'price' => ['required', 'numeric'],
            'quantity' => ['numeric'],
            'product_type' => ['required']
        ]);

        $store = new Product;
        
        $store->name = $request->name;
        $store->price = $request->price;
        $store->coins = $request->amount_coins;
        $store->quantity = $request->quantity;
        $store->days_without_ads = $request->days_without_ads;
        $store->product_type_id = $request->product_type;
        if($request->has('image')){
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $extensionFile = $file->getClientOriginalExtension();
            $check = in_array($extensionFile, $allowedExtensions);
            if($check){
                $path = Storage::disk('public')->putFile("product", $file);
                $store->image = $path;
            }else{
                $response['excluded'][] = "$originalName fue excluido, solo puedes subir jpg, jpeg, png, gif";
            }
        }

        if($store->save()){
            return redirect()->route('products.index')->with('success', 'Producto creado correctamente');
        }
        return redirect()->route('products.index')->with('error', 'Ups, se complico la cosa');
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
            'image' => ['mimes:jpg,jpeg,png,gif'],
            'price' => ['required', 'numeric'],
            'quantity' => ['numeric'],
            'product_type' => ['required']
        ]);

        $update = Product::find($id);
        
        $update->name = $request->name;
        $update->price = $request->price;
        $update->coins = $request->amount_coins;
        $update->quantity = $request->quantity;
        $update->days_without_ads = $request->days_without_ads;
        $update->product_type_id = $request->product_type;
        if($request->has('image')){

            // Eliminamos en caso de que exista una imagen anterior
            if($update->image && !empty($update->image)){
                $path = $update->image;
                if(!empty($path) && Storage::disk('public')->exists($path)){
                    Storage::disk('public')->delete($path);
                }
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $extensionFile = $file->getClientOriginalExtension();
            $check = in_array($extensionFile, $allowedExtensions);
            if($check){
                $path = Storage::disk('public')->putFile("product", $file);
                $update->image = $path;
            }else{
                $response['excluded'][] = "$originalName fue excluido, solo puedes subir jpg, jpeg, png, gif";
            }
        }

        if($update->save()){
            return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente');
        }
        return redirect()->route('products.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $manga = Product::where('id', $id)->get()->first();
        $path = "";
        if($manga->image && !empty($manga->image)){
            $path = $manga->image;
        }
        $delete = Product::destroy($id);
        if($delete){
            if(!empty($path) && Storage::disk('public')->exists($path)){
                Storage::disk('public')->delete($path);
            }
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