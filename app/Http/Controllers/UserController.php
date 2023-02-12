<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = User::get();
        return view('admin.users.index', ['loop' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('admin.users.create');
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
            'email' => ['required', 'string', 'email', 'regex:/^.+@.+$/i','max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $userSaved = $user->save();

        // Create profile and assign role
        $role = Role::findByName('lector');
        $user->assignRole($role);

        $profile = new Profile;
        $profile->user_id = $user->id;
        $profile->avatar = 'storage/avatares/avatar-'.rand(1, 10).'.png';
        $profileSaved = $profile->save();

        if($userSaved && $profileSaved){
            $user->SendEmailVerificationNotification();
            $response['success'] = [
                'msg' => "Usuario creado correctamente.",
                'data' => $user
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'user' => $user,
                'profile' => $profile
            ];
        }

        return $response;
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
        //$user = User::join('profiles', 'users.id', '=', 'profiles.user_id')->where('users.id', $id)->get(['users.*', 'profiles.*'])->first();
        $user = User::find($id);
        return view('admin.users.edit', ['user' => $user]);
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
            'username' => ['required', 'string', 'regex:/^[_A-z0-9]*((-|\S)*[_A-z0-9])*$/','max:16'],
            'email' => ['required', 'string', 'email','regex:/^.+@.+$/i','max:255'],
            'current_password' => ['required', Rules\Password::defaults()],
            'password' => ['required', Rules\Password::defaults(), 'confirmed'],
        ]);

        $user = User::find($id);

        if(!Hash::check($request->current_password, $user->password)){
            return response()->json([
                'error' => 'Contraseña actual es incorrecta.'
            ], 404);
        }
        if($user->username != $request->username){
            $user->username = $request->username;
        }
        if($user->email != $request->email){
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->SendEmailVerificationNotification();
        }
        $user->password = Hash::make($request->password);

        if($user->save()){
            $response['success'] = [
                'msg' => "Usuario actualizado",
                'data' => $user
            ];
        }else{
            $response['error'] = [
                'msg' => "Ups, se complico la cosa",
                'data' => $user
            ];
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::destroy($id);
        if($delete){
            $response['msg'] = "Usuario eliminado correctamente.";
        }else{
            $response['msg'] = "Ups, algo salio mal socio.";
        }

        return $response;
    }
}