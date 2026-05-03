@php
    $storageImageUrl = function (?string $path) {
        if (!$path) {
            return asset('images/fallback.jpg');
        }

        $thumbPath = dirname($path) . '/thumb_' . basename($path);

        return file_exists(public_path('storage/' . $thumbPath))
            ? asset('storage/' . $thumbPath)
            : asset('storage/' . $path);
    };
@endphp

<div class="form-group">
    <label for="category_id">Category</label>
    <select id="category_id" name="category_id">
        <option value="">Select existing category</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id', $menuItem->category_id ?? null) == $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="new_category_name">Or New Category Name</label>
    <input id="new_category_name" name="new_category_name" value="{{ old('new_category_name') }}">
</div>
<div class="form-group">
    <label for="name">Name</label>
    <input id="name" name="name" value="{{ old('name', $menuItem->name ?? '') }}" required>
</div>
<div class="grid">
    <div class="form-group">
        <label for="price">Price (GBP)</label>
        <input id="price" type="number" step="0.01" min="0" name="price" value="{{ old('price', $menuItem->price ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input id="image" type="file" name="image" accept="image/*">
    </div>
</div>
<div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="4">{{ old('description', $menuItem->description ?? '') }}</textarea>
</div>
<div class="grid">
    <label><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $menuItem->is_featured ?? false)) style="width:auto;"> Featured</label>
    <label><input type="checkbox" name="is_hot" value="1" @checked(old('is_hot', $menuItem->is_hot ?? false)) style="width:auto;"> Hot</label>
    <label><input type="checkbox" name="is_new" value="1" @checked(old('is_new', $menuItem->is_new ?? false)) style="width:auto;"> New</label>
    <label><input type="checkbox" name="is_available" value="1" @checked(old('is_available', $menuItem->is_available ?? true)) style="width:auto;"> Available</label>
</div>
@if(!empty($menuItem) && $menuItem->image_path)
    <p>Current image:</p>
    <img src="{{ $storageImageUrl($menuItem->image_path) }}" alt="Current" onerror="this.onerror=null;this.src='{{ asset('images/fallback.jpg') }}';" style="max-width: 180px; border-radius: 8px;">
@endif
