<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\MenuItem;
use App\Models\UpdatePost;
use Illuminate\Support\Facades\Schema;

class PublicController extends Controller
{
    public function home()
{
    $homepage = null;

    if (Schema::hasTable('homepage_sections')) {
        $homepage = HomepageSection::query()->latest()->first();
    }

    if (! $homepage) {
        $homepage = (object) [
            'hero_title' => 'Steam & Spice',
            'hero_subtitle' => 'Nepali Fusion Kitchen',
            'hero_tagline' => 'Every dish full of life',
            'contact_phone' => '+44 20 1234 5678',
            'intro_title' => 'A Warm Welcome',
            'intro_text' => 'We serve handcrafted dishes...',
            'hero_image' => null,
        ];
    }

    // ✅ FEATURED ITEMS (existing)
    $featuredItems = collect();
    if (Schema::hasTable('menu_items') && Schema::hasTable('categories')) {
        $featuredItems = MenuItem::query()
            ->where('is_available', true)
            ->where('is_featured', true)
            ->with('category')
            ->take(6)
            ->get();
    }

    // ✅ ADD THIS BLOCK (IMPORTANT)
    $categories = collect();
    if (Schema::hasTable('categories')) {
        $categories = Category::all();
    }

    // ✅ PASS categories to view
    return view('pages.home', compact('homepage', 'featuredItems', 'categories'));
}

    public function menu()
    {
        $categories = collect();
        if (Schema::hasTable('categories') && Schema::hasTable('menu_items')) {
            $categories = Category::with('menuItems')->get();
        }

        return view('pages.menu', compact('categories'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function about()
    {
        $about = null;

        if (\Schema::hasTable('about_pages')) {
            $about = \App\Models\AboutPage::query()->latest()->first();
        }

        return view('pages.about', compact('about'));
    }

    public function updates()
    {
        $updatePosts = collect();

        if (Schema::hasTable('update_posts')) {
            $updatePosts = UpdatePost::query()
                ->where('is_active', true)
                ->latest()
                ->paginate(6);
        }

        return view('pages.updates', compact('updatePosts'));
    }

    public function ordering()
    {
        $menuItems = collect();
        if (Schema::hasTable('menu_items') && Schema::hasTable('categories')) {
            $menuItems = MenuItem::query()->where('is_available', true)->with('category')->orderBy('name')->get();
        }

        return view('pages.ordering', compact('menuItems'));
    }
}
