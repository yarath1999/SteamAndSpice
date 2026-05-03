<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('menu_items')) {
            $menuItems = new LengthAwarePaginator([], 0, 12);

            return view('admin.menu-items.index', compact('menuItems'))
                ->with('error', 'Menu items table is unavailable.');
        }

        $menuItems = MenuItem::with('category')->latest()->paginate(12);
        return view('admin.menu-items.index', compact('menuItems'));
    }

    public function create()
    {
        if (!Schema::hasTable('categories')) {
            return redirect()->route('admin.dashboard')->with('error', 'Categories table is unavailable.');
        }

        $categories = Category::orderBy('name')->get();
        return view('admin.menu-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('menu_items') || !Schema::hasTable('categories')) {
            return redirect()->route('admin.dashboard')->with('error', 'Menu management is temporarily unavailable.');
        }

        $validated = $this->validateData($request);

        if ($request->filled('new_category_name')) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($request->string('new_category_name'))],
                ['name' => $request->input('new_category_name')]
            );
            $validated['category_id'] = $category->id;
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = ImageHelper::upload($request->file('image'), 'menu-items');
        }

        MenuItem::create($validated);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item created.');
    }

    public function edit(MenuItem $menuItem)
    {
        if (!Schema::hasTable('categories')) {
            return redirect()->route('admin.dashboard')->with('error', 'Categories table is unavailable.');
        }

        $categories = Category::orderBy('name')->get();
        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        if (!Schema::hasTable('menu_items') || !Schema::hasTable('categories')) {
            return redirect()->route('admin.dashboard')->with('error', 'Menu management is temporarily unavailable.');
        }

        $validated = $this->validateData($request);

        if ($request->filled('new_category_name')) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($request->string('new_category_name'))],
                ['name' => $request->input('new_category_name')]
            );
            $validated['category_id'] = $category->id;
        }

        if ($request->hasFile('image')) {
            if ($menuItem->image_path) {
                Storage::disk('public')->delete($menuItem->image_path);
            }
            $validated['image_path'] = ImageHelper::upload($request->file('image'), 'menu-items');
        }

        $menuItem->update($validated);

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        if (!Schema::hasTable('menu_items')) {
            return redirect()->route('admin.dashboard')->with('error', 'Menu items table is unavailable.');
        }

        if ($menuItem->image_path) {
            Storage::disk('public')->delete($menuItem->image_path);
        }

        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id', 'required_without:new_category_name'],
            'new_category_name' => ['nullable', 'string', 'max:100', 'required_without:category_id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'is_hot' => ['nullable', 'boolean'],
            'is_new' => ['nullable', 'boolean'],
            'is_available' => ['nullable', 'boolean'],
        ]) + [
            'is_featured' => $request->boolean('is_featured'),
            'is_hot' => $request->boolean('is_hot'),
            'is_new' => $request->boolean('is_new'),
            'is_available' => $request->boolean('is_available', true),
        ];
    }
}
