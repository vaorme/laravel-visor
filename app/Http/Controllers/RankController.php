<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use Illuminate\Http\Request;

class RankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $loop = Rank::get();
        return view('admin.ranks.index', [
            'loop' => $loop
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.ranks.create');
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
            'icon' => 'nullable|image|mimes:jpeg,jpg,png,gif'
        ]);
        dd($request->icon);

        $store = new Rank;

        $store->name = $request->name;
        $icon = $request->icon;
        if($icon != null){
            $store->icon = $request->icon;
        }

        if($store->save()){
            $response['success'] = [
                'msg' => "Rango creado correctamente.",
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
        $edit = Rank::find($id);
        return view('admin.ranks.edit', [
            'edit' => $edit
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
            'name' => ['required', "max:50"],
            'icon' => ['nullable', 'image']
        ]);
        dd('ss');

        $store = Rank::find($id);

        $store->name = $request->name;
        $icon = $request->icon;
        if($icon != null){
            $store->icon = $request->icon;
        }

        if($store->save()){
            $response['success'] = [
                'msg' => "Rango creado correctamente.",
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
