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
		if (Cache::has('most_viewed')) {
			$mostViewed = Cache::get('most_viewed');
		} else {
			$mostViewed = Manga::where('view_count', '>', 0)->orderBy('view_count', 'desc')->take(10)->get();
			Cache::put('most_viewed', $mostViewed, Carbon::now()->endOfWeek());
		}
		// $categories = Category::withCount('mangas')->orderByDesc('mangas_count')->take(8)->get();
		if (Cache::has('new_chapters')) {
			$newChapters = Cache::get('new_chapters');
		} else {
			$newChapters = Manga::take(16)->where('status', '=', 'published')->has('latestChapters')->with('latestChapters')->get();
			Cache::put('new_chapters', $newChapters, Carbon::now()->endOfWeek());
		}

		if (Cache::has('top_month')) {
			$topMonthly = Cache::get('top_month');
		} else {
			$topMonthly = Manga::where('status', '=', 'published')->select(['id', 'slug', 'name', 'featured_image'])->withAvg('monthRating', 'rating')->has('monthRating')->orderBy('month_rating_avg_rating', 'DESC')->limit(10)->get();
			Cache::put('top_month', $topMonthly, Carbon::now()->endOfWeek());
		}
		if (Cache::has('home_slider')) {
			$slider = Cache::get('home_slider');
		} else {
			$slider = Slider::get();
			Cache::put('home_slider', $slider, Carbon::now()->endOfMonth());
		}

		$viewData = [
			'newChapters' => $newChapters,
			'topmonth' => $topMonthly,
			'mostViewed' => $mostViewed,
			'slider' => $slider
		];

        return view('home', $viewData);
    }
}