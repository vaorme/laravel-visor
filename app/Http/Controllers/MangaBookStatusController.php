<?php

namespace App\Http\Controllers;

use App\Models\MangaBookStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MangaBookStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = MangaBookStatus::get();
        $data = [
            'loop' => $loop,
        ];
        if(isset($request->id)){
            $edit = MangaBookStatus::find($request->id);
            $data['edit'] = $edit;
        }
        return view('admin.manga.status.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.manga.status.create');
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
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:manga_book_status',]
        ]);

        $store = new MangaBookStatus;

        $store->name = $request->name;
        $store->slug = $request->slug;

        if($store->save()){
            return redirect()->route('manga_book_status.index')->with('success', 'Estado creado correctamente');
        }
        return redirect()->route('manga_book_status.index')->with('error', 'Ups, se complico la cosa');
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
        $edit = MangaBookStatus::find($id);
        return view('admin.manga.status.edit', ['edit' => $edit]);
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

        $update = MangaBookStatus::find($id);

        $update->name = $request->name;
        if($update->slug != $request->slug){
            $slugExists = MangaBookStatus::where('slug', $request->slug)->exists();
            if($slugExists){
                return Redirect::back()->withErrors("Error: Slug ya existente");
            }
            $update->slug = $request->slug;
        }

        if($update->save()){
            return redirect()->route('manga_book_status.index')->with('success', 'Estado actualizado correctamente');
        }
        return redirect()->route('manga_book_status.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = MangaBookStatus::destroy($id);
        if($delete){
            $response['msg'] = "Status eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
