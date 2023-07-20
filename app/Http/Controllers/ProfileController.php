<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Chapter;
use App\Models\Countries;
use App\Models\Manga;
use App\Models\User;
use App\Models\UserFollowManga;
use App\Models\UserHasFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request){
        $username = $request->username;
        $user = User::firstWhere('users.username', '=', $username);
        if($user->exists()){
            $profile = $user->profile;
            $page = $request->page;
            $viewData = [
                'user' => $user
            ];
            
            if(!isset($request->page) && Auth::check()){
                $viewData['manga'] = $user->followedMangas;
            }
            if(isset($page) && $page == "atajos" && !Auth::check()){
                abort(404);
            }
            switch ($page) {
                case 'siguiendo':
                    $viewData['manga'] = $user->followedMangas;
                    break;
                case 'favoritos':
                    $viewData['manga'] = $user->favoriteMangas;
                    break;
                case 'atajos':
                    $viewData['manga'] = $user->shortcutMangas;
                    break;
                default:
                    $viewData['manga'] = $user->followedMangas;
                    break;
            }
            $viewData['page'] = $page;
            
            return view('profile.index', $viewData);
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
    public function edit(Request $request){
        $countries = Countries::get();
        $avatares = Storage::disk('public')->files('/avatares');
        return view('profile.account.index', [
            'countries' => $countries,
            'avatares' => $avatares,
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
