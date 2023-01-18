<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MangaCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'order' => 1,
                'slug' =>'accion',
                'name' => 'Acción'
            ],
            [
                'order' => 2,
                'slug' =>'aventura',
                'name' => 'Aventura'
            ],
            [
                'order' => 3,
                'slug' =>'drama',
                'name' => 'Drama'
            ],
            [
                'order' => 4,
                'slug' =>'fantasia',
                'name' => 'Fantasia'
            ],
            [
                'order' => 5,
                'slug' =>'magia',
                'name' => 'Magia'
            ],
            [
                'order' => 6,
                'slug' =>'sobrenatural',
                'name' => 'Sobrenatural'
            ],
            [
                'order' => 7,
                'slug' =>'horror',
                'name' => 'Horror'
            ],
            [
                'order' => 8,
                'slug' =>'misterio',
                'name' => 'Misterio'
            ],
            [
                'order' => 9,
                'slug' =>'psicologico',
                'name' => 'Psicológico'
            ],
            [
                'order' => 10,
                'slug' =>'romance',
                'name' => 'Romance'
            ],
            [
                'order' => 11,
                'slug' =>'ciencia-ficcion',
                'name' => 'Ciencia Ficción'
            ],
            [
                'order' => 12,
                'slug' =>'thriller',
                'name' => 'Thriller'
            ],
            [
                'order' => 13,
                'slug' =>'deporte',
                'name' => 'Deporte'
            ],
            [
                'order' => 14,
                'slug' =>'girls-love',
                'name' => 'Girls Love'
            ],
            [
                'order' => 15,
                'slug' =>'boys-love',
                'name' => 'Boys Love'
            ],
            [
                'order' => 16,
                'slug' =>'harem',
                'name' => 'Harem'
            ],
            [
                'order' => 17,
                'slug' =>'mecha',
                'name' => 'Mecha'
            ],
            [
                'order' => 18,
                'slug' =>'gore',
                'name' => 'Gore'
            ],
            [
                'order' => 19,
                'slug' =>'apocaliptico',
                'name' => 'Apocalíptico'
            ],
            [
                'order' => 20,
                'slug' =>'tragedia',
                'name' => 'Tragedia'
            ],
            [
                'order' => 21,
                'slug' =>'vida-escolar',
                'name' => 'Vida Escolar'
            ],
            [
                'order' => 22,
                'slug' =>'historia',
                'name' => 'Historia'
            ],
            [
                'order' => 23,
                'slug' =>'militar',
                'name' => 'Militar'
            ],
            [
                'order' => 24,
                'slug' =>'policiaco',
                'name' => 'Policiaco'
            ],
            [
                'order' => 25,
                'slug' =>'crimen',
                'name' => 'Crimen'
            ],
            [
                'order' => 26,
                'slug' =>'superpoderes',
                'name' => 'Superpoderes'
            ],
            [
                'order' => 27,
                'slug' =>'vampiros',
                'name' => 'Vampiros'
            ],
            [
                'order' => 28,
                'slug' =>'artes-marciales',
                'name' => 'Artes Marciales'
            ],
            [
                'order' => 29,
                'slug' =>'samurai',
                'name' => 'Samurái'
            ],
            [
                'order' => 30,
                'slug' =>'genero-bender',
                'name' => 'Género Bender'
            ],
            [
                'order' => 31,
                'slug' => 'realidad-virtual',
                'name' => 'Realidad Virtual'
            ],
            [
                'order' => 32,
                'slug' =>'ciberpunk',
                'name' => 'Ciberpunk'
            ],
            [
                'order' => 33,
                'slug' =>'musica',
                'name' => 'Musica'
            ],
            [
                'order' => 34,
                'slug' =>'parodia',
                'name' => 'Parodia'
            ],
            [
                'order' => 35,
                'slug' =>'animacion',
                'name' => 'Animación'
            ],
            [
                'order' => 36,
                'slug' =>'demonios',
                'name' => 'Demonios'
            ],
            [
                'order' => 37,
                'slug' =>'familia',
                'name' => 'Familia'
            ],
            [
                'order' => 38,
                'slug' =>'extranjero',
                'name' => 'Extranjero'
            ],
            [
                'order' => 39,
                'slug' =>'ninos',
                'name' => 'Niños'
            ],
            [
                'order' => 40,
                'slug' =>'realidad',
                'name' => 'Realidad'
            ],
            [
                'order' => 41,
                'slug' =>'telenovela',
                'name' => 'Telenovela'
            ],
            [
                'order' => 42,
                'slug' =>'guerra',
                'name' => 'Guerra'
            ],
            [
                'order' => 43,
                'slug' =>'oeste',
                'name' => 'Oeste'
            ],
            [
                'order' => 44,
                'slug' =>'recuentos-de-la-vida',
                'name' => 'Recuentos de la vida'
            ],
            [
                'order' => 45,
                'slug' =>'ecchi',
                'name' => 'Ecchi'
            ]
        ];
        foreach($categories as $category){
            DB::table('categories')->insert(
                [
                    'order'=> $category['order'],
                    'name'=> $category['name'],
                    'slug'=> $category['slug']
                ]
            );
        }
    }
}
