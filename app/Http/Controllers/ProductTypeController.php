<?php

namespace App\Http\Controllers;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = ProductType::latest();
        $data = [
            'loop' => $loop->paginate(15),
        ];
        if(isset($request->id)){
            $user = Auth::user();
            if($user->can('product_types.edit')){
                $edit = ProductType::find($request->id);
                $data['edit'] = $edit;
            }else{
                return abort(404);
            }
        }
        return view('admin.products.type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.products.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:24']
        ]);

        $store = new ProductType;

        $store->name = $request->name;

        if($store->save()){
            return redirect()->route('product_types.index')->with('success', 'Tipo creado correctamente');
        }
        return redirect()->route('product_types.index')->with('error', 'Ups, se complico la cosa');
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
        $edit = ProductType::find($id);
        return view('admin.products.type.edit', ['edit' => $edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'max:24']
        ]);

        $update = ProductType::find($id);

        $update->name = $request->name;

        if($update->save()){
            return redirect()->route('product_types.index')->with('success', 'Tipo actulizado correctamente');
        }
        return redirect()->route('product_types.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = ProductType::destroy($id);
        if($delete){
            $response['msg'] = "Tipo eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
