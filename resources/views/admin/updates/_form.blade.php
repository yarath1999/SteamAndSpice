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
    <label for="title">Title</label>
    <input id="title" name="title" value="{{ old('title', $updatePost->title ?? '') }}" required>
</div>
<div class="form-group">
    <label for="content">Content</label>
    <textarea id="content" name="content" rows="8" required>{{ old('content', $updatePost->content ?? '') }}</textarea>
</div>
<div class="grid">
    <div class="form-group">
        <label for="image">Image</label>
        <input id="image" type="file" name="image" accept="image/*">
    </div>
    <label style="align-self:end;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $updatePost->is_active ?? true)) style="width:auto;"> Active
    </label>
</div>
@if(!empty($updatePost) && $updatePost->image)
    <p>Current image:</p>
    <img src="{{ $storageImageUrl($updatePost->image) }}" alt="Current" onerror="this.onerror=null;this.src='{{ asset('images/fallback.jpg') }}';" style="max-width: 200px; border-radius: 10px;">
@endif
