<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller{
    public function index(){
		
		$oneWeek = date('Y-m-d', strtotime("-6 days"));
		$oneMonth = date('Y-m-d', strtotime("-1 month"));
		// $newManga = Manga::where('manga.created_at', '>=', $oneMonth)->limit(12)->get();

		// if (Cache::has('most_viewed')) {
		// 	$mostViewed = Cache::get('most_viewed');
		// } else {
		// 	$mostViewed = Manga::where('status', '=', 'published')->with(['rating', 'viewsMonth'])->has('viewsMonth')->limit(16)->get()->sortByDesc('viewsMonth');
		// 	Cache::put('most_viewed', $mostViewed, Carbon::now()->endOfWeek());
		// }
		$mostViewed = Manga::where('status', '=', 'published')->with(['rating', 'viewsMonth'])->has('viewsMonth')->limit(16)->get()->sortByDesc('viewsMonth');
		// if (Cache::has('new_chapters_manga')) {
		// 	$newChapterManga = Cache::get('new_chapters_manga');
		// } else {
		// 	$newChapterManga = Chapter::where('created_at', '>=', $oneWeek)->orderBy('id', 'desc')->withWhereHas('manga.type', function($query) {
		// 		$query->where('slug', '!=','novela');
		// 	})->get()->unique('manga_id');
		// 	Cache::put('new_chapters_manga', $newChapterManga->slice(0, 15), Carbon::now()->endOfWeek());
		// }
		$newChapterManga = Chapter::where('created_at', '>=', $oneWeek)->orderBy('id', 'desc')->withWhereHas('manga.type', function($query) {
			$query->where('slug', '!=','novela');
		})->get()->unique('manga_id');

		// if (Cache::has('new_chapters_novel')) {
		// 	$newChapterNovel = Cache::get('new_chapters_novel');
		// } else {
		// 	$newChapterNovel = Chapter::where('created_at', '>=', $oneWeek)->orderBy('id', 'desc')->withWhereHas('manga.type', function($query) {
		// 		$query->where('slug', '=','novela');
		// 	})->get()->unique('manga_id');
		// 	Cache::put('new_chapters_novel', $newChapterNovel->slice(0, 15), Carbon::now()->endOfWeek());
		// }
		$newChapterNovel = Chapter::where('created_at', '>=', $oneWeek)->orderBy('id', 'desc')->withWhereHas('manga.type', function($query) {
			$query->where('slug', '=','novela');
		})->get()->unique('manga_id');

		// if (Cache::has('top_month')) {
		// 	$topMonthly = Cache::get('top_month');
		// } else {
		// 	$topMonthly = Manga::where('status', '=', 'published')->select(['id', 'slug', 'name', 'featured_image'])->withAvg('monthRating', 'rating')->has('monthRating')->orderBy('month_rating_avg_rating', 'DESC')->limit(10)->get();
		// 	Cache::put('top_month', $topMonthly, Carbon::now()->endOfWeek());
		// }
		$topMonthly = Manga::where('status', '=', 'published')->select(['id', 'slug', 'name', 'featured_image'])->withAvg('monthRating', 'rating')->has('monthRating')->orderBy('month_rating_avg_rating', 'DESC')->limit(10)->get();
		// if (Cache::has('home_slider')) {
		// 	$slider = Cache::get('home_slider');
		// } else {
		// 	$slider = Slider::get();
		// 	Cache::put('home_slider', $slider, Carbon::now()->endOfMonth());
		// }
		$slider = Slider::get();

		// if (Cache::has('categories_home')) {
		// 	$categories = Cache::get('categories_home');
		// } else {
		// 	$categories = Category::has('mangas')->inRandomOrder()->limit(4)->get();
		// 	Cache::put('categories_home', $categories, Carbon::now()->endOfMonth());
		// }
		$categories = Category::has('mangas')->inRandomOrder()->limit(4)->get();

		$viewData = [
			'categories' => $categories,
			'mostViewed' => $mostViewed,
			'newChapterManga' => $newChapterManga,
			'newChapterNovel' => $newChapterNovel,
			'topmonth' => $topMonthly,
			'slider' => $slider
		];

        return view('home', $viewData);
    }
}