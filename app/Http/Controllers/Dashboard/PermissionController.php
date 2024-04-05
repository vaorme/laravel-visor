<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Permission::latest()->orderBy('id', 'desc');
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
            $redirectUrl = 'space/comics/permissions?' . http_build_query($queryParams);

            return Redirect::to($redirectUrl);
        }
        return view('dashboard.permissions.index', $viewData);
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

        $store = new Permission;

        $store->name = $request->name;
        $store->guard_name = $request->guard_name;

        if($store->save()){
			return [
				"status" => true,
				"msg" => "Permiso creado.",
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
		$show = Permission::where('id', $id)->get()->first();
        if($show){
            return response()->json([
                'status' => true,
                'show' => $show
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
        $edit = Permission::find($id);
        return view('dashboard.permissions.edit', ['edit' => $edit]);
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
            'guard_name' => ['required', 'max:24']
        ]);

        $update = Permission::find($id);

        $update->name = $request->name;
        $update->guard_name = $request->guard_name;

        if($update->save()){
			return response()->json([
				'status' => true,
				'msg' => "Permiso actualizado."
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
        $delete = Permission::destroy($id);

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
}
