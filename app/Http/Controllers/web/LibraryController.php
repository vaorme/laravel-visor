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
        //return response()->json($request->all());
        //return response()->json($request->categories);
        
        $list = Manga::where('status', '=', 'published');
        $param_search = strip_tags($request->s);
        if(isset($param_search) && !empty($param_search)){
            $list->where(function ($query) use ($param_search) {
                $query->where('name', 'LIKE', '%'.$param_search.'%')->orWhere('alternative_name', 'LIKE', '%'.$param_search.'%');
            });
        }
        $param_type = strip_tags($request->type);
        if(isset($param_type) && !empty($param_type)){
            $list->withWhereHas('type', function($query) use($param_type){
                $query->where('slug', $param_type);
            });
        }
        $param_demography = strip_tags($request->demography);
        if(isset($param_demography) && !empty($param_demography)){
            $list->withWhereHas('demography', function($query) use($param_demography){
                $query->where('slug', $param_demography);
            });
        }
        $param_bookstatus = strip_tags($request->bookstatus);
        if(isset($param_bookstatus) && !empty($param_bookstatus)){
            $list->withWhereHas('bookStatus', function($query) use($param_bookstatus){
                $query->where('slug', $param_bookstatus);
            });
        }
        $param_categories = strip_tags($request->categories);
        if(isset($param_categories) && !empty($param_categories)){
            $cats = explode(',', $param_categories);
            $list->withWhereHas('categories', function($query) use($cats){
                $query->whereIn('slug', $cats);
            });
        }
        $param_excategories = strip_tags($request->excategories);
        if(isset($param_excategories) && !empty($param_excategories)){
            $cats = explode(',', $param_excategories);
            $list->whereDoesntHave('categories', function($query) use($cats){
                $query->whereIn('slug', $cats);
            });
        }
        $limit = 32;
        $types = MangaType::get();
        $demographics = MangaDemography::get();
        $bookStatus = MangaBookStatus::get();
        $categories = Category::orderBy('name')->get();
        return view('library', [
            'list' => $list->paginate($limit),
            'types' => $types,
            'demographics' => $demographics,
            'bookStatus' => $bookStatus,
            'categories' => $categories
        ]);
    }
}