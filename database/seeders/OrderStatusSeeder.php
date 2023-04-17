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
        $rows = [
            [
                'name' => 'Completed',
                'slug' => 'completed',
            ],
            [
                'name' => 'Processing',
                'slug' => 'processing',
            ],
            [
                'name' => 'On hold',
                'slug' => 'on-hold',
            ],
            [
                'name' => 'Failed',
                'slug' => 'failed',
            ],
            [
                'name' => 'Canceled',
                'slug' => 'canceled',
            ]
        ];
        foreach($rows as $row){
            DB::table('order_status')->insert(
                [
                    'name'=> $row['name'],
                    'slug'=> $row['slug']
                ]
            );
        }
    }
}
