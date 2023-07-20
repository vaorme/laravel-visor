<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebController extends Controller{
    public function index(){
		$oneWeek = date('Y-m-d', strtotime("-6 day"));

		$newManga = Manga::join('manga_has_categories', 'manga_has_categories.manga_id', '=', 'manga.id')
		->join('manga_demography', 'manga_demography.id', '=', 'manga.demography_id')
		->join('manga_type', 'manga_type.id', '=', 'manga.type_id')
		->where('manga.created_at', '>=', $oneWeek)
		->limit(12)
		->get([
			'manga.*',
			'manga_demography.name as manga_demography_name',
			'manga_demography.slug as manga_demography_slug',
			'manga_type.name as manga_type_name',
			'manga_type.slug as manga_type_slug'
		]);

		$popularManga = Manga::join('manga_has_categories', 'manga_has_categories.manga_id', '=', 'manga.id')
		->join('manga_demography', 'manga_demography.id', '=', 'manga.demography_id')
		->join('manga_type', 'manga_type.id', '=', 'manga.type_id')
		->limit(12)
		->get([
			'manga.*',
			'manga_demography.name as manga_demography_name',
			'manga_demography.slug as manga_demography_slug',
			'manga_type.name as manga_type_name',
			'manga_type.slug as manga_type_slug'
		]);

		$newChapters = Chapter::join('manga', 'manga.id', '=', 'chapters.manga_id')
		->where('chapters.created_at', '>=', $oneWeek)->orderBy('chapters.id', 'desc')
		->limit(12)->get([
			'chapters.*',
			'manga.name as manga_name',
			'manga.featured_image',
			'manga.slug as manga_slug'
		])->unique('manga_id')->groupBy('type');

        return view('home', [
			'newManga' => $newManga,
			'popularManga' => $popularManga,
			'newChapters' => $newChapters
		]);
    }
}