<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserFollowManga;
use App\Models\UserHasFavorite;
use App\Models\UserRateManga;
use App\Models\UserViewChapter;
use App\Models\UserViewManga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WebUserController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = config('app.disk');
    }

    public function update(Request $request, $id){
        // return response()->json($request->all());
        if($id != Auth::id()){
            return redirect()->route('account.index')->with('error', 'Hey, no puedes hacer eso.');
        }
        //return response()->json($request->all());
		$request->validate([
			'avatar_file' => ['dimensions:max_width=248,max_height=248', 'max:400', 'mimes:jpg,jpeg,png,gif'],
            'email' => ['string', 'email', 'regex:/^.+@.+$/i','max:255']
		]);
        
        $user = User::find($id);
		
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
                    $pathAvatar = $request->file('avatar_file')->storeAs('images/users/'.$user->username,'avatar.'.$avatarExtension, $this->disk);
                    $profile->avatar = 'images/users/'.$user->username.'/avatar.'.$avatarExtension;
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

        if($profileSaved){
            return redirect()->route('account.index')->with('success', 'Perfil actualizado correctamente');
        }
        return redirect()->route('account.index')->with('error', 'Ups, se complico la cosa');
    }

    // :UN/FOLLOW
    public function followManga(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Manga,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $exists = UserFollowManga::where('manga_id', '=', $id)->where('user_id', '=', $user_id)->exists();

        if($exists){
            return response()->json([
                'status' => "error",
                'message' => "Ya lo sigues"
            ]);
        }

        $follow = new UserFollowManga;
        $follow->manga_id = $id;
        $follow->user_id = $user_id;
        
        if($follow->save()){
            return response()->json([
                'status' => "success",
                'message' => "Siguiendo"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    public function unfollowManga(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Manga,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $notExists = UserFollowManga::where('manga_id', '=', $id)->where('user_id', '=', $user_id)->doesntExist();

        if($notExists){
            return response()->json([
                'status' => "error",
                'message' => "No lo sigues"
            ]);
        }

        $follow = UserFollowManga::where('manga_id', '=', $id)->where('user_id', '=', $user_id);
        if($follow->delete()){
            return response()->json([
                'status' => "success",
                'message' => "Lo dejaste de seguir"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    // :VIEW / VIEWED

    public function viewManga(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Manga,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $exists = UserViewManga::where('manga_id', '=', $id)->where('user_id', '=', $user_id)->exists();

        if($exists){
            return response()->json([
                'status' => "error",
                'message' => "Ya lo viste"
            ]);
        }

        $follow = new UserViewManga;
        $follow->manga_id = $id;
        $follow->user_id = $user_id;
        
        if($follow->save()){
            return response()->json([
                'status' => "success",
                'message' => "Viendo"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    public function unviewManga(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Manga,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $notExists = UserViewManga::where('manga_id', '=', $id)->where('user_id', '=', $user_id)->doesntExist();

        if($notExists){
            return response()->json([
                'status' => "error",
                'message' => "No lo ves"
            ]);
        }

        $follow = UserViewManga::where('manga_id', '=', $id)->where('user_id', '=', $user_id);
        if($follow->delete()){
            return response()->json([
                'status' => "success",
                'message' => "Lo dejaste de ver"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    // :FAV / UNFAV

    public function favManga(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Manga,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $exists = UserHasFavorite::where('manga_id', '=', $id)->where('user_id', '=', $user_id)->exists();

        if($exists){
            return response()->json([
                'status' => "error",
                'message' => "Ya es favorito"
            ]);
        }

        $follow = new UserHasFavorite;
        $follow->manga_id = $id;
        $follow->user_id = $user_id;
        
        if($follow->save()){
            return response()->json([
                'status' => "success",
                'message' => "Agregado a favoritos"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    public function unfavManga(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Manga,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $notExists = UserHasFavorite::where('manga_id', '=', $id)->where('user_id', '=', $user_id)->doesntExist();

        if($notExists){
            return response()->json([
                'status' => "error",
                'message' => "No es favorito"
            ]);
        }

        $follow = UserHasFavorite::where('manga_id', '=', $id)->where('user_id', '=', $user_id);
        if($follow->delete()){
            return response()->json([
                'status' => "success",
                'message' => "Ya no es favorito"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    // :VIEW / VIEWED CHAPTER

    public function viewChapter(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Chapter,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $exists = UserViewChapter::where('chapter_id', '=', $id)->where('user_id', '=', $user_id)->exists();

        if($exists){
            return response()->json([
                'status' => "error",
                'message' => "Ya lo viste"
            ]);
        }

        $follow = new UserViewChapter;
        $follow->chapter_id = $id;
        $follow->user_id = $user_id;
        
        if($follow->save()){
            return response()->json([
                'status' => "success",
                'message' => "Viendo"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }

    public function unviewChapter(Request $request, $id){
        $validator = Validator::make($request->params, [
            'id' => ['required', 'numeric', 'exists:App\Models\Chapter,id']
        ],[
			'id.required' => 'ID requerido',
			'id.numeric' => 'El ID debe ser número',
            'id.exists' => 'ID no existe'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        $user_id = Auth::id();

        $notExists = UserViewChapter::where('chapter_id', '=', $id)->where('user_id', '=', $user_id)->doesntExist();

        if($notExists){
            return response()->json([
                'status' => "error",
                'message' => "No lo ves"
            ]);
        }

        $follow = UserViewChapter::where('chapter_id', '=', $id)->where('user_id', '=', $user_id);
        if($follow->delete()){
            return response()->json([
                'status' => "success",
                'message' => "Lo dejaste de ver"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }
    public function rateManga(Request $request, $mangaid){

        $validator = Validator::make($request->params, [
            'rating' => ['required', 'numeric']
        ],[
			'id.required' => 'Valor requerido',
			'id.numeric' => 'El valor debe ser númerico'
		]);
		if ($validator->fails()) {
            return response()->json([
                'status' => "error",
                'message' => $validator->errors()->all()
            ]);
        }
        if(!Auth::check()){
            return response()->json([
                'status' => "error",
                'message' => "Solo para usuarios registrados"
            ]);
        }
        $user = Auth::user();
        if(!$user->verifiedEmail()){
            return response()->json([
                'status' => "error",
                'message' => "Debes verificar tu correo"
            ]);
        }

        $rating = UserRateManga::where('user_id', '=', $user->id)->where('manga_id', '=', $mangaid);
        if($rating->exists()){
            return response()->json([
                'status' => "error",
                'message' => "Ya lo calificaste"
            ]);
        }

        $rate = new UserRateManga;

        $rate->user_id = $user->id;
        $rate->manga_id = $mangaid;
        $rate->rating = number_format($request->params['rating']);

        if($rate->save()){
            Cache::forget('home_slider');
            Cache::forget('most_viewed');
            Cache::forget('manga_shortcuts');
            Cache::forget('categories_home');
            Cache::forget('top_month');
            return response()->json([
                'status' => "success",
                'message' => "Calificado"
            ]);
        }

        return response()->json([
            'status' => "error",
            'message' => "Ups, algo paso",
        ]);
    }
}