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

    $heroImage = $storageImageUrl($homepage->hero_image);
@endphp

<style>
    :root {
        --home-bg: #1a120b;
        --home-bg-deep: #2b1d14;
        --home-highlight: #ff7b32;
        --home-accent: #f1c876;
        --home-text: #f5f5f5;
        --home-text-soft: #cfcfcf;
        --home-panel: rgba(43, 29, 20, 0.94);
        --home-border: rgba(241, 200, 118, 0.2);
        --hero-logo: url('{{ asset('images/hero-logo.jpg') }}');
    }

    .home-shell {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        background: radial-gradient(circle at 20% 30%, rgba(255, 140, 60, 0.14), transparent 40%),
                    radial-gradient(circle at 80% 70%, rgba(255, 200, 120, 0.10), transparent 40%),
                    linear-gradient(180deg, var(--home-bg), var(--home-bg-deep));
        color: var(--home-text);
        padding: 0 0 64px;
        overflow: hidden;
    }

    .home-wrap {
        width: min(1200px, 100%);
        margin: 0 auto;
        padding: 0 16px;
    }

    @media (max-width: 768px) {
        .home-wrap {
            padding: 0 12px;
        }
    }

    @media (max-width: 480px) {
        .home-wrap {
            padding: 0 16px;
        }
    }

    /* Horizontal scroll lists for promos and gallery */
    .horizontal-scroll {
        display: flex;
        gap: 24px;
        overflow-x: auto;
        scroll-snap-type: x proximity;
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        overscroll-behavior-x: contain;
        scroll-padding-inline: 12px;
        padding: 12px 12px 18px;
        mask-image: linear-gradient(90deg, transparent 0, #000 6%, #000 94%, transparent 100%);
    }

    .horizontal-scroll::-webkit-scrollbar {
        height: 8px;
    }

    .promo-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        width: 100%;
    }

    /* Dedicated horizontal scroll container for additional promos (4th+ cards).
       Uses flex layout to avoid conflicts with .promo-list grid rules. */
    .promo-scroll-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
        overflow-y: auto;
        scroll-snap-type: y mandatory;
        -webkit-overflow-scrolling: touch;
        padding: 4px 12px;
        margin-top: 24px;
        min-height: clamp(1400px, 280vh, 1800px);
        max-height: clamp(1400px, 280vh, 1800px);
    }

    .promo-scroll-list::-webkit-scrollbar {
        width: 8px;
    }

    .promo-scroll-list::-webkit-scrollbar-track {
        background: rgba(255, 200, 120, 0.08);
        border-radius: 10px;
    }

    .promo-scroll-list::-webkit-scrollbar-thumb {
        background: rgba(255, 123, 50, 0.3);
        border-radius: 10px;
    }

    .promo-scroll-list::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 123, 50, 0.5);
    }

    /* Scroll cards reuse the full promo-row layout so every promo keeps the same pattern. */
    .promo-scroll-list .promo-card {
        padding: 28px 24px;
    }

    .promo-scroll-list .promo-row {
        flex: 0 0 auto;
        width: 100%;
        margin-bottom: 0;
        gap: 32px;
        scroll-snap-align: start;
    }

    .promo-scroll-list .promo-text h2 {
        font-size: 30px;
        margin-bottom: 14px;
    }

    .promo-scroll-list .promo-text p {
        margin: 12px 0 20px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .promo-scroll-list .promo-image img {
        height: 240px;
    }

    .promo-scroll-list .promo-row:hover .promo-image img {
        transform: scale(1.04);
    }

    @media (max-width: 768px) {
        .promo-scroll-list {
            gap: 20px;
            padding: 4px 8px;
            min-height: clamp(1350px, 260vh, 1650px);
            max-height: clamp(1350px, 260vh, 1650px);
        }

        .promo-scroll-list .promo-row {
            flex-basis: auto;
            width: 100%;
            gap: 24px;
        }

        .promo-scroll-list .promo-card {
            padding: 24px 18px;
        }

        .promo-scroll-list .promo-text h2 {
            font-size: 24px;
            margin-bottom: 12px;
        }

        .promo-scroll-list .promo-text p {
            -webkit-line-clamp: 4;
        }

        .promo-scroll-list .promo-image img {
            height: 220px;
        }
    }

    @media (max-width: 768px) {
        .promo-list {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .promo-list .promo-card {
        flex: unset;
        width: 100%;
        padding: 24px;
        border-radius: 12px;
        background: var(--home-panel);
        box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    }

    .promo-list .promo-card img {
        display: block;
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .promo-list .promo-card .promo-text {
        padding: 0;
    }

    .gallery-card {
        flex: 0 0 auto;
        width: clamp(240px, 28vw, 340px);
        scroll-snap-align: start;
        background: var(--home-panel);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    }

    .gallery-card-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .gallery-card img { display:block; width:100%; height:220px; object-fit:cover; }

    .gallery-card-caption {
        padding: 10px 12px 12px;
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.4;
        color: var(--home-text);
        min-height: 44px;
        background: linear-gradient(180deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.98));
        border-top: 1px solid rgba(241, 200, 118, 0.12);
    }

    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 16px;
    }

    .hero {
        position: relative;
        height: min(78vh, 720px);
        min-height: 520px;
        background: radial-gradient(circle at center, rgba(255, 123, 50, 0.22), rgba(0, 0, 0, 0) 52%),
                    radial-gradient(circle at 50% 45%, rgba(255, 123, 50, 0.14), rgba(0, 0, 0, 0) 68%),
                    #0a0706;
        background-size: cover;
        background-position: center;
        overflow: hidden;
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: -8% 0 0;
        background-image: var(--hero-logo);
        background-repeat: no-repeat;
        background-position: center 42%;
        background-size: min(92vw, 900px);
        opacity: 0.42;
        filter: blur(5px) saturate(1.08);
        transform: scale(1.04);
        z-index: 1;
    }

    .hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(255, 123, 50, 0.22), rgba(0, 0, 0, 0) 60%);
        opacity: 0.9;
        z-index: 2;
        pointer-events: none;
    }

    @media (max-width: 768px) {
        .hero {
            height: 380px;
        }
    }

    @media (max-width: 480px) {
        .hero {
            height: 320px;
        }
    }

    .hero-media {
        display: none;
    }

    @media (max-width: 768px) {
        .hero-media {
            height: 100%;
        }
    }

    @media (max-width: 480px) {
        .hero-media {
            height: 100%;
        }
    }

    .hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 30%;
        display: block;
        opacity: 0.12;
        filter: blur(2px) saturate(0.9);
    }

    .hero-overlay-layer {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(8, 6, 6, 0.4), rgba(8, 6, 6, 0.78));
        z-index: 3;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 8%;
        z-index: 4;
        text-align: center;
    }

    @media (max-width: 768px) {
        .hero-overlay {
            padding: 0 5%;
            align-items: flex-start;
            padding-top: 32px;
        }
    }

    @media (max-width: 480px) {
        .hero-overlay {
            padding: 0 16px;
            align-items: flex-start;
            padding-top: 24px;
        }
    }

    .hero-content {
        position: relative;
        z-index: 4;
        color: #f5f5f5;
        max-width: 720px;
        text-shadow: 0 6px 24px rgba(0, 0, 0, 0.28);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .hero-content h1 {
        font-size: clamp(2.6rem, 8vw, 5.2rem);
        font-weight: 800;
        line-height: 1.12;
        margin: 0;
        color: #f5f5f5;
        letter-spacing: -0.7px;
        animation: fadeInUp 0.6s ease-out forwards;
        animation-delay: 100ms;
        font-family: 'Playfair Display', Georgia, serif;
        text-transform: uppercase;
        text-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
    }

    .hero-wordmark {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        line-height: 0.88;
    }

    .hero-wordline {
        display: block;
        white-space: nowrap;
    }

    .hero-wordline--amp {
        color: #ff7b32;
        text-shadow: 0 0 18px rgba(255, 123, 50, 0.75);
        font-size: 0.92em;
        margin: 0.06em 0;
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.2rem;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
    }

    @media (max-width: 480px) {
        .hero-content h1 {
            font-size: 1.8rem;
            line-height: 1.2;
            letter-spacing: -0.3px;
        }
    }

    .subtitle {
        margin: 20px 0 0;
        color: var(--home-accent);
        letter-spacing: 1.2px;
        text-transform: uppercase;
        font-weight: 700;
        font-size: clamp(0.8rem, 1.8vw, 0.95rem);
        font-family: 'Jost', 'Segoe UI', sans-serif;
        order: 1;
    }

    @media (max-width: 480px) {
        .subtitle {
            font-size: 0.75rem;
            letter-spacing: 0.8px;
            margin-top: 16px;
        }
    }

    .tagline {
        margin: 16px 0 0;
        color: #cfcfcf;
        font-size: clamp(1rem, 2vw, 1.15rem);
        line-height: 1.8;
        max-width: 56ch;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        order: 3;
    }

    @media (max-width: 480px) {
        .tagline {
            font-size: 0.95rem;
            margin-top: 12px;
            max-width: 100%;
            line-height: 1.7;
        }
    }

    .cta-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 14px 32px;
        background: #FF7B32;
        color: #1a120b;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: none;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(255, 123, 50, 0.3);
        position: relative;
        overflow: hidden;
        font-size: 1rem;
        order: 4;
    }

    .hero-content h1 {
        order: 2;
    }

    @media (max-width: 480px) {
        .cta-btn {
            padding: 12px 24px;
            font-size: 0.95rem;
            margin-top: 16px;
        }
    }

    /* New hero content labels and badge styling */
    .hero-kicker {
        margin-bottom: 10px;
        color: var(--home-accent);
        letter-spacing: 1.2px;
        text-transform: uppercase;
        font-weight: 700;
        font-size: clamp(0.6rem, 1vw, 0.75rem);
        font-family: 'Jost', 'Segoe UI', sans-serif;
        order: 1;
    }

    .hero-cta-heading {
        font-size: clamp(0.9rem, 1.8vw, 1.1rem);
        font-family: 'Playfair Display', Georgia, serif;
        font-style: italic;
        color: #ff7b32;
        font-weight: 600;
        text-shadow: 0 6px 20px rgba(255, 123, 50, 0.3);
        letter-spacing: 0.3px;
        margin-top: 12px;
        order: 5;
    }

    .hero-cta-subheading {
        font-size: clamp(0.55rem, 0.85vw, 0.65rem);
        font-family: 'Jost', 'Segoe UI', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #cfcfcf;
        font-weight: 600;
        margin: 5px 0 0;
        order: 6;
    }

    .veg-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border: 1px solid #2ecc71;
        border-radius: 999px;
        color: #2ecc71;
        font-size: clamp(0.55rem, 0.8vw, 0.65rem);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        margin-top: 10px;
        order: 7;
        background: rgba(46, 204, 113, 0.08);
        backdrop-filter: blur(4px);
    }

    .veg-symbol {
        font-size: 1.2em;
        display: inline-block;
    }

    .hero-buttons {
        display: flex;
        gap: 10px;
        margin-top: 12px;
        order: 8;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 7px 16px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 700;
        font-size: clamp(0.65rem, 0.9vw, 0.8rem);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 1px solid transparent;
        cursor: pointer;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        text-transform: uppercase;
        letter-spacing: 0.35px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .btn-primary {
        background: #FF7B32;
        color: #1a120b;
        border-color: #ff7b32;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(255, 123, 50, 0.4);
    }

    .btn-secondary {
        background: rgba(46, 204, 113, 0.12);
        color: #2ecc71;
        border-color: #2ecc71;
        backdrop-filter: blur(4px);
    }

    .btn-secondary:hover {
        background: rgba(46, 204, 113, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 6px 24px rgba(46, 204, 113, 0.3);
    }

    .arrow {
        display: inline-block;
        transition: transform 0.3s ease;
    }

    .btn-primary:hover .arrow {
        transform: translateX(4px);
    }

    @media (max-width: 768px) {
        .hero-kicker {
            font-size: 0.6rem;
            margin-bottom: 6px;
            letter-spacing: 0.9px;
        }

        .hero-cta-heading {
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .hero-cta-subheading {
            font-size: 0.6rem;
            letter-spacing: 0.9px;
            margin-top: 4px;
        }

        .veg-badge {
            font-size: 0.6rem;
            padding: 5px 9px;
            margin-top: 8px;
            gap: 4px;
        }

        .hero-buttons {
            gap: 8px;
            margin-top: 10px;
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
            padding: 7px 14px;
            font-size: 0.7rem;
        }
    }

    @media (max-width: 480px) {
        .hero-kicker {
            font-size: 0.55rem;
            margin-bottom: 5px;
            letter-spacing: 0.8px;
        }

        .hero-cta-heading {
            font-size: 0.82rem;
            margin-top: 9px;
        }

        .hero-cta-subheading {
            font-size: 0.55rem;
            letter-spacing: 0.8px;
            margin-top: 3px;
        }

        .veg-badge {
            font-size: 0.55rem;
            padding: 4px 9px;
            margin-top: 7px;
        }

        .hero-buttons {
            gap: 6px;
            margin-top: 9px;
        }

        .btn {
            padding: 6px 12px;
            font-size: 0.65rem;
            letter-spacing: 0.3px;
        }
    }

    .cta-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.12);
        transition: left 0.4s ease;
        pointer-events: none;
    }

    .cta-btn:hover::before {
        left: 100%;
    }

    .cta-btn:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 35px rgba(255, 123, 50, 0.4);
        background: #FF7B32;
    }

    .cta-btn:active {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(255, 123, 50, 0.3);
    }

    .category-scroll {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin: 28px 0 32px;
        -webkit-overflow-scrolling: touch;
    }

    @media (max-width: 768px) {
        .category-scroll {
            gap: 16px;
            margin: 24px 0 28px;
        }
    }

    @media (max-width: 480px) {
        .category-scroll {
            gap: 12px;
            margin: 20px 0 24px;
        }
    }

    .category-card {
        min-width: 140px;
        padding: 18px 20px;
        background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.92));
        border: 1px solid rgba(241, 200, 118, 0.14);
        color: #f5f5f5;
        border-radius: 16px;
        text-align: center;
        font-weight: 600;
        flex: 0 0 auto;
        transition: all 0.32s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        will-change: transform, box-shadow, background-color;
    }

    @media (max-width: 480px) {
        .category-card {
            min-width: 120px;
            padding: 16px;
            font-size: 0.9rem;
        }
    }

    .category-card:hover {
        transform: translateY(-10px) scale(1.06);
        background: #FF7B32;
        color: #1a120b;
        box-shadow: 0 14px 32px rgba(255, 123, 50, 0.38), 0 0 24px rgba(255, 123, 50, 0.24);
    }

    .category-card:active {
        transform: translateY(-6px) scale(1.03);
    }

    .category-card h3 {
        margin: 0;
        color: inherit;
        font-size: 1.05rem;
        line-height: 1.3;
        font-weight: 700;
    }

    .section-title {
        margin: 0 0 12px;
        color: #f5f5f5;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2rem, 3.5vw, 2.8rem);
        line-height: 1.15;
        font-weight: 800;
        letter-spacing: -0.5px;
        max-width: 22ch;
        margin-left: auto;
        margin-right: auto;
    }

    .section-description {
        margin: 0 auto 36px;
        color: #cfcfcf;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        font-size: clamp(0.95rem, 1.8vw, 1.1rem);
        line-height: 1.8;
        max-width: 65ch;
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: clamp(1.6rem, 3vw, 2rem);
            margin-bottom: 10px;
        }
        .section-description {
            margin-bottom: 28px;
        }
    }

    @media (max-width: 480px) {
        .section-title {
            font-size: 1.4rem;
            margin-bottom: 8px;
        }
        .section-description {
            font-size: 0.95rem;
            margin-bottom: 24px;
        }
    }

    .featured-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 32px;
    }

    @media (max-width: 1024px) {
        .featured-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px;
        }
    }

    @media (max-width: 768px) {
        .featured-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }

    /* Simplify card styling - reduce borders */
    .feature-card {
        background: linear-gradient(180deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.9));
        border: none;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        min-height: 100%;
        transition: all 0.38s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        will-change: transform, box-shadow, border-color;
    }

    .feature-card:hover {
        transform: translateY(-12px) scale(1.04);
        box-shadow: 0 28px 56px rgba(255, 123, 50, 0.28), 0 0 32px rgba(255, 123, 50, 0.18);
    }

    .feature-card:active {
        transform: translateY(-6px) scale(1.01);
    }

    .feature-card img,
    .feature-fallback {
        width: 100%;
        height: 256px;
        object-fit: cover;
        object-position: center;
        background: rgba(255, 255, 255, 0.04);
        transition: transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    @media (max-width: 480px) {
        .feature-card img,
        .feature-fallback {
            height: 200px;
        }
    }

    .feature-card:hover img {
        transform: scale(1.06);
    }

    .feature-body {
        padding: 20px;
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: space-between;
        min-height: 104px;
    }

    .feature-info h3 {
        margin: 0;
        color: #f5f5f5;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .feature-info p {
        margin: 6px 0 0;
        color: #cfcfcf;
        font-size: 0.93rem;
        line-height: 1.5;
        font-weight: 400;
    }

    .feature-price {
        color: var(--home-highlight);
        font-weight: 900;
        font-size: 1rem;
        white-space: nowrap;
    }

    .intro-block,
    .cta-block {
        border-radius: 20px;
        padding: 40px;
        background: linear-gradient(135deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.88));
        border: 1px solid rgba(241, 200, 118, 0.16);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        transition: all 0.38s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .intro-block {
        background: transparent;
        border: none;
        box-shadow: none;
        max-width: 860px;
        margin: 0 auto;
    }

    /* Intro section with image support */
    .intro-block-with-image {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 48px;
        align-items: center;
        max-width: 1100px;
        margin: 0 auto;
    }

    @media (max-width: 1024px) {
        .intro-block-with-image {
            grid-template-columns: 1fr;
            gap: 36px;
        }
    }

    @media (max-width: 768px) {
        .intro-block-with-image {
            gap: 24px;
        }
    }

    .intro-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .intro-image-wrapper {
        position: relative;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 12px 36px rgba(0, 0, 0, 0.25);
        height: 100%;
        min-height: 340px;
    }

    @media (max-width: 768px) {
        .intro-image-wrapper {
            min-height: 280px;
        }
    }

    .intro-image {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        display: block;
        transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .intro-block-with-image:hover .intro-image {
        transform: scale(1.03);
    }

    @media (max-width: 768px) {
        .intro-block,
        .cta-block {
            padding: 28px;
            border-radius: 16px;
        }
    }

    @media (max-width: 480px) {
        .intro-block,
        .cta-block {
            padding: 20px;
        }
    }

    .intro-block p,
    .cta-block p {
        margin: 0;
        color: #cfcfcf;
        line-height: 1.8;
        font-weight: 400;
    }

    .intro-block {
        text-align: center;
        max-width: 900px;
        margin: 0 auto;
    }

    .cta-block {
        display: grid;
        gap: 18px;
        justify-items: center;
        justify-content: center;
        text-align: center;
        border: 2px solid rgba(241, 200, 118, 0.28);
        padding: 44px;
        background: linear-gradient(135deg, rgba(255, 123, 50, 0.08), rgba(241, 200, 118, 0.04));
        border-radius: 20px;
        transition: all 0.38s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: box-shadow, border-color;
    }

    @media (max-width: 768px) {
        .cta-block {
            gap: 16px;
            padding: 32px;
        }
    }

    @media (max-width: 480px) {
        .cta-block {
            gap: 12px;
            padding: 22px;
        }
    }

    .cta-block:hover {
        box-shadow: 0 14px 40px rgba(255, 123, 50, 0.25);
        border-color: rgba(241, 200, 118, 0.4);
        transform: translateY(-2px);
    }

    .cta-block > div {
        max-width: 56ch;
    }

    .cta-text {
        margin: 0;
        color: #f5f5f5;
        font-size: clamp(1.3rem, 2.5vw, 1.8rem);
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    @media (max-width: 480px) {
        .cta-text {
            font-size: 1.2rem;
        }
    }

    .promo-section {
        padding: 0;
    }

    /* Promo cards - cleaner styling */
    .promo-card {
        background: linear-gradient(135deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.88));
        border: none;
        border-radius: 20px;
        padding: 44px 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        transition: all 0.38s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: box-shadow, border-color;
    }

    .promo-card:hover {
        box-shadow: 0 18px 52px rgba(255, 123, 50, 0.25), 0 0 28px rgba(255, 123, 50, 0.16);
    }

    .promo-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 64px;
        margin-bottom: 80px;
    }

    @media (max-width: 1024px) {
        .promo-row {
            gap: 48px;
            margin-bottom: 72px;
        }
    }

    @media (max-width: 768px) {
        .promo-row {
            flex-direction: column;
            gap: 32px;
            margin-bottom: 56px;
            text-align: center;
        }
    }

    @media (max-width: 480px) {
        .promo-row {
            gap: 24px;
            margin-bottom: 40px;
        }
    }

    .promo-row.reverse {
        flex-direction: row-reverse;
    }

    .promo-text {
        flex: 1;
    }

    .promo-text h2 {
        font-size: 36px;
        margin-bottom: 20px;
        font-weight: 800;
        color: #f5f5f5;
        line-height: 1.2;
        max-width: 16ch;
    }

    @media (max-width: 768px) {
        .promo-text h2 {
            font-size: 24px;
            margin-bottom: 12px;
        }
    }

    @media (max-width: 480px) {
        .promo-text h2 {
            font-size: 20px;
        }
    }

    .promo-text p {
        margin: 16px 0 28px;
        color: #cfcfcf;
        font-weight: 400;
        line-height: 1.7;
    }

    @media (max-width: 480px) {
        .promo-text p {
            margin: 12px 0 20px;
        }
    }

    .promo-image {
        flex: 1;
    }

    .promo-image img {
        width: 100%;
        height: 256px;
        border-radius: 18px;
        display: block;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
        transition: transform 0.45s cubic-bezier(0.34, 1.56, 0.64, 1);
        object-fit: cover;
        object-position: center;
    }

    .promo-image img {
        transition: transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .promo-row:hover .promo-image img {
        transform: scale(1.04);
    }

    @media (max-width: 480px) {
        .promo-image img {
            height: 220px;
        }
    }

    .gallery {
        padding: 0;
        text-align: center;
    }

    .gallery-list {
        position: relative;
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        justify-content: center;
        gap: 24px;
        max-width: 900px;
        margin: 0 auto;
    }

    @media (max-width: 1024px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    .gallery-grid img {
        width: 100%;
        height: 256px;
        object-fit: cover;
        object-position: center;
        border-radius: 18px;
        display: block;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: all 0.42s cubic-bezier(0.34, 1.56, 0.64, 1);
        max-width: 320px;
        width: 100%;
        margin: 0 auto;
        will-change: transform, box-shadow;
    }

    @media (max-width: 768px) {
        .gallery-grid img {
            height: 220px;
        }
    }

    @media (max-width: 480px) {
        .gallery-grid img {
            height: 180px;
        }
    }

    .gallery-grid img:hover {
        transform: scale(1.05);
        box-shadow: 0 18px 48px rgba(255, 123, 50, 0.3), 0 0 24px rgba(255, 123, 50, 0.2);
    }

    /* Keyframe animations for smooth entrance effects */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.96);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Base reveal animation */
    .reveal {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94), transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Staggered animation delays for grid items */
    .feature-card:nth-child(1).reveal.is-visible {
        animation-delay: 0ms;
    }

    .feature-card:nth-child(2).reveal.is-visible {
        animation-delay: 60ms;
    }

    .feature-card:nth-child(3).reveal.is-visible {
        animation-delay: 120ms;
    }

    .gallery-grid img:nth-child(1).reveal.is-visible {
        animation-delay: 0ms;
    }

    .gallery-grid img:nth-child(2).reveal.is-visible {
        animation-delay: 70ms;
    }

    .gallery-grid img:nth-child(3).reveal.is-visible {
        animation-delay: 140ms;
    }

    .category-card:nth-child(1) {
        --stagger-delay: 0ms;
    }

    .category-card:nth-child(2) {
        --stagger-delay: 50ms;
    }

    .category-card:nth-child(3) {
        --stagger-delay: 100ms;
    }

    .category-card:nth-child(n+4) {
        --stagger-delay: calc((var(--index, 4) - 4) * 50ms);
    }

    /* Mobile (320px - 480px) */
    @media (max-width: 480px) {
        .hero {
            height: 280px;
        }

        .hero-overlay {
            padding-top: 20px;
        }

        .home-shell {
            padding-bottom: 48px;
        }

        .promo-row {
            margin-bottom: 40px;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
        }
    }

    /* Tablet (481px - 1024px) */
    @media (max-width: 1024px) and (min-width: 481px) {
        .featured-grid {
            gap: 18px;
        }

        .section-title {
            margin-bottom: 22px;
        }
    }

    /* Medium screens (769px - 900px) */
    @media (max-width: 900px) and (min-width: 769px) {
        .featured-grid {
            grid-template-columns: 1fr;
        }

        .promo-row {
            gap: 32px;
        }
    }

    /* Smooth transitions for interactive elements */
    a, button {
        transition: all 0.3s ease;
    }

    /* Accessibility: Respect user motion preferences */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }

        .reveal,
        .reveal.is-visible,
        .hero-cta,
        .hero-cta:hover,
        .cta-btn,
        .category-card,
        .feature-card,
        .gallery-grid img {
            opacity: 1;
            transform: none;
            transition: none;
            filter: none;
            animation: none;
        }
    }

    a:focus-visible,
    button:focus-visible,
    input:focus-visible,
    textarea:focus-visible,
    select:focus-visible {
        outline: 2px solid rgba(241, 200, 118, 0.85);
        outline-offset: 2px;
    }
