<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Countries;
use App\Models\Order;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request){
        $username = $request->username;
        $user = User::firstWhere('users.username', '=', $username);
        if($user){
            if(!$user->profile){
                $user->syncRoles('lector');
                $profile = new Profile();
                $profile->user_id = $user['id'];
                $profile->public_profile = 1;
                $profile->avatar = 'avatares/avatar-'.rand(1, 10).'.jpg';
                $profile->save();
                return redirect()->route('profile.index', ['username' => $user->username]);
            }
            $page = $request->item;
            $viewData = [
                'user' => $user
            ];
            if($user->profile->public_profile || Auth::id() == $user->id){
                if(Auth::check()){
                    
                    $userLastChapters = collect($user->latestChapters());
                    $perPage = 16; 
                    $currentPage = request()->get('page', 1);
                    $paginatedChapters = new LengthAwarePaginator(
                        $userLastChapters->forPage($currentPage, $perPage),
                        $userLastChapters->count(),
                        $perPage,
                        $currentPage,
                        ['path' => route('profile.index', ['username' => $user->username])]
                    );
                    $viewData['latest'] = $paginatedChapters;
                }
                if(isset($page) && $page == "atajos" && !Auth::check()){
                    abort(404);
                }
                switch ($page) {
                    case 'siguiendo':
                        $viewData['manga'] = $user->followedMangas()->paginate(16);
                        break;
                    case 'favoritos':
                        $viewData['manga'] = $user->favoriteMangas()->paginate(16);
                        break;
                    case 'atajos':
                        $viewData['manga'] = $user->shortcutMangas()->paginate(16);
                        break;
                    default:
                        $viewData['manga'] = $user->followedMangas()->paginate(16);
                        break;
                }
                $viewData['page'] = $page;
            }
            
            return view('profile.index', $viewData);
        }else{
            return abort(404);
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
    public function shopping(){
        $orders = Order::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(20);
        $viewData = [
            'loop' => $orders
        ];
        return view('profile.account.shopping', $viewData);
    }
}
