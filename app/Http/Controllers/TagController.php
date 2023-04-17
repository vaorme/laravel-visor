<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TagController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Tag::get();
        $data = [
            'loop' => $loop,
        ];
        if(isset($request->id)){
            $edit = Tag::find($request->id);
            $data['edit'] = $edit;
        }
        return view('admin.tags.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
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
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);

        $store = new Tag;

        $store->name = $request->name;
        $store->slug = $request->slug;

        if($store->save()){
            return redirect()->route('tags.index')->with('success', 'Tag creado correctamente');
        }
        return redirect()->route('tags.index')->with('error', 'Ups, se complico la cosa');
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
        $edit = Tag::find($id);
        return view('admin.tags.edit', ['edit' => $edit]);
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
            'name' => ['required', "max:50"],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);

        $update = Tag::find($id);
        $update->name = $request->name;
        $update->slug = $request->slug;

        if($update->slug != $request->slug){
            $slugExists = Tag::where('slug', $request->slug)->exists();
            if($slugExists){
                return Redirect::back()->withErrors("Error: Slug ya existente");
            }
            $update->slug = $request->slug;
        }
        if($update->save()){
            return redirect()->route('tags.index')->with('success', 'Tag actualizado correctamente');
        }
        return redirect()->route('tags.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $delete = Tag::destroy($id);
        if($delete){
            $response['msg'] = "Tag eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
