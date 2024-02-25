<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MangaBookStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ComicStatusController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = MangaBookStatus::latest()->orderBy('id', 'desc');
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
            $redirectUrl = 'space/comics/status?' . http_build_query($queryParams);

            return Redirect::to($redirectUrl);
        }
        return view('dashboard.comics.status.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
		$rules = [
            'name' => ['required', 'max:24', 'unique:manga_book_status'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:manga_book_status']
        ];
		$messages = [
			'name.unique' => 'El nombre ya existe.',
			'slug.unique' => 'El slug ya existe.'
		];
		$validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->all()
            ]);
        }

        $store = new MangaBookStatus();

        $store->name = $request->name;
        $store->slug = $request->slug;

        if($store->save()){
			return [
				"status" => true,
				"msg" => "Estado creado.",
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
		$show = MangaBookStatus::where('id', $id)->get()->first();
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
    public function edit($id){
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
        $rules = [
            'name' => ['required', 'max:24'],
            'slug' => ['required', 'max:50', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/']
        ];
		$validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'msg' => $validator->errors()->all()
            ]);
        }

        $update = MangaBookStatus::find($id);

        $update->name = $request->name;
        if($update->slug != $request->slug){
            $slugExists = MangaBookStatus::where('slug', $request->slug)->exists();
            if($slugExists){
				return response()->json([
					'status' => false,
					'msg' => "Slug $update->slug ya existe."
				]);
            }
            $update->slug = $request->slug;
        }

        if($update->save()){
			return response()->json([
				'status' => true,
				'msg' => "Estado actualizado correctamente"
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
        $delete = MangaBookStatus::destroy($id);

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