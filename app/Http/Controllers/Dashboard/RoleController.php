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
        return response()->json($request->all());
        $request->validate([
            'name' => ['required', 'max:24'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:manga_type']
        ]);

        $store = new Role();

        $store->name = $request->name;
        $store->slug = $request->slug;
		$store->description = $request->description;

        if($store->save()){
			return [
				"status" => true,
				"msg" => "Tipo creado.",
				"item" => $store
			];
        }
		return [
			"status" => true,
			"msg" => "Ups, error.",
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
        $request->validate([
            'name' => ['required', 'max:24'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ]);

        $update = Role::find($id);

        $update->name = $request->name;
        if($update->slug != $request->slug){
            $slugExists = Role::where('slug', $request->slug)->exists();
            if($slugExists){
				return response()->json([
					'status' => false,
					'msg' => "Slug $update->slug ya existe."
				]);
            }
            $update->slug = $request->slug;
        }
        $update->description = $request->description;

        if($update->save()){
			return response()->json([
				'status' => true,
				'msg' => "Tipo actualizado correctamente"
			]);
        }
		return response()->json([
			'status' => false,
			'msg' => "Ups, se complico la cosa"
		]);
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
                'msg' => "Ups, algo paso",
            ]);
        }
        return response()->json([
            'status' => true,
            'msg' => "Eliminado correctamente"
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
            'msg' => "Permisos listados correctamente"
        ]);
    }
}