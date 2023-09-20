<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\Slider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
		if (Cache::has('new_chapters')) {
			$newLimit = Cache::get('new_chapters');
		} else {
			$newChapters = Manga::where('status', '=', 'published')
				->has('latestChapters') // Only include manga with at least one latest chapter
				->with(['latestChapters' => function ($query) {
					$query->latest('created_at')->orderBy('name', 'DESC')->limit(2); // Order and limit the latestChapters relationship
				}])
				->get();

			$newChapters = $newChapters->sortByDesc(function ($manga) {
				return optional($manga->latestChapters->first())->created_at; // Get the creation date of the latest chapter
			})->values(); // Reset the array keys

			$newLimit = $newChapters->take(20);
			Cache::put('new_chapters', $newLimit, Carbon::now()->endOfWeek());
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
			'newChapters' => $newLimit,
			'topmonth' => $topMonthly,
			'mostViewed' => $mostViewed,
			'slider' => $slider
		];

        return view('home', $viewData);
    }
}