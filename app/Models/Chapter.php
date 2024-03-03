<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

class Chapter extends Model{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
    use HasFactory;
    
    public function manga(): BelongsTo{
        return $this->belongsTo(Manga::class)->where('status', '=', 'published');
    }
    public function isChapterPremium(){
        if($this->price && $this->price > 0){
            return true;
        }
        return false;
    }
    public function url(){
        if($this->manga){
            return URL::route('chapter_viewer.index', [
                'manga_slug' => $this->manga->slug,
                'chapter_slug' => $this->slug
            ]);
        }
        return "/";
    }
    public function next($mangaid){
        // GET NEXT
        $chapter = Chapter::where('manga_id', '=', $mangaid)->where('order', '>', $this->order)->orderBy('order', 'asc')->first();
        return $chapter ? Chapter::where('id', '=', $chapter->id)->get('slug')->first() : null;
    }
    public  function prev($mangaid){
        // GET PREVIOUS
        $chapter = Chapter::where('manga_id', '=', $mangaid)->where('order', '<', $this->order)->orderBy('order', 'desc')->latest()->first();
        return $chapter? Chapter::where('id', '=', $chapter->id)->get('slug')->first() : null;
    }
}
