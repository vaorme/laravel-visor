<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProductTypeController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = ProductType::latest()->orderBy('id', 'desc');
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
        return view('dashboard.comics.type.index', $viewData);
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
            'name' => ['required', 'max:24']
        ]);

        $store = new ProductType;

        $store->name = $request->name;

        if($store->save()){
			return [
				"status" => true,
				"message" => "Tipo creado.",
				"item" => $store
			];
        }
		return [
			"status" => true,
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
		$show = ProductType::where('id', $id)->get()->first();
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
        $edit = ProductType::find($id);
        return view('admin.manga.type.edit', ['edit' => $edit]);
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

        $update = ProductType::find($id);

        $update->name = $request->name;

        if($update->save()){
			return response()->json([
				'status' => true,
				'message' => "Tipo actualizado correctamente"
			]);
        }
		return response()->json([
			'status' => false,
			'message' => "Ups, se complico la cosa"
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
        $delete = ProductType::destroy($id);

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
}