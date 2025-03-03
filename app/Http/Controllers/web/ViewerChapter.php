<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manga;
use App\Models\ViewCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ViewerChapter extends Controller{
    public function index(Request $request){
        $manga = Manga::where('slug', '=', $request->manga_slug)->get()->first();
        if($manga && $manga->status != "published"){
            return abort(404);
        }

        $chapters = Chapter::where('manga_id', '=', $manga->id)->orderBy('order', 'desc')->get();
        $currentChapter = Chapter::where('slug', '=', $request->chapter_slug)->where('manga_id', '=', $manga->id)->get()->first();
        if(!$currentChapter){
            return abort(404);
        }

        // CACHE VIEW
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

        $viewData = [
            'manga' => $manga,
            'chapters' => $chapters,
            'currentChapter' => $currentChapter,
            'chapter_slug' => $request->chapter_slug,
            'next_chapter' => ($currentChapter->next($manga->id))?? $currentChapter->next($manga->id),
            'prev_chapter' => ($currentChapter->prev($manga->id))?? $currentChapter->prev($manga->id)
        ];

        // :MANGA
        if($currentChapter->type == "comic"){
            $images = json_decode($currentChapter->images);
            if(isset($images) && !empty($images)){
                if(isset($request->reader_type)){
                    $total_pages = count($images);
                    if(isset($request->current_page)){
                        if($request->current_page >= 1 && $request->current_page <= $total_pages){
                            $currentPage = $request->current_page;
                            $currentImage = $images[$currentPage - 1];
                            $currentImageIndex = array_search($currentImage, $images);
                            $prev_paged = ($currentImageIndex == 0)? null : $currentPage - 1;
                            $next_paged = (($currentImageIndex + 1) == $total_pages)? null : $currentPage + 1;
        
                            $viewData['prev_paged'] = $prev_paged;
                            $viewData['current_page'] = $currentPage;
                            $viewData['next_paged'] = $next_paged;

                            $images = [$currentImage];
                        }else{
                            abort(404);
                        }
                    }else{
                        $currentPage = 1;
                        $currentImage = $images[$currentPage - 1];
                        $currentImageIndex = array_search($currentImage, $images);
                        $prev_paged = ($currentImageIndex == 0)? null : $currentPage - 1;
                        $next_paged = ($currentPage == $total_pages)? null : $currentPage + 1;
    
                        $viewData['prev_paged'] = $prev_paged;
                        $viewData['current_page'] = 1;
                        $viewData['next_paged'] = $next_paged;
    
                        $images = [$currentImage];
                    }
                    $viewData['total_pages'] = $total_pages;
                }
                $viewData['images'] = $images;
            }
        }

        // :NOVEL
        if($currentChapter->type == "novel"){
            $viewData['content'] = json_decode($currentChapter->content);
        }
        
        return view('viewer', $viewData);
    }
}