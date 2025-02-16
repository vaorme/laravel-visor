<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\Manga;
use App\Models\Setting;
use App\Models\SettingAds;
use App\Models\SettingSeo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		if (Cache::has('settings')) {
			$settings = Cache::get('settings');
		} else {
			$settings = Setting::get([
				'title as app.title',
				'logo as app.logo',
				'favicon as app.favicon',
				'chat_id as app.chat_id',
				'global_message as app.global_message',
				'maintenance as app.maintenance',
				'allow_new_users as app.allow_new_users',
				'disk as app.disk',
				'insert_head as app.head',
				'insert_body as app.body',
				'insert_footer as app.footer',
			])->first();
			Cache::put('settings', $settings, Carbon::now()->endOfYear());
		}
		if (Cache::has('settings_seo')) {
			$settingSeo = Cache::get('settings_seo');
		} else {
			$settingSeo = SettingSeo::get([
				'seo_title as app.seo_title',
				'seo_description as app.seo_description',
				'seo_keywords as app.seo_keywords',
				'seo_author as app.seo_author',
				'seo_subject as app.seo_subject',
				'seo_robots as app.seo_robots'
			])->first();
			Cache::put('settings_seo', $settingSeo, Carbon::now()->endOfYear());
		}
		if (Cache::has('settings_ads')) {
			$settingAds = Cache::get('settings_ads');
		} else {
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
				'ads_11 as app.ads_11',
				'ads_12 as app.ads_12',
				'ads_13 as app.ads_13',
				'ads_14 as app.ads_14',
				'ads_15 as app.ads_15',
			])->first();
			Cache::put('settings_ads', $settingAds, Carbon::now()->endOfYear());
		}

		if ($settingSeo) {
			config($settingSeo->toArray());
		}
		if ($settingAds) {
			config($settingAds->toArray());
		}
		if ($settings) {
			config($settings->toArray());
		}
	}
}
