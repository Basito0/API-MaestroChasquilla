<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('regions')->insert([
            ['region_id' => 1, 'name' => 'Arica y Parinacota'],
            ['region_id' => 2, 'name' => 'Tarapacá'],
            ['region_id' => 3, 'name' => 'Antofagasta'],
            ['region_id' => 4, 'name' => 'Atacama'],
            ['region_id' => 5, 'name' => 'Coquimbo'],
            ['region_id' => 6, 'name' => 'Valparaíso'],
            ['region_id' => 7, 'name' => 'Región del Libertador Gral. Bernardo O’Higgins'],
            ['region_id' => 8, 'name' => 'Región del Maule'],
            ['region_id' => 9, 'name' => 'Región del Ñuble'],
            ['region_id' => 10, 'name' => 'Región del Biobío'],
            ['region_id' => 11, 'name' => 'Región de La Araucanía'],
            ['region_id' => 12, 'name' => 'Región de Los Ríos'],
            ['region_id' => 13, 'name' => 'Región de Los Lagos'],
            ['region_id' => 14, 'name' => 'Región Aysén del Gral. Carlos Ibáñez del Campo'],
            ['region_id' => 15, 'name' => 'Región de Magallanes y de la Antártica Chilena'],
            ['region_id' => 16, 'name' => 'Región Metropolitana de Santiago'],
        ]);
    }
}

