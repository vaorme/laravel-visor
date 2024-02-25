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
        if(isset($param_search) && !empty($param_search)){
            $loop->where(function ($query) use ($param_search) {
                $query->where('username', 'LIKE', '%'.$param_search.'%');
            });
        }

		$loop = $loop->paginate(20);
		$viewData = [
            'loop' => $loop
        ];
		if ($loop->lastPage() === 1 && $loop->currentPage() !== 1) {
            $queryParams = $request->query();
            $queryParams['page'] = 1;
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
			'avatar_file' => ['dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif'],
            'email' => ['required', 'string', 'email', 'regex:/^.+@.+$/i','max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
		]);
		
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $userSaved = $user->save();

        // Asignamos monedas
        if(isset($request->coins) && !empty($request->coins)){
            $user->purchaseCoins($request->coins);
        }
        // Asignamos dias
        if(isset($request->days_without_ads) && !empty($request->days_without_ads)){
            $user->purchaseDays($request->days_without_ads);
        }

        // Assign Role
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
		if(isset($request->default_avatar) && !empty($request->default_avatar)){
			$profile->avatar = $request->default_avatar;
		}else{
			if(isset($request->avatar_file)){
                Storage::disk($this->disk)->deleteDirectory('images/users/'.$user->username);
                Storage::disk($this->disk)->makeDirectory('images/users/'.$user->username);
                $avatarExtension = $request->file('avatar_file')->extension();
                $pathAvatar = $request->file('avatar_file')->store('images/users/'.$request->username, $this->disk);
                $profile->avatar = $pathAvatar;
			}else{
				$profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
			}
		}
		if(isset($request->cover) && !empty($request->cover)){
			$profile->cover = $request->cover;
		}
		if(isset($request->public_profile)){
			$profile->public_profile = $request->public_profile;
		}
		if(isset($request->redes)){
			$encodeRedes = json_encode($request->redes);
			$profile->redes = $encodeRedes;
		}
        $profileSaved = $profile->save();

        if($userSaved && $profileSaved){
            $user->SendEmailVerificationNotification();
            $response['success'] = [
                'msg' => "Usuario creado correctamente.",
                // 'data' => $user,
				'status' => 200
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                // 'user' => $user,
                // 'profile' => $profile
            ];
        }

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente');
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
			'avatar_file' => ['dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif'],
            'email' => ['string', 'email', 'regex:/^.+@.+$/i','max:255']
		]);
        
        $user = User::find($id);
        $oldEmail = $user->email;
		
        if($user->username !== $request->username){
			if(User::where('username', $request->username)->exists()){
				return response()->json("Usuario existe");
			}
			$user->username = $request->username;
		}
        if($user->email !== $request->email){
			if(User::where('email', $request->email)->exists()){
				return response()->json("email existe");
			}
			$user->email = $request->email;
		}
		if(!empty($request->password)){
			$user->password = Hash::make($request->password);
		}
        $userSaved = $user->save();
        if($oldEmail !== $request->email){
			$user->SendEmailVerificationNotification();
		}

        $userCoins = $request->coins;

        if(!isset($userCoins)){
            $userCoins = 0;
        }
        if(isset($userCoins) && $userCoins != ""){
            if(isset($user->coins) && $user->coins->exists()){
                $user->assignCoins($userCoins);
            }else{
                $user->purchaseCoins($userCoins);
            }
        }

        $userDays = $request->days_without_ads;
        
        if(!isset($userDays)){
            $userDays = 0;
        }
        if(isset($userDays) && $userDays != ""){
            if(isset($user->daysNotAds) && $user->daysNotAds->exists()){
                $user->assignDays($userDays);
            }else{
                $user->purchaseDays($userDays);
            }
        }
		
        // Assign Role
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
		if(isset($request->default_avatar) && !empty($request->default_avatar)){
			$profile->avatar = $request->default_avatar;
		}else{
			if(isset($request->current_avatar) && !empty($request->current_avatar)){
                $profile->avatar = $request->current_avatar;
            }else{
                if(isset($request->avatar_file)){
                    Storage::disk($this->disk)->deleteDirectory('images/users/'.$user->username);
                    Storage::disk($this->disk)->makeDirectory('images/users/'.$user->username);
                    $avatarExtension = $request->file('avatar_file')->extension();
                    $pathAvatar = $request->file('avatar_file')->store('images/users/'.$request->username, $this->disk);
                    $profile->avatar = $pathAvatar;
                }else{
                    $profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
                }
            }
		}
		if(isset($request->cover) && !empty($request->cover)){
			$profile->cover = $request->cover;
		}
		if(isset($request->public_profile)){
			$profile->public_profile = $request->public_profile;
		}
		if(isset($request->redes)){
			$encodeRedes = json_encode($request->redes);
			$profile->redes = $encodeRedes;
		}
        $profileSaved = $profile->save();

        if($userSaved && $profileSaved){
            $response['success'] = [
                'msg' => "Usuario actualizado correctamente",
                // 'data' => $user,
				'status' => 200
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                // 'user' => $user,
                // 'profile' => $profile
            ];
        }

        //return $response;
		return redirect()->route('users.edit', ['id' => $id])->with('success', 'Usuario actualizado correctamente');
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
                    'msg' => "Eliminado correctamente"
                ], 200);
            }
        } catch (\Exception $e) {
            // Error response
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}