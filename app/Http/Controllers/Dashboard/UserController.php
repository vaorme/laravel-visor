<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Countries;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserFollowManga;
use App\Models\UserHasFavorite;
use App\Models\UserHasRole;
use App\Models\UserViewChapter;
use App\Models\UserViewManga;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = config('app.disk');
    }
    public function index(Request $request){
        $loop = User::orderBy('created_at', 'desc');
        $param_search = strip_tags($request->s);
        $param_status = ($request->status)? strip_tags($request->status) : 'active';
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('username', 'LIKE', '%'.$param_search.'%');
            });
        }
        if(isset($param_status) && $param_status === "active"){
            $loop->whereNotNull('email_verified_at');
        }else{
            $loop->whereNull('email_verified_at');
        }
		$loop = $loop->paginate(20);
		$viewData = [
            'loop' => $loop
        ];
		if ($loop->lastPage() === 1 && $loop->currentPage() !== 1) {
            $queryParams = $request->query();
            $queryParams['page'] = 1;
            if(isset($param_status)) {
                $queryParams['status'] = $param_status;
            }
            $redirectUrl = 'space/users?' . http_build_query($queryParams);

            return Redirect::to($redirectUrl);
        }

        return view('dashboard.users.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $countries = Countries::get();
        $avatares = Storage::disk($this->disk)->files('/avatares');
		$roles = Role::get();
        return view('dashboard.users.form', [
            'countries' => $countries,
            'avatares' => $avatares,
			'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'username' => ['required', 'string', 'regex:/^[_A-z0-9]*((-|\S)*[_A-z0-9])*$/','max:16', 'unique:'.User::class],
			'avataravatar_file' => ['dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif'],
            'email' => ['required', 'string', 'email', 'regex:/^.+@.+$/i','max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);
		
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $userSaved = $user->save();

        // ? ASSIGN COINS
        // if(isset($request->coins) && !empty($request->coins)){
        //     $user->purchaseCoins($request->coins);
        // }
        // ? ASSIGN DAYS
        // if(isset($request->days_without_ads) && !empty($request->days_without_ads)){
        //     $user->purchaseDays($request->days_without_ads);
        // }

        // ? ASSIGN ROLE
		if(isset($request->roles)){
			$role = Role::findByName($request->roles);
		}else{
			$role = Role::findByName('lector');
		}
        $user->assignRole($role);

        $profile = new Profile;
		if(isset($request->country) && !empty($request->country)){
			$profile->country_id = $request->country;
		}
		$profile->name = $request->name;
		$profile->message = $request->message;
        $profile->user_id = $user->id;
		if(isset($request->avatar)){
			Storage::disk($this->disk)->deleteDirectory('images/users/'.$user->username);
			Storage::disk($this->disk)->makeDirectory('images/users/'.$user->username);
			$avatarExtension = $request->file('avatar')->extension();
			$pathAvatar = $request->file('avatar')->store('images/users/'.$request->username, $this->disk);
			$profile->avatar = $pathAvatar;
		}else{
			$profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
		}
		if(isset($request->cover_url) && !empty($request->cover_url)){
			$profile->cover = $request->cover_url;
		}
		if(isset($request->public_profile)){
			$profile->public_profile = 1;
		}else{
			$profile->public_profile = 0;
		}
		if(isset($request->social)){
			$encodeRedes = json_encode($request->social);
			$profile->redes = $encodeRedes;
		}
        $profileSaved = $profile->save();
        if($userSaved && $profileSaved){
            $user->SendEmailVerificationNotification();
            $response['success'] = [
                'message' => "Usuario creado correctamente.",
                // 'data' => $user,
				'status' => 200
            ];
        }else{
            $response['error'] = [
                'message' => "Ups, se complico la cosa",
                // 'user' => $user,
                // 'profile' => $profile
            ];
        }

		return redirect()->route('users.edit', ['id' => $user->id])->with('success', 'Usuario creado correctamente');
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
        $user = User::find($id);
		if(!$user){
			abort(404);
        }
        $profileExists = Profile::where('user_id', '=',$id);
        if(!$profileExists->exists()){
            $user->syncRoles('lector');
            $profile = new Profile();
            $profile->user_id = $user['id'];
            $profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
            $profile->save();
        }
		$countries = Countries::get();
        $avatares = Storage::disk($this->disk)->files('/avatares');
		$roles = Role::get();

        return view('dashboard.users.form', [
            'countries' => $countries,
            'avatares' => $avatares,
			'roles' => $roles,
			'user' => $user
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
		// return response()->json($request->all());
		$request->validate([
            'username' => ['string', 'regex:/^[_A-z0-9]*((-|\S)*[_A-z0-9])*$/','max:16'],
			'avatar' => ['dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif'],
            'email' => ['string', 'email', 'regex:/^.+@.+$/i','max:255']
		]);
        
        $user = User::find($id);
        $oldEmail = $user->email;
		
        if($user->username !== $request->username){
			if(User::where('username', $request->username)->exists()){
				return response()->json("El nombre de usuario ya existe.");
			}
			$user->username = $request->username;
		}
        if($user->email !== $request->email){
			if(User::where('email', $request->email)->exists()){
				return response()->json("El correo ya existe.");
			}
			$user->email = $request->email;
		}
        $userSaved = $user->save();
        if($oldEmail !== $request->email){
			$user->SendEmailVerificationNotification();
		}
		
        // ? ASSIGN ROLE
		if(isset($request->roles) && !empty($request->roles)){
			$role = Role::findByName($request->roles);
		}else{
			$role = Role::findByName('lector');
		}
        $user->syncRoles($role);
		
        $profile = Profile::find($id);
		$profile->name = $request->name;
		$profile->message = $request->message;
		if(isset($request->country) && !empty($request->country)){
			$profile->country_id = $request->country;
		}

		if(!isset($request->avatar) && (isset($request->current_avatar) && !empty($request->current_avatar))){
			$profile->avatar = $request->current_avatar;
		}else{
			if(isset($request->avatar)){
				Storage::disk($this->disk)->deleteDirectory('images/users/'.$user->username);
				Storage::disk($this->disk)->makeDirectory('images/users/'.$user->username);
				$avatarExtension = $request->file('avatar')->extension();
				$pathAvatar = $request->file('avatar')->store('images/users/'.$request->username, $this->disk);
				$profile->avatar = $pathAvatar;
			}else{
				$profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
			}
		}
		if(isset($request->cover_url) && !empty($request->cover_url)){
			$profile->cover = $request->cover_url;
		}
		if(isset($request->public_profile)){
			$profile->public_profile = 1;
		}else{
			$profile->public_profile = 0;
		}
		if(isset($request->social)){
			$encodeRedes = json_encode($request->social);
			$profile->redes = $encodeRedes;
		}
        $profileSaved = $profile->save();

        if($userSaved && $profileSaved){
            $response['success'] = [
                'message' => "Usuario actualizado correctamente",
                // 'data' => $user,
				'status' => 200
            ];
        }else{
            $response['error'] = [
                'message' => "Ups, se complico la cosa",
                // 'user' => $user,
                // 'profile' => $profile
            ];
        }

        //return $response;
		return redirect()->route('users.edit', ['id' => $id])->with('success', 'Usuario actualizado correctamente');
    }

	public function changePassword(Request $request){
		$request->validate([
			'id' => ['required', 'integer', 'exists:users,id'],
			'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);
		$user = User::find($request->id);
		$user->password = Hash::make($request->password);
		if($user->save()){
			return response()->json([
				'status' => true,
				'message' => "Contraseña actualizada correctamente."
			]);
		}
		return response()->json([
			'status' => true,
			'message' => "Ha ocurrido un error."
		]);
	}
	public function activateAccount(Request $request){
		$request->validate([
			'id' => ['required', 'integer', 'exists:users,id']
		]);

		$user = User::find($request->id);
		$user->email_verified_at = Carbon::now();
		if($user->save()){
			return response()->json([
				'status' => true,
				'message' => "Cuenta activada correctamente."
			]);
		}
		return response()->json([
			'status' => true,
			'message' => "Ha ocurrido un error."
		]);
	}

	public function deactivateAccount(Request $request){
        $request->validate([
            'id' => ['required', 'integer', 'exists:users,id']
        ]);

        $user = User::find($request->id);
        $user->email_verified_at = null;
        if($user->save()){
            return response()->json([
                'status' => true,
                'message' => "Cuenta desactivada correctamente."
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Ha ocurrido un error."
        ]);
    }
    public function getCoins($id){
        if(!isset($id)){
            return response()->json([
                'status' => false,
                'message' => "Ha ocurrido un error."
            ]);    
        }

        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => true,
                'data' => $user->coins,
                'message' => "Monedas obtenidas."
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Ha ocurrido un error."
        ]);
    }
    public function assignCoins(Request $request,$id){
        if(!isset($id)){
            return response()->json([
                'status' => false,
                'message' => "Ha ocurrido un error."
            ]);    
        }

        $user = User::find($id);
        if($user){
            if($user->assignCoins($request->amount)){
                return response()->json([
                    'status' => true,
                    'message' => "Monedas asignadas correctamente."
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => "Ups, algo salio mal."
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Ha ocurrido un error."
        ]);
    }

    public function assignDays(Request $request,$id){
        if(!isset($id)){
            return response()->json([
                'status' => false,
                'message' => "Ha ocurrido un error."
            ]);    
        }

        $user = User::find($id);
        if($user){
            if($user->assignDays($request->amount)){
                return response()->json([
                    'status' => true,
                    'message' => "Días asignados correctamente."
                ]);
            }
            return response()->json([
                'status' => false,
                'message' => "Ups, algo salio mal."
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Ha ocurrido un error."
        ]);
    }
    public function getDays($id){
        if(!isset($id)){
            return response()->json([
                'status' => false,
                'message' => "Ha ocurrido un error."
            ]);    
        }

        $user = User::find($id);
        if($user){
            return response()->json([
                'status' => true,
                'data' => $user->daysNotAds,
                'message' => "Días obtenidas."
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => "Ha ocurrido un error."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
		try {
			$delete = User::destroy($id);
            if($delete){
                return response()->json([
                    'status' => true,
                    'message' => "Eliminado correctamente"
                ], 200);
            }
        } catch (\Exception $e) {
            // Error response
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}