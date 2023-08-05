<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        Setting::create([
            'title' => "Nartag | Traducciones amistosas",
            'logo' => null,
            'favicon' => null,
            'maintenance' => false,
            'allow_new_users' => true
        ]);
    }
}