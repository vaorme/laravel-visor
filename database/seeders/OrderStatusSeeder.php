<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = ['Completed', 'Processing', 'On hold', 'Failed', 'Canceled'];
        foreach($rows as $row){
            DB::table('order_status')->insert(
                ['name'=> $row]
            );
        }
    }
}
