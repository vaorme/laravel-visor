<?php

namespace App\Http\Controllers;

use App\Models\Shortcut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShortcutsController extends Controller{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'manga_id' => ['required', 'regex:/^[0-9]+$/']
        ],[
			'manga_id.required' => 'Manga requerido'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'msg' => $validator->errors()->all()
            ]);
        }

        $id = Auth::id();

        $exists = Shortcut::where('manga_id', '=', $request->manga_id)->where('user_id', '=', $id)->exists();
        if($exists){
            return response()->json([
                'status' => "error",
                'msg' => "El atajo ya existe"
            ]);
        }
        $count = Shortcut::where('user_id', '=', $id)->count();
        if($count >= 16){
            return response()->json([
                'status' => "error",
                'msg' => "Limite de atajos alcanzado"
            ]);
        }

        $store = new Shortcut;

        $store->user_id = $id;
        $store->manga_id = $request->manga_id;

        if($store->save()){
            return response()->json([
                'status' => "success",
                'msg' => "Atajo creado correctamente"
            ]);
        }

        return response()->json([
            'status' => "error",
            'msg' => "Ups, algo paso."
        ]);
    }

    public function show($id){
        
    }

    public function edit($id){
        
    }

    public function update(Request $request, $id){

        // if($update->save()){
		// 	if(isset($request->permisos)){
		// 		$update->syncPermissions($request->permisos);
		// 	}else{
		// 		$update->syncPermissions();
		// 	}
        //     return redirect()->route('roles.index')->with('success', 'Role actualizado correctamente');
        // }
        // return redirect()->route('roles.index')->with('error', 'Ups, se complico la cosa');
    }

    public function destroy(Request $request){
        if(!isset($request->user_id) && !isset($request->manga_id)){
            return response()->json([
                'status' => "error",
                'message' => "ID Requerido"
            ]);
        }
        if($request->user_id != Auth::id()){
            return response()->json([
                'status' => "error",
                'message' => "No puedes hacer eso."
            ]);
        }
        $delete = Shortcut::where('user_id', '=', $request->user_id)->where('manga_id', '=', $request->manga_id);
        if($delete->delete()){
            return response()->json([
                'status' => "success",
                'message' => "Atajo eliminado correctamente"
            ]);
        }else{
            return response()->json([
                'status' => "error",
                'message' => "Ups, algo salio mal socio."
            ]);
        }
    }
}
