<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller{
    public function index(){

		$oneWeek = date('Y-m-d', strtotime("-6 days"));
		$oneMonth = date('Y-m-d', strtotime("-1 month"));
		// $newManga = Manga::where('manga.created_at', '>=', $oneMonth)->limit(12)->get();

		$mostViewed = Manga::where('status', '=', 'published')->with(['rating', 'viewsMonth'])->has('viewsMonth')->limit(16)->get()->sortByDesc('viewsMonth');
		$newChapters = Chapter::where('chapters.created_at', '>=', $oneWeek)->orderBy('chapters.id', 'desc')
		->limit(12)->has('manga')->get()->unique('manga_id')->groupBy('type');
		$topMonthly = Manga::where('status', '=', 'published')->select(['id', 'slug', 'name', 'featured_image'])->withAvg('monthRating', 'rating')->has('monthRating')->orderBy('month_rating_avg_rating', 'DESC')->get();

		$slider = Slider::get();
		$categories = Category::has('mangas')->inRandomOrder()->limit(3)->get();
		$viewData = [
			'categories' => $categories,
			'mostViewed' => $mostViewed,
			'newChapters' => $newChapters,
			'topmonth' => $topMonthly,
			'slider' => $slider
		];

        return view('home', $viewData);
    }
}