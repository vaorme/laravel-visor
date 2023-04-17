<?php

namespace App\Http\Controllers;

use App\Models\MangaType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MangaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = MangaType::get();
        $data = [
            'loop' => $loop,
        ];
        if(isset($request->id)){
            $edit = MangaType::find($request->id);
            $data['edit'] = $edit;
        }
        return view('admin.manga.type.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manga.type.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['required', 'max:24'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:manga_type',]
        ]);

        $store = new MangaType;

        $store->name = $request->name;
        $store->slug = $request->slug;
        if(!empty($store->description)){
            $store->description = $request->description;
        }

        if($store->save()){
            return redirect()->route('manga_types.index')->with('success', 'Tipo creado correctamente');
        }
        return redirect()->route('manga_types.index')->with('error', 'Ups, se complico la cosa');
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
    public function edit($id)
    {
        $edit = MangaType::find($id);
        return view('admin.manga.type.edit', ['edit' => $edit]);
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
            'name' => ['required', 'max:24']
        ]);

        $update = MangaType::find($id);

        $update->name = $request->name;
        if($update->slug != $request->slug){
            $slugExists = MangaType::where('slug', $request->slug)->exists();
            if($slugExists){
                return Redirect::back()->withErrors("Error: Slug ya existente");
            }
            $update->slug = $request->slug;
        }
        $update->description = $request->description;

        if($update->save()){
            return redirect()->route('manga_types.index')->with('success', 'Tipo actualizado correctamente');
        }
        return redirect()->route('manga_types.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = MangaType::destroy($id);
        if($delete){
            $response['msg'] = "Tipo eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
