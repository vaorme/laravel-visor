<?php

namespace App\Http\Controllers;

use App\Models\MangaDemography;
use Illuminate\Http\Request;

class MangaDemographyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loop = MangaDemography::get();
        return view('admin.manga.demography.index', ['loop' => $loop]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manga.demography.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:24']
        ]);
        
        $demography = new MangaDemography;

        $demography->name = $request->name;
        if(!empty($request->description)){
            $demography->description = $request->description;
        }

        if($demography->save()){
            $response['success'] = [
                'msg' => "Demografia creada correctamente.",
                'data' => $demography
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $demography
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
        $edit = MangaDemography::find($id);
        return view('admin.manga.demography.edit', ['edit' => $edit]);
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
        $demography = MangaDemography::find($id);

        $demography->name = $request->name;
        $demography->description = $request->description;

        if($demography->save()){
            $response['success'] = [
                'msg' => "Demografia actualizada correctamente.",
                'data' => $demography
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $demography
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
        $delete = MangaDemography::destroy($id);
        if($delete){
            $response['msg'] = "Demografia eliminada correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
