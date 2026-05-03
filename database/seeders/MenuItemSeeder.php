<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['category' => 'starters', 'name' => 'Smoked Paprika Prawns', 'price' => 11.50, 'description' => 'Pan-seared prawns with roasted garlic butter and paprika.', 'is_featured' => true],
            ['category' => 'starters', 'name' => 'Charred Corn Chaat', 'price' => 8.90, 'description' => 'Sweet corn, tamarind glaze, lime, and crispy shallots.', 'is_featured' => false],
            ['category' => 'mains', 'name' => 'Steam & Spice Signature Biryani', 'price' => 18.50, 'description' => 'Fragrant basmati rice, saffron, and slow-cooked spiced chicken.', 'is_featured' => true],
            ['category' => 'mains', 'name' => 'Tandoori River Salmon', 'price' => 21.00, 'description' => 'Clay-oven roasted salmon with dill yogurt and mint chutney.', 'is_featured' => true],
            ['category' => 'mains', 'name' => 'Firepot Paneer Masala', 'price' => 16.00, 'description' => 'Paneer cubes in smoky tomato gravy with fenugreek.', 'is_featured' => false],
            ['category' => 'desserts', 'name' => 'Cardamom Creme Brulee', 'price' => 7.50, 'description' => 'Classic caramelized custard infused with green cardamom.', 'is_featured' => true],
            ['category' => 'desserts', 'name' => 'Saffron Kulfi Slice', 'price' => 6.80, 'description' => 'Frozen saffron milk dessert with pistachio crumble.', 'is_featured' => false],
            ['category' => 'drinks', 'name' => 'Rose Masala Lemonade', 'price' => 4.80, 'description' => 'Fresh lemon, rose syrup, black salt, and spice blend.', 'is_featured' => false],
        ];

        foreach ($items as $item) {
            $category = Category::query()->where('slug', $item['category'])->first();

            if (!$category) {
                continue;
            }

            MenuItem::query()->updateOrCreate(
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                ],
                [
                    'price' => $item['price'],
                    'description' => $item['description'],
                    'is_featured' => $item['is_featured'],
                    'is_available' => true,
                ]
            );
        }
    }
}
