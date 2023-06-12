<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebController extends Controller{
    public function index(){
		$yourDate = date("Y-m-d", strtotime("-7 day"));
		
		$latestChapters = Chapter::join('manga', 'manga.id', '=', 'chapters.manga_id')
		->join('manga_has_categories', 'manga_has_categories.manga_id', '=', 'manga.id')
		->join('manga_demography', 'manga_demography.id', '=', 'manga.demography_id')
		->join('manga_type', 'manga_type.id', '=', 'manga.type_id')
		->where('chapters.created_at', '>=', $yourDate)->orderBy('chapters.id', 'desc')
		->limit(12)->get([
			'chapters.*',
			'manga.name as manga_name',
			'manga.featured_image',
			'manga.slug as manga_slug',
			'manga_demography.name as manga_demography_name',
			'manga_demography.slug as manga_demography_slug',
			'manga_type.name as manga_type_name',
			'manga_type.slug as manga_type_slug'
		])->unique('manga_id')->groupBy('type');

        return view('home', [
			'latestChapters' => $latestChapters
		]);
    }
}