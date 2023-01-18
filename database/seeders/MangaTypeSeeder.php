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
        $rows = ['Manga','Manhua','Manhwa','Novela','One Shot','Doujinshi','Oel'];
        foreach($rows as $row){
            DB::table('manga_type')->insert(
                ['name'=> $row]
            );
        }
    }
}
