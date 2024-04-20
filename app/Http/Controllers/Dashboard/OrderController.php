<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $loop = Order::latest()->orderBy('id', 'desc');
        $param_search = strip_tags($request->s);
        $param_status = ($request->status)? strip_tags($request->status) : '';
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('name', 'LIKE', '%'.$param_search.'%')
                ->orWhere('order_id', 'LIKE', '%'.$param_search.'%')
                ->orWhere('transaction_id', 'LIKE', '%'.$param_search.'%');
            });
        }
        if(!empty($param_status)){
            $param_status = strtoupper($param_status);
            $loop->where('status', $param_status);
        }
		$loop = $loop->paginate(15);
        $viewData = [
            'loop' => $loop,
        ];

		if ($loop->lastPage() === 1 && $loop->currentPage() !== 1) {
            $queryParams = $request->query();
            $queryParams['page'] = 1;
            $redirectUrl = 'space/orders?' . http_build_query($queryParams);

            return Redirect::to($redirectUrl);
        }
        return view('dashboard.orders.index', $viewData);
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
                'message' => $validator->errors()->all()
            ]);
        }

        $store = new Order();

        $store->name = $request->name;
        $store->slug = $request->slug;

        if($store->save()){
			return [
				"status" => true,
				"message" => "Estado creado.",
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
		$show = Order::where('orders.id', $id)
        ->leftJoin('users', 'orders.user_id', '=', 'users.id')
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
        ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
        ->select(
            "orders.*",
            "users.username",
            "profiles.avatar as user_avatar",
            "products.id as product_id",
            "products.name as product_name",
            "products.coins as product_coins",
            "products.days_without_ads as product_days",
            "products.price as product_price",
        )->get()->first();
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
        $edit = Order::find($id);
        return view('dashboard.orders.edit', ['edit' => $edit]);
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
                'message' => $validator->errors()->all()
            ]);
        }

        $update = Order::find($id);

        $update->name = $request->name;
        if($update->slug != $request->slug){
            $slugExists = Order::where('slug', $request->slug)->exists();
            if($slugExists){
				return response()->json([
					'status' => false,
					'message' => "Slug $update->slug ya existe."
				]);
            }
            $update->slug = $request->slug;
        }

        if($update->save()){
			return response()->json([
				'status' => true,
				'message' => "Estado actualizado correctamente"
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
        $delete = Order::destroy($id);

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