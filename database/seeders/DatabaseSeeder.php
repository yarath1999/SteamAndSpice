<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use App\Models\SiteSetting;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            MenuItemSeeder::class,
        ]);

        $adminUser = User::query()->firstOrCreate(
            ['email' => 'admin@steamandspice.com'],
            [
                'name' => 'Admin',
                'password' => 'password123',
            ]
        );

        if (!$adminUser->is_admin) {
            $adminUser->is_admin = true;
            $adminUser->save();
        }

        HomepageSection::query()->firstOrCreate([], [
            'hero_title' => 'Steam & Spice',
            'hero_subtitle' => 'Nepali Fusion Kitchen',
            'hero_tagline' => 'Every dish full of life',
            'contact_phone' => '+44 20 1234 5678',
            'intro_title' => 'A Warm Welcome',
            'intro_text' => 'We serve handcrafted dishes inspired by street flavors and elevated cuisine.',
        ]);

        SiteSetting::query()->firstOrCreate([], [
            'phone' => '+44 20 1234 5678',
            'email' => 'hello@steamandspice.co.uk',
            'address' => '221B Baker Street, London, UK',
        ]);
    }
}
