<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\UpdatePost;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class UpdatePostController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('update_posts')) {
            $updatePosts = new LengthAwarePaginator([], 0, 12);

            return view('admin.updates.index', compact('updatePosts'))
                ->with('error', 'Updates table is unavailable.');
        }

        $updatePosts = UpdatePost::query()->latest()->paginate(12);

        return view('admin.updates.index', compact('updatePosts'));
    }

    public function create()
    {
        return view('admin.updates.create');
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('update_posts')) {
            return redirect()->route('admin.dashboard')->with('error', 'Updates table is unavailable.');
        }

        $validated = $this->validateData($request);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::upload($request->file('image'), 'updates');
        }

        UpdatePost::create($validated);

        return redirect()->route('admin.updates.index')->with('success', 'Update post created.');
    }

    public function edit(UpdatePost $updatePost)
    {
        return view('admin.updates.edit', compact('updatePost'));
    }

    public function show(UpdatePost $updatePost)
    {
        return view('admin.updates.show', compact('updatePost'));
    }

    public function update(Request $request, UpdatePost $updatePost)
    {
        if (!Schema::hasTable('update_posts')) {
            return redirect()->route('admin.dashboard')->with('error', 'Updates table is unavailable.');
        }

        $validated = $this->validateData($request);

        if ($request->hasFile('image')) {
            if ($updatePost->image) {
                Storage::disk('public')->delete($updatePost->image);
            }
            $validated['image'] = ImageHelper::upload($request->file('image'), 'updates');
        }

        $updatePost->update($validated);

        return redirect()->route('admin.updates.index')->with('success', 'Update post updated.');
    }

    public function destroy(UpdatePost $updatePost)
    {
        if (!Schema::hasTable('update_posts')) {
            return redirect()->route('admin.dashboard')->with('error', 'Updates table is unavailable.');
        }

        if ($updatePost->image) {
            Storage::disk('public')->delete($updatePost->image);
        }

        $updatePost->delete();

        return redirect()->route('admin.updates.index')->with('success', 'Update post deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]) + [
            'is_active' => $request->boolean('is_active', true),
        ];
    }
}
