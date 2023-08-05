<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Manga;
use App\Models\Setting;
use App\Models\SettingAds;
use App\Models\SettingSeo;
use Exception;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
        $settings = Setting::get([
            'title as app.title',
            'logo as app.logo',
            'favicon as app.favicon',
            'chat_id as app.chat_id',
            'global_message as app.global_message',
            'maintenance as app.maintenance',
            'allow_new_users as app.allow_new_users',
            'disk as app.disk'
        ])->first();
        $settingSeo = SettingSeo::get([
            'seo_title as app.seo_title',
            'seo_description as app.seo_description',
            'seo_keywords as app.seo_keywords',
            'seo_author as app.seo_author',
            'seo_subject as app.seo_subject',
            'seo_robots as app.seo_robots'
        ])->first();
        $settingAds = SettingAds::get([
            'ads_1 as app.ads_1',
            'ads_2 as app.ads_2',
            'ads_3 as app.ads_3',
            'ads_4 as app.ads_4',
            'ads_5 as app.ads_5',
            'ads_6 as app.ads_6',
            'ads_7 as app.ads_7',
            'ads_8 as app.ads_8',
            'ads_9 as app.ads_9',
            'ads_10 as app.ads_10',
        ])->first();
        
        if($settingSeo){
            config($settingSeo->toArray());
        }
        if($settingAds){
            config($settingAds->toArray());
        }
        if($settings){
            config($settings->toArray());
        }
    }
}
