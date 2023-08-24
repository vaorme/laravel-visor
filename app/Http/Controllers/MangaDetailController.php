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
use Illuminate\Support\Facades\Cache;

class MangaDetailController extends Controller{
    public function index(Request $request){
        $manga = Manga::firstWhere('manga.slug', '=',$request->slug);
        if(!$manga){
            return redirect()->route('web.index');
        }
        if($manga->status != "published"){
            return abort(404);
        }

        $durationInSeconds = 3600;
        $viewCount = Cache::remember('manga_view_count_'.$manga->id, $durationInSeconds, function () use ($manga) {
            return $manga->view_count;
        });
        
        // Incrementa cache si no encuentra o expiro
        Cache::put('manga_view_count_'.$manga->id, $viewCount + 1, $durationInSeconds);
        // Actualiza la base cada cierto tiempo (e.g., cada 10 visitas o cada minuto)
        if ($viewCount % 10 === 0) {
            $manga->increment('view_count', $viewCount - $manga->view_count);
        }

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
