<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Benchmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index($username){
        $user = User::join('profiles', 'profiles.user_id', '=', 'users.id')->where('users.username', '=', $username);
        if($user->exists()){
            // Benchmark::dd(fn () => $user);
            return view('profile.index', ['user' => $user->get()->toArray()]);
        }else{
            return view('profile.index', [
                'error' => true,
                'msg' => "Usuario $username no existe"
            ]);
        }
    }
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('profile.account.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('account.index')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

	public function validateAvatar(Request $request){
		$validator = Validator::make($request->all(), [
            'avatar' => ['required','dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif']
        ],[
			'avatar.mimes' => 'Formato invalido | Permitidos: jpg,jpeg,png,gif',
			'avatar.dimensions' => 'las dimensiones máximas de la imagen son 248x248px',
			'avatar.max' => 'El tamaño maxo es de 400kb'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'msg' => $validator->errors()->all()
            ]);
        }

		return true;
	}
}
