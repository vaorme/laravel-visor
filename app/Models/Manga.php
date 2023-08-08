<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Manga extends Model{
    use HasFactory;

    protected $table = "manga";

    public function url(){
        return URL::route('manga_detail.index', [
            'slug' => $this->slug
        ]);
    }
    public function chapters(){
        return $this->hasMany(Chapter::class)->latest();
    }
    public function lastChapter(){
        $week = date('Y-m-d', strtotime("-6 day"));
        return $this->hasOne(Chapter::class)->where('chapters.created_at', '>=', $week);
    }
    public function categories(){
        return $this->belongsToMany(Category::class, 'manga_has_categories', 'manga_id');
    }
    public function userFollowManga(){
        return $this->hasOne(UserFollowManga::class)->where('user_follow_manga.user_id', '=', Auth::id());
    }
    public function demography(){
        return $this->belongsTo(MangaDemography::class);
    }
    public function bookStatus(){
        return $this->belongsTo(MangaBookStatus::class);
    }
    public function type(){
        return $this->belongsTo(MangaType::class);
    }
    public function author(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function rating(){
        return $this->hasMany(UserRateManga::class, 'manga_id');
    }
    public function monthRating(){
        $month = date('Y-m-d', strtotime("-1 month"));
        return $this->hasMany(UserRateManga::class, 'manga_id')->where('manga_rating.created_at', '>=', $month);
    }
    public function viewCount(){
        return $this->hasMany(ViewCount::class, 'manga_id');
    }
    public function viewsMonth(){
        $month = date('Y-m-d', strtotime("-1 month"));
        return $this->hasMany(ViewCount::class, 'manga_id')->where('view_count.created_at', '>=', $month);
    }
    public function cover(){
        $disk = config('app.disk');
        $image = $this->featured_image;
        $url = Storage::disk($disk)->url($image);
        return $url;
    }
    public function newChapter(){
        $date = null;
        if($this->new_chapters_date){
            $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
            $date = Carbon::parse($this->new_chapters_date);
            $mes = $meses[($date->format('n')) - 1];
            switch ($this->new_chapters_time) {
                case 'day':
                    $date->addDay();
                    break;
                case 'week':
                    $date->addWeek();
                    break;
                case 'month':
                    $date->addMonth();
                    break;
            }
            $date = $mes .' '.$date->format('d'). ' de ' . $date->format('Y');;
        }
        return $date;
    }
}
