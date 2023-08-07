<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Role::get();
		$permissions = Permission::get();
		$data = [
            'loop' => $loop,
			'permissions' => $permissions
        ];
        if(isset($request->id)){
            $user = Auth::user();
            if($user->can('tags.edit')){
                $edit = Role::find($request->id);
                $data['edit'] = $edit;
                $data['currentPermissions'] = $edit->permissions;
            }else{
                return abort(404);
            }
        }
        return view('admin.roles.index', $data);
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
			if(isset($request->permisos)){
				$store->givePermissionTo($request->permisos);
			}
            return redirect()->route('roles.index')->with('success', 'Role creado correctamente');
        }
        return redirect()->route('roles.index')->with('error', 'Ups, se complico la cosa');
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

        $update = Role::find($id);

        if($update->name != $request->name){
            $update->name = $request->name;
        }

        if($update->save()){
			if(isset($request->permisos)){
				$update->syncPermissions($request->permisos);
			}else{
				$update->syncPermissions();
			}
            return redirect()->route('roles.index')->with('success', 'Role actualizado correctamente');
        }
        return redirect()->route('roles.index')->with('error', 'Ups, se complico la cosa');
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
