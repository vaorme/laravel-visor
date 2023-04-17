<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MangaBookStatusSeeder extends Seeder
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
                'name' => 'Unknown',
                'slug' => 'unknown'
            ],
            [
                'name' => 'Ongoing',
                'slug' => 'ongoing',
            ],
            [
                'name' => 'Completed',
                'slug' => 'completed',
            ],
            [
                'name' => 'Publishing finished',
                'slug' => 'publishing-finished',
            ],
            [
                'name' => 'Publishing',
                'slug' => 'publishing',
            ],
            [
                'name' => 'On hiatus',
                'slug' => 'on-hiatus',
            ]
        ];
        foreach($rows as $row){
            DB::table('manga_book_status')->insert(
                [
                    'name'=> $row['name'],
                    'slug'=> $row['slug']
                ]
            );
        }
    }
}