</style>

<section class="home-shell">
    <div class="home-wrap">
        @php
            $heroTitleParts = preg_split('/\s*&\s*/', trim($homepage->hero_title), 2) ?: [$homepage->hero_title];
            $heroTitleLeft = trim($heroTitleParts[0] ?? $homepage->hero_title);
            $heroTitleRight = trim($heroTitleParts[1] ?? '');
        @endphp
        <section class="hero reveal">
            <div class="relative hero-media" aria-hidden="true">
                <img src="{{ $heroImage }}" alt="{{ $homepage->hero_title }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                <div class="absolute inset-0 bg-black/40 hero-overlay-layer"></div>
            </div>
            <div class="hero-overlay">
                <div class="hero-content">
                    <div class="hero-kicker">{{ $homepage->hero_subtitle }}</div>
                    <h1 class="hero-wordmark">
                        <span class="hero-wordline">{{ $heroTitleLeft !== '' ? $heroTitleLeft : $homepage->hero_title }}</span>
                        <span class="hero-wordline hero-wordline--amp">&amp;</span>
                        @if($heroTitleRight !== '')
                            <span class="hero-wordline">{{ $heroTitleRight }}</span>
                        @endif
                    </h1>
                    <div class="hero-cta-heading">{{ "Every dish full of life" }}</div>
                    <div class="hero-cta-subheading">Come Hungry, Leave Happy</div>
                    <div class="veg-badge">
                        <span class="veg-symbol">🌱</span>
                        <span>100% Vegetarian</span>
                    </div>
                    <div class="hero-buttons">
                        <a href="/order-online" class="btn btn-primary">Explore the Menu <span class="arrow">→</span></a>
                        <a href="https://wa.me/447555759468" class="btn btn-secondary" target="_blank" rel="noopener noreferrer">WhatsApp 07555 759468</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="section section-dark reveal">
            <div class="container categories reveal">
                <h2 class="section-title">Explore Menu</h2>
                <p class="section-description">Browse our carefully curated selection of dishes, from traditional favorites to innovative creations</p>
                <div class="category-scroll">
                    @foreach($categories as $category)
                        <div class="category-card">
                            <h3>{{ $category->name }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section section-light reveal">
            <div class="container reveal">
                <h2 class="section-title">{{ $homepage->hero_subtitle }}</h2>
                <p class="section-description">Discover our most popular dishes, prepared fresh with the finest ingredients</p>
                <div class="featured-grid">
                    @forelse($featuredItems->take(3) as $item)
                        <a href="{{ route('menu') }}" class="feature-card reveal">
                            <img src="{{ $storageImageUrl($item->image_path) }}" alt="{{ $item->name }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                            <div class="feature-body">
                                <div class="feature-info">
                                    <h3>{{ $item->name }}</h3>
                                    <p>{{ Str::limit($item->description ?: $homepage->intro_text, 80) }}</p>
                                </div>
                                <div class="feature-price">GBP {{ number_format((float) $item->price, 2) }}</div>
                            </div>
                        </a>
                    @empty
                        <a href="{{ route('menu') }}" class="feature-card">
                            <div class="feature-fallback" aria-hidden="true"></div>
                            <div class="feature-body">
                                <div class="feature-info">
                                    <h3>{{ $homepage->hero_title }}</h3>
                                    <p>{{ Str::limit($homepage->intro_text, 80) }}</p>
                                </div>
                                <div class="feature-price">—</div>
                            </div>
                        </a>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="section section-dark reveal">
            <div class="container promo-section reveal">
                <h2 class="section-title">Special Offers</h2>
                <p class="section-description">Explore our limited-time promotions and signature dishes</p>
                @php
                    $promoCards = [];

                    $hasLegacy1 = !empty($homepage->promo1_title);
                    $hasLegacy2 = !empty($homepage->promo2_title);
                    $arrayPromos = $homepage->promo_cards ?? [];

                    if ($hasLegacy1) {
                        $promoCards[] = [
                            'title' => $homepage->promo1_title,
                            'description' => $homepage->promo1_description,
                            'link' => route('menu'),
                            'image' => $homepage->promo1_image,
                        ];
                    }

                    if ($hasLegacy2) {
                        $promoCards[] = [
                            'title' => $homepage->promo2_title,
                            'description' => $homepage->promo2_description,
                            'link' => route('menu'),
                            'image' => $homepage->promo2_image,
                        ];
                    }

                    if (!empty($arrayPromos) && is_array($arrayPromos)) {
                        foreach ($arrayPromos as $card) {
                            $promoCards[] = $card;
                        }
                    }
                @endphp

                @if(!empty($promoCards) && is_array($promoCards) && count($promoCards) > 0)
                    <div class="promo-scroll-list reveal" role="list" aria-label="Promotions">
                        @foreach($promoCards as $scrollIndex => $card)
                            @php
                                $scrollReverse = ($scrollIndex % 2) === 1;
                            @endphp
                            <article class="promo-row {{ $scrollReverse ? 'reverse' : '' }} promo-card" role="listitem">
                                <div class="promo-text">
                                    <h2>{{ $card['title'] ?? '' }}</h2>
                                    <p>{{ $card['description'] ?? '' }}</p>
                                    <a href="{{ route('menu') }}" class="btn cta-btn">{{ $card['title'] ? $card['title'] : 'View' }}</a>
                                </div>
                                <div class="promo-image">
                                    <img src="{{ $storageImageUrl($card['image'] ?? null) }}" alt="{{ $card['title'] ?? 'Promotion' }}" decoding="async" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <section class="section section-light reveal">
            <div class="container gallery reveal">
                <h2 class="section-title">{{ $homepage->gallery_title }}</h2>
                <p class="section-description">Visual journey through our culinary creations</p>
                @php $galleryCards = $homepage->gallery_cards ?? []; @endphp
                @if(!empty($galleryCards) && is_array($galleryCards) && count($galleryCards) > 0)
                    <div class="gallery-list horizontal-scroll reveal" role="list" aria-label="Gallery">
                        @foreach(array_slice($galleryCards, 0, 15) as $card)
                            @php
                                $redirect = isset($card['redirect_to_menu']) ? filter_var($card['redirect_to_menu'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : true;
                                $redirect = is_null($redirect) ? true : $redirect;
                                $cardLink = $card['link'] ?? null;
                                $cardTitle = trim($card['title'] ?? '');
                                $imgSrc = $storageImageUrl($card['image'] ?? null);
                            @endphp
                            <div class="gallery-card" role="listitem">
                                @if($redirect)
                                    <a href="{{ route('menu') }}" class="gallery-card-link">
                                        <img src="{{ $imgSrc }}" alt="{{ $cardTitle !== '' ? $cardTitle : 'Gallery image' }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                        @if($cardTitle !== '')
                                            <div class="gallery-card-caption">{{ $cardTitle }}</div>
                                        @endif
                                    </a>
                                @elseif(!empty($cardLink))
                                    <a href="{{ $cardLink }}" class="gallery-card-link">
                                        <img src="{{ $imgSrc }}" alt="{{ $cardTitle !== '' ? $cardTitle : 'Gallery image' }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                        @if($cardTitle !== '')
                                            <div class="gallery-card-caption">{{ $cardTitle }}</div>
                                        @endif
                                    </a>
                                @else
                                    <div class="gallery-card-link">
                                        <img src="{{ $imgSrc }}" alt="{{ $cardTitle !== '' ? $cardTitle : 'Gallery image' }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                        @if($cardTitle !== '')
                                            <div class="gallery-card-caption">{{ $cardTitle }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="gallery-grid">
                        <div class="gallery-card reveal">
                            <a href="{{ route('menu') }}">
                                @if(!empty($homepage->food_card1_image))
                                    <img src="{{ asset('storage/' . $homepage->food_card1_image) }}" alt="Gallery image 1" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                @else
                                    <img src="{{ $fallbackImage }}" alt="Gallery image 1" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                @endif
                            </a>
                        </div>
                        <div class="gallery-card reveal">
                            <a href="{{ route('menu') }}">
                                @if(!empty($homepage->food_card2_image))
                                    <img src="{{ asset('storage/' . $homepage->food_card2_image) }}" alt="Gallery image 2" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                @else
                                    <img src="{{ $fallbackImage }}" alt="Gallery image 2" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                @endif
                            </a>
                        </div>
                        <div class="gallery-card reveal">
                            <a href="{{ route('menu') }}">
                                @if(!empty($homepage->food_card3_image))
                                    <img src="{{ asset('storage/' . $homepage->food_card3_image) }}" alt="Gallery image 3" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                @else
                                    <img src="{{ $fallbackImage }}" alt="Gallery image 3" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'">
                                @endif
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="section section-dark reveal">
            <div class="container reveal">
                @if(!empty($homepage->intro_image))
                    <div class="intro-block-with-image">
                        <div class="intro-content">
                            <h2 class="section-title">{{ $homepage->intro_title }}</h2>
                            <p class="section-description">{{ $homepage->intro_text }}</p>
                        </div>
                        <div class="intro-image-wrapper">
                            <img src="{{ $storageImageUrl($homepage->intro_image) }}" alt="{{ $homepage->intro_title }}" onerror="this.onerror=null;this.src='{{ $fallbackImage }}'" class="intro-image">
                        </div>
                    </div>
                @else
                    <div class="intro-block">
                        <h2 class="section-title">{{ $homepage->intro_title }}</h2>
                        <p class="section-description">{{ $homepage->intro_text }}</p>
                    </div>
                @endif
            </div>
        </section>

        <section class="section section-light reveal">
            <div class="container cta-block reveal">
                <div>
                    <p class="cta-text">{{ $homepage->hero_tagline }}</p>
                    <p>{{ $homepage->hero_subtitle }}</p>
                </div>
                <a class="cta-btn" href="{{ route('ordering') }}">{{ $homepage->cta_button_label }}</a>
            </div>
        </section>
    </div>
</section>

<script>
(() => {
    // Fade-in on scroll with smooth entrance animations
    const elements = document.querySelectorAll('.reveal');
    if (!elements.length) {
        return;
    }

    // Fallback for browsers without IntersectionObserver
    if (!('IntersectionObserver' in window)) {
        elements.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    // Performance: Use passive observer options
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -8% 0px'
        }
    );

    elements.forEach((el) => observer.observe(el));

    // Enhanced hover state management for better performance
    // Disable animations during rapid scrolling
    let scrollTimeout;
    let isScrolling = false;

    window.addEventListener(
        'scroll',
        () => {
            if (!isScrolling) {
                isScrolling = true;
                document.body.style.pointerEvents = 'none';
            }

            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                isScrolling = false;
                document.body.style.pointerEvents = 'auto';
            }, 150);
        },
        { passive: true }
    );
})();
</script>
@endsection
