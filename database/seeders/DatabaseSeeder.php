<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Jonatan Vargas',
        //     'email' => 'jonvargasor@gmail.com',
        // ]);
        $this->call([
            RolePermissionSeeder::class,
            MangaBookStatusSeeder::class,
            MangaTypeSeeder::class,
            MangaDemographySeeder::class,
            MangaCategoriesSeeder::class,
            UserRanks::class,
            Countries::class,
            SettingSeeder::class
        ]);
    }
}
