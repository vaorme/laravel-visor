<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRanks extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = [
            'Integration Phase',
            'White Core',
            'Silver Core',
            'Yellow Core',
            'Orange Core',
            'Red Core',
            'Black Core',
            'Aether Core'
        ];
        foreach($rows as $row){
            DB::table('ranks')->insert(
                ['name'=> $row]
            );
        }
    }
}
