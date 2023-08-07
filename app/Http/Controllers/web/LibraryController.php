<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manga;
use App\Models\MangaBookStatus;
use App\Models\MangaDemography;
use App\Models\MangaType;
use Illuminate\Http\Request;

class LibraryController extends Controller{
    public function index(Request $request){
        $list = Manga::get();
        $types = MangaType::get();
        $demographics = MangaDemography::get();
        $bookStatus = MangaBookStatus::get();
        $categories = Category::get();
        return view('library', [
            'list' => $list,
            'types' => $types,
            'demographics' => $demographics,
            'bookStatus' => $bookStatus,
            'categories' => $categories
        ]);
    }
}