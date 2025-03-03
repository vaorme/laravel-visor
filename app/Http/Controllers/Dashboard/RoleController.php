<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Role::latest()->orderBy('id', 'desc');
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('name', 'LIKE', '%'.$param_search.'%');
            });
        }
		$loop = $loop->paginate(15);
        $viewData = [
            'loop' => $loop,
        ];
		
		if ($loop->lastPage() === 1 && $loop->currentPage() !== 1) {
            $queryParams = $request->query();
            $queryParams['page'] = 1;
            $redirectUrl = 'space/comics/types?' . http_build_query($queryParams);

            return Redirect::to($redirectUrl);
        }
        return view('dashboard.roles.index', $viewData);
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
            'guard_name' => ['required', 'max:24']
        ]);

        $store = new Role();

        $store->name = $request->name;
		$store->guard_name = $request->guard_name;

        if($store->save()){
            if(isset($request->permissions)){
				$store->givePermissionTo($request->permissions);
			}
			return [
				"status" => true,
				"message" => "Rol creado.",
				"item" => $store
			];
        }
		return [
			"status" => false,
			"message" => "Ups, error.",
			"item" => $store
		];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
		$show = Role::find($id);
        if($show){
            $show->permissions;
            return response()->json([
                'status' => true,
                'show' => $show,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'show' => $show
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit = Role::find($id);
        return view('dashboard.roles.edit', ['edit' => $edit]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        try {
            $request->validate([
                'name' => ['required', 'max:24'],
                'guard_name' => ['required', 'max:24']
            ]);
    
            $update = Role::find($id);
    
            $update->name = $request->name;
            $update->guard_name = $request->guard_name;
    
            if($update->save()){
                if(isset($request->permissions)){
                    $update->syncPermissions($request->permissions);
                }else{
                    $update->syncPermissions();
                }
                return response()->json([
                    'status' => true,
                    'message' => "Rol actualizado correctamente."
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => "Error al actualizar el rol."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Role::destroy($id);

		if(!$delete){
            return response()->json([
                'status' => false,
                'message' => "Ups, algo paso",
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => "Eliminado correctamente"
        ]);
    }

    public function getPermissions(){
        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            if (strpos($permission->name, '.') !== false) {
                $parts = explode('.', $permission->name);
                return $parts[0];
            } else {
                return $permission->name;
            }
        });
        return response()->json([
            'status' => true,
            'data' => $groupedPermissions,
            'message' => "Permisos listados correctamente"
        ]);
    }
}