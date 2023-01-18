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
        $rows = ['Shōnen','Shōjo','Seinen','Josei','Kodomo'];
        foreach($rows as $row){
            DB::table('manga_demography')->insert(
                ['name'=> $row]
            );
        }
    }
}
