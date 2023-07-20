<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use App\Models\MangaHasCategory;
use App\Models\UserFollowManga;
use App\Models\UserHasFavorite;
use App\Models\UserViewChapter;
use App\Models\UserViewManga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MangaDetailController extends Controller{
    public function index(Request $request){
        $manga = Manga::where('manga.slug', '=',$request->slug)
        ->join('manga_demography', 'manga_demography.id', '=', 'manga.demography_id')
		->join('manga_type', 'manga_type.id', '=', 'manga.type_id')
        ->join('manga_book_status', 'manga_book_status.id', '=', 'manga.book_status_id')
        ->get([
            'manga.*',
			'manga_demography.name as manga_demography_name',
			'manga_demography.slug as manga_demography_slug',
			'manga_type.name as manga_type_name',
			'manga_type.slug as manga_type_slug',
            'manga_book_status.name as mb_status_name',
            'manga_book_status.slug as mb_status_slug'
        ]);
        if($manga->isEmpty()){
            return redirect()->route('web.index');
        }

        $viewedChapters = UserViewChapter::where('user_id', '=', Auth::id())
        ->join('chapters', 'chapters.id', '=', 'chapter_id')
        ->where('chapters.manga_id', '=', $manga->first()->id)->pluck('chapters.id')->toArray();

        $mangaFollowed = false;
        $existsFollowed = UserFollowManga::where('manga_id', '=', $manga->first()->id)->where('user_id', '=', Auth::id())->exists();
        if($existsFollowed){
            $mangaFollowed = true;
        }

        $mangaViewed = false;
        $existsViewed = UserViewManga::where('manga_id', '=', $manga->first()->id)->where('user_id', '=', Auth::id())->exists();
        if($existsViewed){
            $mangaViewed = true;
        }

        $mangaFavorite = false;
        $existsFavorite = UserHasFavorite::where('manga_id', '=', $manga->first()->id)->where('user_id', '=', Auth::id())->exists();
        if($existsFavorite){
            $mangaFavorite = true;
        }
        return view('manga_detail', [
            'manga' => $manga->first(),
            'viewedChapters' => $viewedChapters,
            'mangaFollowed' => $mangaFollowed,
            'mangaViewed' => $mangaViewed,
            'mangaFavorite' => $mangaFavorite
        ]);
    }
}
