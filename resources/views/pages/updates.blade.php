@extends('layouts.app')

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
<style>
    .updates-shell {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        background: linear-gradient(180deg, #1a120b 0%, #2b1d14 100%);
        color: #f5f5f5;
        padding: 42px 0 4px;
    }

    .updates-wrap {
        width: min(1120px, 92%);
        margin: 0 auto;
    }

    .updates-header {
        max-width: 70ch;
        margin-bottom: 36px;
    }

    .updates-kicker {
        margin: 0 0 14px;
        color: #f1c876;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.8rem;
        font-weight: 700;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .updates-title {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2.6rem, 5.8vw, 4.2rem);
        line-height: 1.18;
        color: #f5f5f5;
        letter-spacing: -0.5px;
        font-weight: 700;
    }

    .updates-lead {
        margin: 24px 0 0;
        color: #cfcfcf;
        font-size: clamp(0.95rem, 1.8vw, 1.05rem);
        line-height: 1.75;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .updates-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 28px;
        padding-bottom: 0;
    }

    @media (max-width: 1024px) {
        .updates-grid {
            gap: 24px;
        }
    }

    @media (max-width: 768px) {
        .updates-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .update-card {
        display: grid;
        gap: 20px;
        padding: 24px;
        border-radius: 20px;
        border: 1px solid rgba(241, 200, 118, 0.16);
        background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.9));
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.28);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
    }
    .update-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 24px rgba(255, 123, 50, 0.14);
        border-color: rgba(241, 200, 118, 0.24);
    }

    @media (max-width: 768px) {
        .update-card {
            padding: 20px;
        }
    }

    .update-image {
        width: 100%;
        height: 256px;
        border-radius: 16px;
        object-fit: cover;
        object-position: center;
        display: block;
        background: linear-gradient(145deg, rgba(255, 123, 50, 0.14), rgba(241, 200, 118, 0.08));
        transition: transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .update-card:hover .update-image {
        transform: scale(1.03);
    }

    .update-title {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.4rem, 2.8vw, 1.9rem);
        color: #f5f5f5;
        font-weight: 700;
        letter-spacing: -0.3px;
        line-height: 1.22;
    }

    .update-text {
        margin: 0;
        color: #cfcfcf;
        line-height: 1.75;
        font-size: 0.97rem;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .update-meta {
        color: #cfcfcf;
        font-size: 0.85rem;
        letter-spacing: 0.2px;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .updates-empty {
        color: #ccb89f;
        padding: 10px 0 6px;
    }

    .reveal {
        opacity: 0;
        transform: translateY(12px);
        transition: opacity .34s ease, transform .34s ease;
    }

    .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    @media (max-width: 860px) {
        .updates-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal,
        .reveal.is-visible {
            opacity: 1;
            transform: none;
            transition: none;
        }
    }
</style>

<section class="updates-shell">
    <div class="updates-wrap">
        <header class="updates-header reveal">
            <p class="updates-kicker">Updates</p>
            <h1 class="updates-title">Latest News</h1>
            <p class="updates-lead">Fresh stories, announcements, and kitchen updates from Steam & Spice.</p>
        </header>

        <div class="updates-grid">
            @forelse($updatePosts as $post)
                <article class="update-card reveal">
                    <img class="update-image" src="{{ $storageImageUrl($post->image) }}" alt="{{ $post->title }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">

                    <div>
                        <h2 class="update-title">{{ $post->title }}</h2>
                        <p class="update-text">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}</p>
                    </div>

                    <div class="update-meta">{{ $post->created_at?->format('M d, Y') }}</div>
                </article>
            @empty
                <div class="updates-empty reveal">No updates have been published yet.</div>
            @endforelse
        </div>
    </div>
</section>

<script>
(() => {
    const elements = document.querySelectorAll('.reveal');
    if (!elements.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -8% 0px'
    });

    elements.forEach((element) => observer.observe(element));
})();
</script>
@endsection
