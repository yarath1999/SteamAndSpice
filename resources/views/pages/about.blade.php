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

    $aboutTitle = $about->title ?? 'About Us';
    $aboutDescription = $about->description ?? 'We are a modern fusion kitchen serving bold flavors and handcrafted dishes.';
    $aboutImage = $storageImageUrl($about?->image);
@endphp

<section class="about-shell">
    <div class="about-wrap">
        <div class="about-hero reveal">
            <p class="about-kicker">About Steam & Spice</p>
            <h1>{{ $aboutTitle }}</h1>
            <p class="about-lead">{{ $aboutDescription }}</p>
        </div>

        <div class="about-grid reveal">
            <div class="about-story card-surface">
                <div class="story-block">
                    <h2>Our Story</h2>
                    <p>
                        Steam & Spice brings together the warmth of Nepali cooking with the energy of modern street food,
                        creating dishes that feel both familiar and fresh.
                    </p>
                    <p>
                        Every plate is prepared with care, balancing vibrant flavor, comforting texture, and a thoughtful
                        presentation that reflects the character of our kitchen.
                    </p>
                    <p>
                        We believe great food should feel inviting, memorable, and crafted with intention from the first
                        ingredient to the final garnish.
                    </p>
                </div>

                <div class="about-highlights">
                    <div class="highlight-chip">Handcrafted</div>
                    <div class="highlight-chip">Fresh Ingredients</div>
                    <div class="highlight-chip">Fusion Flavours</div>
                    <div class="highlight-chip">Warm Hospitality</div>
                </div>

                <div class="about-quote">
                    <span class="quote-mark">“</span>
                    <p>Cooking with heart, serving with warmth, and sharing flavor with every guest.</p>
                </div>
            </div>

            <div class="about-media card-surface">
                <img src="{{ $aboutImage }}" alt="About Steam & Spice" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                <div class="media-caption">
                    <h3>Crafted for comfort and character</h3>
                    <p>
                        A single image with a strong crop gives the page a premium feel and keeps the focus on the story.
                    </p>
                </div>
            </div>
        </div>

        <div class="about-actions reveal">
            <a class="btn cta-btn" href="{{ route('menu') }}">View Menu</a>
            <a class="btn btn-muted cta-btn" href="{{ route('ordering') }}">Order Online</a>
        </div>
    </div>
</section>

<style>
    .about-shell {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        padding: 84px 8% 96px;
        background: radial-gradient(circle at 20% 18%, rgba(255, 123, 50, 0.16), transparent 34%),
                    radial-gradient(circle at 80% 78%, rgba(241, 200, 118, 0.10), transparent 36%),
                    linear-gradient(180deg, #1a120b 0%, #2b1d14 100%);
    }

    .about-wrap {
        max-width: 1180px;
        margin: 0 auto;
    }

    .about-hero {
        max-width: 760px;
        margin-bottom: 28px;
    }

    .about-kicker {
        margin: 0 0 14px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #FF7B32;
    }

    .about-lead {
        max-width: 68ch;
        color: #cfcfcf;
        font-size: clamp(1rem, 1.6vw, 1.08rem);
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 24px;
        align-items: stretch;
    }

    .card-surface {
        background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.94));
        border: 1px solid rgba(241, 200, 118, 0.16);
        border-radius: 18px;
        box-shadow: 0 12px 30px rgba(20, 14, 5, 0.22);
    }

    .about-story {
        padding: 28px;
    }

    .story-block p {
        max-width: 62ch;
    }

    .about-highlights {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 26px 0 22px;
    }

    .highlight-chip {
        padding: 10px 14px;
        border-radius: 999px;
        background: rgba(255, 123, 50, 0.12);
        border: 1px solid rgba(241, 200, 118, 0.18);
        color: #f5f5f5;
        font-size: 0.92rem;
        font-weight: 600;
    }

    .about-quote {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 18px 20px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(241, 200, 118, 0.12);
    }

    .quote-mark {
        color: #f1c876;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 2.4rem;
        line-height: 1;
    }

    .about-quote p {
        margin: 4px 0 0;
        font-style: italic;
    }

    .about-media {
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .about-media img {
        width: 100%;
        height: 100%;
        min-height: 320px;
        object-fit: cover;
        object-position: center;
        display: block;
        border-bottom: 1px solid rgba(241, 200, 118, 0.12);
    }

    .media-caption {
        padding: 20px 22px 24px;
    }

    .media-caption h3 {
        margin-bottom: 10px;
    }

    .media-caption p {
        margin: 0;
    }

    .about-actions {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .about-actions .btn {
        min-width: 180px;
        padding: 14px 20px;
        border-radius: 14px;
        text-align: center;
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1;
        border: 1px solid transparent;
    }

    .about-actions .btn.cta-btn {
        background: #FF7B32;
        color: #1a120b;
        box-shadow: 0 8px 18px rgba(255, 123, 50, 0.26);
    }

    .about-actions .btn.btn-muted {
        background: linear-gradient(135deg, rgba(255, 123, 50, 0.16), rgba(241, 200, 118, 0.10));
        color: #f5f5f5;
        border-color: rgba(241, 200, 118, 0.18);
        box-shadow: 0 8px 18px rgba(20, 14, 5, 0.16);
    }

    .about-actions .btn:hover {
        transform: translateY(-2px);
    }

    .about-actions .btn.btn-muted:hover {
        box-shadow: 0 12px 24px rgba(20, 14, 5, 0.22);
    }

    .about-actions .btn.cta-btn:hover {
        box-shadow: 0 12px 24px rgba(255, 123, 50, 0.34);
    }

    @media (max-width: 900px) {
        .about-shell {
            padding: 68px 16px 80px;
        }

        .about-grid {
            grid-template-columns: 1fr;
        }

        .about-media img {
            min-height: 260px;
        }
    }

    @media (max-width: 560px) {
        .about-story,
        .media-caption {
            padding: 20px;
        }

        .about-actions {
            flex-direction: column;
        }

        .about-actions .btn {
            width: 100%;
        }
    }
</style>

@endsection