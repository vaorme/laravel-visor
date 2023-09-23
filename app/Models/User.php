<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function isAuthenticated(){
        return Auth::check();
    }
    public function link(){
        $link = route('profile.index', ['username' => $this->username]);
        return $link;
    }
    public function profile(){
        return $this->hasOne(Profile::class);
    }
    public function followedMangas(){
        return $this->belongsToMany(Manga::class, UserFollowManga::class)->where('manga.status', '=', 'published');
    }
    public function favoriteMangas(){
        return $this->belongsToMany(Manga::class, UserHasFavorite::class)->where('manga.status', '=', 'published');
    }
    public function shortcutMangas(){
        return $this->belongsToMany(Manga::class, UserShortcut::class)->where('manga.status', '=', 'published')->limit(20);
    }
    public function latestChapters(){
        return $this->followedMangas->map(function ($manga) {
            return $manga->lastChapter;
        })->filter(function ($chapter) {
            return $chapter !== null;
        })->sortByDesc(function ($chapter) {
            return optional($chapter)->created_at; // Get the creation date of the latest chapter
        })->values();
    }
    public function verifiedEmail(){
        return ($this instanceof MustVerifyEmail);
    }
    public function userBuyChapter($chapterid){
        $instance = UserBuyChapter::where('user_id', '=',$this->id)
        ->whereNotNull('chapter_id')
        ->where('chapter_id', '=',$chapterid);
        if($instance->exists()){
            return $instance->get();
        }
        return false;
    }
    public function buyChapter($chapterid){
        $chapter = Chapter::find($chapterid);
        if($chapter){
            $userCoins = $this->coins;
            if(!$userCoins){
                return response()->json([
                    'status' => "error",
                    'message' => "No tienes suficientes monedas."
                ]);
            }
            if($userCoins->coins < $chapter->price){
                return response()->json([
                    'status' => "error",
                    'message' => "No tienes suficientes monedas."
                ]);
            }
            $userCoins->coins = $userCoins->coins - $chapter->price;
            $buy = UserBuyChapter::create([
                'price' => $chapter->price,
                'chapter_id' => $chapterid,
                'user_id' => $this->id
            ]);
            if($buy){
                $userCoins->save();
                $chapter = Chapter::find($chapterid);
                if($chapter){
                    return response()->json([
                        'status' => "success",
                        'url' => $chapter->url(),
                        'message' => "Capítulo comprado."
                    ]);
                }
                return response()->json([
                    'status' => "success",
                    'message' => "Capítulo comprado."
                ]);
            }
            return response()->json([
                'status' => "error",
                'message' => "Ups, algo paso con tu compra."
            ]);
        }
        return response()->json([
            'status' => "error",
            'message' => "Ups, capítulo no encontrado"
        ]);
    }
    public function daysNotAds(){
        return $this->hasOne(UserBuyDays::class);
    }
    public function coins(){
        return $this->hasOne(UserBuyCoins::class);
    }
    public function purchaseDays($days = 0){
        // Check if the user already has an ad-free days record
        $existingRecord = $this->daysNotAds;
        if ($existingRecord) {

            $today = Carbon::now();
            $lastUpdated = Carbon::parse($existingRecord->last_updated);
            $elapsedDays = $today->diffInDays($lastUpdated);
            $remainingDays = $existingRecord->days_without_ads - $elapsedDays;

            // Check if the assigned days exceed the remaining days
            if ($days >= $remainingDays) {
                // Reset the days count to 0
                $existingRecord->days_without_ads = 0;
            } else {
                // Update the days count by subtracting the elapsed days
                $existingRecord->days_without_ads -= $elapsedDays;
            }

            $existingRecord->last_updated = Carbon::now();
            $existingRecord->days_without_ads += $days;
            $existingRecord->save();
        }else{
            UserBuyDays::create([
                'username' => $this->username,
                'user_id' => $this->id,
                'last_updated' => Carbon::now(),
                'days_without_ads' => $days,
            ]);
        }
        return true;
    }
    public function purchaseCoins($coins = 0){
        $existingRecord = $this->coins;
        if ($existingRecord) {
            $existingRecord->increment('coins', $coins);
        } else {
            UserBuyCoins::create([
                'username' => $this->username,
                'user_id' => $this->id,
                'coins' => $coins,
            ]);
        }
        return true;
    }
    public function assignCoins($coins = 0){
        $instance = UserBuyCoins::where('user_id', $this->id);
        if($instance->exists()){
            $instance->update([
                'coins' => $coins,
            ]);
            return true;
        }
        return false;
    }
    public function assignDays($days = 0){
        $instance = UserBuyDays::where('user_id', $this->id);
        if($instance->exists()){
            $instance->update([
                'days_without_ads' => $days,
            ]);
            return true;
        }
        return false;
    }
    public function removeCoins($coins = 0){
        $instance = UserBuyCoins::where('user_id', $this->id);
        if($instance->exists()){
            $current = $this->coins;
            $resta = $current->coins - $coins;
            if($resta <= 0){
                $current->coins = 0;
                $current->save();
            }else{
                $current->coins = $resta;
                $current->save();
            }
            return true;
        }
        return false;
    }
    public function removeDays($days = 0){
        $instance = UserBuyDays::where('user_id', $this->id);
        if($instance->exists()){
            $current = $this->daysNotAds;
            $resta = $current->days_without_ads - $days;
            if($resta <= 0){
                $current->days_without_ads = 0;
                $current->save();
            }else{
                $current->days_without_ads = $resta;
                $current->save();
            }
            return true;
        }
        return false;
    }
    public function showAds(){
        $userDays = UserBuyDays::where('user_id', $this->id)->first();
        if ($userDays && $userDays->user_id == $this->id) {
            $today = Carbon::now();
            $lastUpdated = Carbon::parse($userDays->last_updated);
            $elapsedDays = $today->diffInDays($lastUpdated);
            $remainingDays = $userDays->days_without_ads - $elapsedDays;
            if ($remainingDays <= 0) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
    public function remainingDays() {
        $userDays = UserBuyDays::where('user_id', $this->id)->first();
        if ($userDays) {
            $today = Carbon::now();
            $lastUpdated = Carbon::parse($userDays->last_updated);
            $elapsedDays = $today->diffInDays($lastUpdated);
            $remainingDays = $userDays->days_without_ads - $elapsedDays;
            if ($remainingDays <= 0) {
                return 0;
            } else {
                return $remainingDays;
            }
        }
        return 0;
    }
}
