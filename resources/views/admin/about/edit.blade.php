@extends('layouts.admin')

@section('content')
@php
    $fallbackImage = asset('images/fallback.jpg');
    $storageImageUrl = function (?string $path) use ($fallbackImage) {
        if (!$path) {
            return $fallbackImage;
        }

        $thumbPath = dirname($path) . '/thumb_' . basename($path);

        return file_exists(public_path('storage/' . $thumbPath))
            ? asset('storage/' . $thumbPath)
            : asset('storage/' . $path);
    };
@endphp

<h1>About Us</h1>
<p class="muted" style="margin-top: -8px; margin-bottom: 20px;">Update the public About page content, title, and image.</p>

<form class="panel" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label for="title">Title</label>
        <input id="title" type="text" name="title" value="{{ old('title', $about->title ?? '') }}" placeholder="About Us">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" rows="8" placeholder="Write the About page story here...">{{ old('description', $about->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="image">Image</label>
        <input id="image" type="file" name="image" accept="image/*">
        <small style="display:block; margin-top:8px; color:#cfcfcf;">Upload a wide, high-quality image for the About section.</small>
    </div>

    @if(!empty($about?->image))
        <div class="form-group">
            <label>Current Image Preview</label>
            <img src="{{ $storageImageUrl($about->image) }}" alt="About image" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';" style="width:100%; max-width:360px; height:220px; object-fit:cover; object-position:center; border-radius:10px; display:block; border:1px solid rgba(241, 200, 118, 0.16);">
        </div>
    @endif

    <button class="btn cta-btn" type="submit">Save About Content</button>
</form>
@endsection
