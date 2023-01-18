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
        $rows = ['Unknown','Ongoing','Completed','Publishing finished','Publishing','On hiatus'];
        foreach($rows as $row){
            DB::table('manga_book_status')->insert(
                ['name'=> $row]
            );
        }
    }
}
