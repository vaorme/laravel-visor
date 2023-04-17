<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Category::get();
        $data = [
            'loop' => $loop,
        ];
        if(isset($request->id)){
            $edit = Category::find($request->id);
            $data['edit'] = $edit;
        }
        return view('admin.categories.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['required', "max:50"],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:categories',]
        ]);

        $currentOrder = Category::orderBy('id', 'DESC')->first();
        $count = 0;
        if($currentOrder){
            $count = $currentOrder->order + 1;
        }else{
            $count = 1;
        }

        $store = new Category;

        $store->order = $count;
        $store->name = $request->name;
        $store->slug = $request->slug;

        if($store->save()){
            return redirect()->route('categories.index')->with('success', 'Categoria creada correctamente');
        }
        return redirect()->route('categories.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $edit = Category::find($id);
        return view('admin.categories.edit', ['edit' => $edit]);
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
            'name' => ['required', "max:50"],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);

        $update = Category::find($id);
        $update->name = $request->name;
        if($update->slug != $request->slug){
            $slugExists = Category::where('slug', $request->slug)->exists();
            if($slugExists){
                return Redirect::back()->withErrors("Error: Slug ya existente");
            }
            $update->slug = $request->slug;
        }

        if($update->save()){
            return redirect()->route('categories.index')->with('success', 'Categoria actualizada correctamente');
        }
        return redirect()->route('categories.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $delete = Category::destroy($id);
        if($delete){
            $response['msg'] = "Categoria eliminada correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
