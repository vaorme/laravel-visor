<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MangaTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            [
                'name' => 'Manga',
                'slug' => 'manga'
            ],
            [
                'name' => 'Manhua',
                'slug' => 'manhua'
            ],
            [
                'name' => 'Manhwa',
                'slug' => 'manhwa'
            ],
            [
                'name' => 'Novela',
                'slug' => 'novela'
            ],
            [
                'name' => 'One Shot',
                'slug' => 'one-shot'
            ],
            [
                'name' => 'Doujinshi',
                'slug' => 'doujinshi'
            ],
            [
                'name' => 'Oel',
                'slug' => 'oel'
            ]
        ];
        foreach($rows as $row){
            DB::table('manga_type')->insert(
                [
                    'name'=> $row['name'],
                    'slug'=> $row['slug']
                ]
            );
        }
    }
}
