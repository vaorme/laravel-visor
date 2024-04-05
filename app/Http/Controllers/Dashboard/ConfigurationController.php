<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SettingAds;
use App\Models\SettingSeo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller{
    private $disk;

    public function __construct(){
        $this->disk = 'public';
    }

    public function index(){
        $settings = Setting::first();
		$viewData = [
			'setting' => $settings
		];
        return view('dashboard.settings.index', $viewData);
    }
    public function update(Request $request){
        $request->validate([
            'title' => ['nullable', 'string', 'max:60'],
            'email' => ['nullable', 'string', 'email', 'regex:/^.+@.+$/i','max:255'],
            'logo' => ['dimensions:max_width=512,max_height=512', 'max:400', 'mimes:webp,jpg,jpeg,png,gif'],
            'favicon' => ['dimensions:max_width=248,max_height=248', 'max:140', 'mimes:webp,jpg,jpeg,png,gif'],
            'chatid' => ['nullable', 'string', 'max:240'],
            'message' => ['nullable', 'string', 'max:240'],
        ]);

        $update = Setting::get()->first();
        $update->title = $request->title;
        $update->email = $request->email;
        $update->chat_id = $request->chat_id;
        $update->global_message = $request->message;
        if(isset($request->maintenance) && $request->maintenance == 'on'){
            $update->maintenance = 1;
        }else{
            $update->maintenance = 0;
        }
        if(isset($request->allow_new_users) && $request->allow_new_users == 'on'){
            $update->allow_new_users = 1;
        }else{
            $update->allow_new_users = 0;
        }
        $update->disk = $request->disk;
        $update->insert_head = $request->insert_head;
        $update->insert_body = $request->insert_body;
        $update->insert_footer = $request->insert_footer;
        $logo = $request->file('logo');
        if($logo){
            Storage::disk($this->disk)->deleteDirectory('images/site/logo');
            Storage::disk($this->disk)->makeDirectory('images/site/logo');

            $avatarExtension = $request->file('logo')->extension();
            $pathAvatar = $request->file('logo')->storeAs('images/site/logo', 'logo-site.'.$avatarExtension, $this->disk);
            $update->logo = 'images/site/logo/logo-site.'.$avatarExtension;
        }

        $favicon = $request->file('favicon');
        if($favicon){
            Storage::disk($this->disk)->deleteDirectory('images/site/favicon');
            Storage::disk($this->disk)->makeDirectory('images/site/favicon');

            $avatarExtension = $request->file('favicon')->extension();
            $pathAvatar = $request->file('favicon')->storeAs('images/site/favicon', 'favicon-site.'.$avatarExtension, $this->disk);
            $update->favicon = 'images/site/favicon/favicon-site.'.$avatarExtension;
        }
        if($update->save()){
            Cache::forget('settings');
            return redirect()->route('settings.index')->with('success', 'Configuración actualizada');
        }
        return redirect()->route('settings.index')->with('error', 'Ups, se complico la cosa');
    }
    public function ads(){
        $settings = SettingAds::exists();
        if($settings){
            $settings = SettingAds::first();
            $viewData = [
                'setting' => $settings
            ];
        }else{
            $viewData = [];
        }
		
        return view('dashboard.settings.ads', $viewData);
    }
    public function adsStore(Request $request){
        $store = new SettingAds;
        $store->ads_1 = $request->ads_1;
        $store->ads_2 = $request->ads_2;
        $store->ads_3 = $request->ads_3;
        $store->ads_4 = $request->ads_4;
        $store->ads_5 = $request->ads_5;
        $store->ads_6 = $request->ads_6;
        $store->ads_7 = $request->ads_7;
        $store->ads_8 = $request->ads_8;
        $store->ads_9 = $request->ads_9;
        $store->ads_10 = $request->ads_10;
        
        if($store->save()){
            Cache::forget('settings_ads');
            return redirect()->route('settings.ads.index')->with('success', 'ADS Creados');
        }
        return redirect()->route('settings.ads.index')->with('error', 'Ups, se complico la cosa');
    }
    public function adsUpdate(Request $request){
        $update = SettingAds::get()->first();
        $update->ads_1 = $request->ads_1;
        $update->ads_2 = $request->ads_2;
        $update->ads_3 = $request->ads_3;
        $update->ads_4 = $request->ads_4;
        $update->ads_5 = $request->ads_5;
        $update->ads_6 = $request->ads_6;
        $update->ads_7 = $request->ads_7;
        $update->ads_8 = $request->ads_8;
        $update->ads_9 = $request->ads_9;
        $update->ads_10 = $request->ads_10;
        
        if($update->save()){
            Cache::forget('settings_ads');
            return redirect()->route('settings.ads.index')->with('success', 'ADS actualizados');
        }
        return redirect()->route('settings.ads.index')->with('error', 'Ups, se complico la cosa');
    }
    public function seo(){
        $settings = SettingSeo::exists();
        if($settings){
            $settings = SettingSeo::first();
            $viewData = [
                'setting' => $settings
            ];
        }else{
            $viewData = [];
        }
		
        return view('dashboard.settings.seo', $viewData);
    }
    public function seoStore(Request $request){
        $store = new SettingSeo;
        $store->seo_title = $request->seo_title;
        $store->seo_description = $request->seo_description;
        $store->seo_keywords = $request->seo_keywords;
        $store->seo_author = $request->seo_author;
        $store->seo_subject = $request->seo_subject;
        $store->seo_robots = $request->seo_robots;
        
        if($store->save()){
            Cache::forget('settings_seo');
            return redirect()->route('settings.seo.index')->with('success', 'SEO Creado');
        }
        return redirect()->route('settings.seo.index')->with('error', 'Ups, se complico la cosa');
    }
    public function seoUpdate(Request $request){
        $update = SettingSeo::get()->first();
        $update->seo_title = $request->seo_title;
        $update->seo_description = $request->seo_description;
        $update->seo_keywords = $request->seo_keywords;
        $update->seo_author = $request->seo_author;
        $update->seo_subject = $request->seo_subject;
        $update->seo_robots = $request->seo_robots;
        
        if($update->save()){
            Cache::forget('settings_seo');
            return redirect()->route('settings.seo.index')->with('success', 'SEO actualizado');
        }
        return redirect()->route('settings.seo.index')->with('error', 'Ups, se complico la cosa');
    }
}
