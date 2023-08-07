<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Permission::get();
        $data = [
            'loop' => $loop,
        ];
        if(isset($request->id)){
            $user = Auth::user();
            if($user->can('tags.edit')){
                $edit = Permission::find($request->id);
                $data['edit'] = $edit;
            }else{
                return abort(404);
            }
        }
        return view('admin.permissions.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['max:255', 'regex:/^\S[a-z0-9]+(?:\.+[a-z0-9]+)*$/', 'required']
        ]);
        
        $store = new Permission;
        $store->name = $request->name;

        if($store->save()){
            return redirect()->route('permissions.index')->with('success', 'Permiso creado correctamente');
        }
        return redirect()->route('permissions.index')->with('error', 'Ups, se complico la cosa');
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
        $edit = Permission::find($id);
        return view('admin.permissions.edit', ['edit' => $edit]);
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
            'name' => ['max:255', 'regex:/^\S[a-z0-9]+(?:\.+[a-z0-9]+)*$/', 'required']
        ]);
        
        $store = Permission::find($id);
        $store->name = $request->name;

        if($store->save()){
            return redirect()->route('permissions.index')->with('success', 'Permiso actualizado correctamente');
        }
        return redirect()->route('permissions.index')->with('error', 'Ups, se complico la cosa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $delete = Permission::destroy($id);
        if($delete){
            $response['msg'] = "Permiso eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
