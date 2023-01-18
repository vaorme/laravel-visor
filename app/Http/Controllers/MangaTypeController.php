<?php

namespace App\Http\Controllers;

use App\Models\MangaType;
use Illuminate\Http\Request;

class MangaTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $types = MangaType::get();
        return view('admin.manga.type.index', ['types' => $types]);
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
            'name' => ['required', 'max:24']
        ]);

        $type = new MangaType;

        $type->name = $request->name;
        if(!empty($type->description)){
            $type->description = $request->description;
        }

        if($type->save()){
            $response['success'] = [
                'msg' => "Tipo creado correctamente.",
                'data' => $type
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $type
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
    public function edit($id)
    {
        $type = MangaType::find($id);
        return view('admin.manga.type.edit', ['type' => $type]);
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

        $type = MangaType::find($id);

        $type->name = $request->name;
        $type->description = $request->description;

        if($type->save()){
            $response['success'] = [
                'msg' => "Tipo actualizado correctamente.",
                'data' => $type
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $type
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
