<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MangaDemographySeeder extends Seeder
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
                'name' => 'Shōnen',
                'slug' => 'shonen',
            ],
            [
                'name' => 'Shōjo',
                'slug' => 'shojo',
            ],
            [
                'name' => 'Seinen',
                'slug' => 'seinen',
            ],
            [
                'name' => 'Josei',
                'slug' => 'josei',
            ],
            [
                'name' => 'Kodomo',
                'slug' => 'kodomo',
            ]
        ];
        foreach($rows as $row){
            DB::table('manga_demography')->insert(
                [
                    'name'=> $row['name'],
                    'slug'=> $row['slug']
                ]
            );
        }
    }
}
