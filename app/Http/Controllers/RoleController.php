<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $roles = Role::get();
        return view('admin.roles.index', ['loop' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'name' => ['max:255', 'regex:/^\S[a-z0-9]+(?:[a-z0-9]+)*$/', 'required']
        ]);
        
        $store = new Role;
        $store->name = $request->name;

        if($store->save()){
            $response['success'] = [
                'msg' => "Role creado correctamente.",
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
        $edit = Role::find($id);
        $permissions = Permission::get();
        return view('admin.roles.edit', [
            'edit' => $edit,
            'permissions' => $permissions
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
            'name' => ['max:255', 'regex:/^\S[a-z0-9]+(?:[a-z0-9]+)*$/', 'required']
        ]);

        $permissions = $request->input('permisos');

        $update = Role::find($id);
        if($update->name != $request->name){
            $update->name == $request->name;
        }
        $update->syncPermissions($permissions);

        if($update->save()){
            $response['success'] = [
                'msg' => "Role actualizado.",
                'data' => $update
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $update
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
        $delete = Role::destroy($id);
        if($delete){
            $response['msg'] = "Role eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}
