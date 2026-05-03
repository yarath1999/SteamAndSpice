<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Starters', 'slug' => 'starters'],
            ['name' => 'Mains', 'slug' => 'mains'],
            ['name' => 'Desserts', 'slug' => 'desserts'],
            ['name' => 'Drinks', 'slug' => 'drinks'],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
