<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{

    public function run(): void
    {
         $categories = [
            ['name' => 'Cereais', 'description' => 'Todo os os tipos de cereais', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Frutas', 'description' => 'Variedade de frutas frescas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ferramentas', 'description' => 'Ferramentas manuais e elétricas', 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}
