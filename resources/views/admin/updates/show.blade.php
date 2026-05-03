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
<h1>Update #{{ $updatePost->id }}</h1>
<section class="panel" style="display:grid; gap:14px;">
    @if($updatePost->image)
        <img src="{{ $storageImageUrl($updatePost->image) }}" alt="{{ $updatePost->title }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';" style="width:100%; max-width: 720px; border-radius: 14px; object-fit: cover;">
    @endif
    <div>
        <h2 style="margin-bottom: 10px;">{{ $updatePost->title }}</h2>
        <p>{{ $updatePost->content }}</p>
        <p class="muted">Status: {{ $updatePost->is_active ? 'Active' : 'Inactive' }}</p>
    </div>
</section>
@endsection
