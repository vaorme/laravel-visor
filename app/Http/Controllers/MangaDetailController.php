<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use App\Models\MangaHasCategory;
use App\Models\UserFollowManga;
use App\Models\UserHasFavorite;
use App\Models\UserViewChapter;
use App\Models\UserViewManga;
use App\Models\ViewCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MangaDetailController extends Controller{
    public function index(Request $request){
        $manga = Manga::firstWhere('manga.slug', '=',$request->slug);
        if(!$manga->exists()){
            return redirect()->route('web.index');
        }
        $count = new ViewCount;
        $manga->viewCount()->save($count);

        $viewedChapters = UserViewChapter::where('user_id', '=', Auth::id())
        ->join('chapters', 'chapters.id', '=', 'chapter_id')
        ->where('chapters.manga_id', '=', $manga->id)->pluck('chapters.id')->toArray();

        $mangaFollowed = false;
        $existsFollowed = UserFollowManga::where('manga_id', '=', $manga->id)->where('user_id', '=', Auth::id())->exists();
        if($existsFollowed){
            $mangaFollowed = true;
        }

        $mangaViewed = false;
        $existsViewed = UserViewManga::where('manga_id', '=', $manga->id)->where('user_id', '=', Auth::id())->exists();
        if($existsViewed){
            $mangaViewed = true;
        }

        $mangaFavorite = false;
        $existsFavorite = UserHasFavorite::where('manga_id', '=', $manga->id)->where('user_id', '=', Auth::id())->exists();
        if($existsFavorite){
            $mangaFavorite = true;
        }
        return view('manga_detail', [
            'manga' => $manga,
            'viewedChapters' => $viewedChapters,
            'mangaFollowed' => $mangaFollowed,
            'mangaViewed' => $mangaViewed,
            'mangaFavorite' => $mangaFavorite
        ]);
    }
}
