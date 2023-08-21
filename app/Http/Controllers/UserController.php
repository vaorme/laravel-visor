<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = config('app.disk');
    }
    public function index(Request $request){
        $users = User::orderBy('created_at', 'desc');
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $users->where(function ($query) use ($param_search) {
                $query->where('username', 'LIKE', '%'.$param_search.'%');
            });
        }
        return view('admin.users.index', ['loop' => $users->paginate(15)]);
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
        return view('admin.users.create', [
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
				$avatarExtension = $request->file('avatar_file')->extension();
				$pathAvatar = $request->file('avatar_file')->storeAs('images/users', $request->username.'-avatar.'.$avatarExtension, $this->disk);
				$profile->avatar = 'images/users/'.$request->username.'-avatar.'.$avatarExtension;
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
        $user = User::join('profiles', 'users.id', '=', 'profiles.user_id')->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')->where('users.id', $id)->get(['users.*', 'profiles.*', 'model_has_roles.role_id'])->first();
		$countries = Countries::get();
        $avatares = Storage::disk($this->disk)->files('/avatares');
		$roles = Role::get();

        return view('admin.users.edit', [
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
		//return response()->json($request->all());
		$request->validate([
            'username' => ['string', 'regex:/^[_A-z0-9]*((-|\S)*[_A-z0-9])*$/','max:16'],
			'avatar_file' => ['dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif'],
            'email' => ['string', 'email', 'regex:/^.+@.+$/i','max:255']
		]);
        
        $user = User::find($id);
		
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
		
        // Assign Role
		if(isset($request->roles)){
			$role = Role::findByName($request->roles);
		}else{
			$role = Role::findByName('lector');
		}
        $user->assignRole($role);
		
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
                    $avatarExtension = $request->file('avatar_file')->extension();
                    $pathAvatar = $request->file('avatar_file')->storeAs('images/users', $request->username.'-avatar.'.$avatarExtension, $this->disk);
                    $profile->avatar = 'images/users/'.$request->username.'-avatar.'.$avatarExtension;
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
            $user->SendEmailVerificationNotification();
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
        $delete = User::destroy($id);
        if($delete){
            $response['msg'] = "Usuario eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }
        return $response;
    }
}