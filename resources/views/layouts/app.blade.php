<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Steam & Spice</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&family=Playfair+Display:wght@600;700;800;900&display=swap');

        html {
            scroll-behavior: smooth;
        }

        :root {
            --bg: #1a120b;
            --bg-deep: #2b1d14;
            --ink: #f5f5f5;
            --accent: #ff7b32;
            --muted: #cfcfcf;
            --panel: #2a1c13;
            --line: rgba(241, 200, 118, 0.18);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at 20% 30%, rgba(255, 140, 60, 0.22), transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(255, 200, 120, 0.18), transparent 40%),
                        linear-gradient(to bottom, #1a120b, #2b1d14);
            line-height: 1.7;
            letter-spacing: 0.1px;
        }
        body.menu-page {
            background: linear-gradient(180deg, #1a120b 0%, #22170f 48%, #2b1d14 100%);
            color: #f5f5f5;
        }
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 8% 12%, rgba(255, 123, 50, 0.08) 0%, rgba(255, 123, 50, 0) 38%),
                radial-gradient(circle at 84% 6%, rgba(241, 200, 118, 0.1) 0%, rgba(241, 200, 118, 0) 42%);
            z-index: -1;
        }
        h1, h2, h3, h4, h5, h6 {
            margin: 0 0 20px;
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            color: #f5f5f5;
            text-wrap: balance;
        }
        h1 {
            font-size: clamp(2.2rem, 4.5vw, 3.5rem);
            line-height: 1.15;
            letter-spacing: -0.6px;
            margin-bottom: 24px;
        }
        h2 {
            font-size: clamp(1.7rem, 3.5vw, 2.5rem);
            line-height: 1.2;
            letter-spacing: -0.4px;
            margin-bottom: 20px;
        }
        h3 {
            font-size: clamp(1.3rem, 2.8vw, 1.8rem);
            line-height: 1.25;
            letter-spacing: -0.2px;
            margin-bottom: 18px;
        }
        h4, h5, h6 {
            font-size: 1.1rem;
            line-height: 1.3;
            letter-spacing: 0;
            margin-bottom: 16px;
        }
        p {
            margin: 0 0 18px;
            font-size: 16px;
            line-height: 1.7;
            color: #cfcfcf;
            font-family: 'Jost', 'Segoe UI', sans-serif;
        }
        a {
            color: inherit;
        }
        .muted {
            color: #cfcfcf;
        }
        .section {
            padding: 80px 8%;
        }
        .section-dark {
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 140, 60, 0.16), transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255, 200, 120, 0.12), transparent 40%),
                #1a120b;
        }
        .section-light {
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 140, 60, 0.16), transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255, 200, 120, 0.12), transparent 40%),
                linear-gradient(180deg, #2b1d14 0%, #1f150f 100%);
            color: #f5f5f5;
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
        }
        @media (max-width: 768px) {
            .section {
                padding: 64px 8%;
            }
        }
        @media (max-width: 480px) {
            .section {
                padding: 48px 16px;
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease;
        }
        /* Support both class names used across pages/scripts */
        .reveal.is-visible,
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Step 5 — Depth, realism & interaction (UI-only) */
        .menu-card, .promo-card, .food-card {
            border-radius: 12px;
            overflow: hidden;
            transition: 0.3s;
            background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.94));
            border: 1px solid rgba(241, 200, 118, 0.16);
            box-shadow: 0 10px 25px rgba(0,0,0,0.28);
        }
        .menu-card:hover, .promo-card:hover, .food-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.34);
        }

        /* Image hover zoom */
        .menu-card img, .promo-card img, .food-card img {
            transition: transform 0.4s ease;
            will-change: transform;
        }
        .menu-card:hover img, .promo-card:hover img, .food-card:hover img {
            transform: scale(1.05);
        }

        /* CTA / button interactions */
        .cta-btn {
            background: linear-gradient(135deg, #ff7b32, #f1c876);
            color: #1a120b;
            box-shadow: 0 6px 15px rgba(255,123,50,0.3);
            transition: transform .24s ease, box-shadow .24s ease;
        }
        .cta-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255,123,50,0.4);
        }
        .cta-btn:active {
            transform: scale(0.97);
        }

        /* Glass effect for light sections */
        .section-light { /* preserve existing color but ensure contrast */
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 140, 60, 0.16), transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255, 200, 120, 0.12), transparent 40%),
                linear-gradient(165deg, rgba(43, 29, 20, 0.88), rgba(26, 18, 11, 0.72));
            color: #f5f5f5;
        }
        .section-light .container {
            background: linear-gradient(165deg, rgba(43, 29, 20, 0.88), rgba(26, 18, 11, 0.72));
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 40px;
            border: 1px solid rgba(241, 200, 118, 0.14);
        }

        /* Standardized text spacing */
        .section h1 + h2,
        .section h2 + h3,
        .section h3 + p {
            margin-top: 24px;
        }
        .section p + p {
            margin-top: 20px;
        }
        .section p + h2,
        .section p + h3 {
            margin-top: 32px;
        }

        /* Hero animation */
        .hero-content {
            animation: fadeUp 1s ease forwards;
        }
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Section transitions */
        .section {
            transition: all 0.3s ease;
        }

        /* Optional: subtle underline on nav links */
        .nav a {
            position: relative;
        }
        .nav a::after {
            content: '';
            position: absolute;
            left: 10%;
            right: 10%;
            height: 2px;
            bottom: -4px;
            background: transparent;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .22s ease, background .22s ease;
            border-radius: 2px;
        }
        .nav a:hover::after {
            transform: scaleX(1);
            background: #f1c876;
        }
        .hero {
            background-size: cover;
            animation: heroZoom 10s ease-in-out infinite alternate;
        }
        .hero-media img {
            animation: heroZoom 10s ease-in-out infinite alternate;
            transform-origin: center center;
        }
        @keyframes heroZoom {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }
        .navbar,
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            display: flex;
            align-items: center;
            z-index: 70;
            background: linear-gradient(180deg, #2b1d14, #1a120b);
            color: #f5f5f5;
            padding: 14px 0;
            border-bottom: 1px solid rgba(241, 200, 118, 0.12);
            transition: background .3s ease, border-color .3s ease, box-shadow .3s ease, backdrop-filter .3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(0px);
        }
        .navbar.is-scrolled,
        .topbar.is-scrolled {
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.34);
        }
        .topbar.topbar--overlay {
            background: linear-gradient(180deg, #2b1d14, #1a120b);
            border-bottom-color: rgba(241, 200, 118, 0.12);
            box-shadow: none;
            backdrop-filter: blur(0px);
        }
        .topbar.topbar--overlay.is-scrolled {
            background: linear-gradient(180deg, #2b1d14, #1a120b);
            border-bottom-color: rgba(241, 200, 118, 0.12);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(10px);
        }
        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .brand {
            color: #f5f5f5;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 0.2px;
            transition: color .24s ease, text-shadow .24s ease;
        }
        .topbar.topbar--overlay .brand {
            color: #f5f5f5;
            text-shadow: none;
        }
        .nav { display: flex; gap: 12px; flex-wrap: wrap; }

        /* Mobile hamburger menu */
        .hamburger {
            display: none;
            width: 40px;
            height: 36px;
            border: 0;
            background: transparent;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border-radius: 8px;
        }
        .hamburger:focus { outline: none; box-shadow: 0 0 0 3px rgba(255,123,50,0.12); }
        .hamburger .bar { display:block; width:22px; height:2px; background: #f5f5f5; border-radius:2px; position:relative; }
        .hamburger .bar::before, .hamburger .bar::after { content:''; position:absolute; left:0; width:22px; height:2px; background:#f5f5f5; border-radius:2px; }
        .hamburger .bar::before { top:-7px; }
        .hamburger .bar::after { top:7px; }

        @media (max-width: 768px) {
            .hamburger { display: inline-flex; }
            .nav { display: none; }
            .topbar.nav-open .nav {
                display: flex;
                flex-direction: column;
                gap: 8px;
                position: absolute;
                left: 0;
                right: 0;
                top: 70px;
                background: linear-gradient(180deg, rgba(43,29,20,0.98), rgba(26,18,11,0.98));
                padding: 12px 16px 18px;
                z-index: 80;
                border-bottom: 1px solid rgba(241,200,118,0.06);
            }
            .topbar.nav-open .nav a { display: block; padding: 12px 10px; border-radius: 8px; }
        }
        .navbar a,
        .nav a {
            color: #f5f5f5 !important;
            text-decoration: none;
            padding: 6px 9px;
            border-radius: 999px;
            font-size: 0.94rem;
            font-weight: 500;
            letter-spacing: 0.2px;
            transition: background .22s ease, color .22s ease, transform .22s ease, box-shadow .22s ease;
        }
        .topbar.is-scrolled .nav a {
            color: #f5f5f5 !important;
        }
        .navbar a:hover,
        .nav a:hover {
            background: transparent;
            color: #f1c876 !important;
            transform: translateY(-1px);
            box-shadow: none;
        }
        .btn {
            display: inline-block;
            border: 0;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #1a120b;
            padding: 10px 14px;
            border-radius: 10px;
            text-decoration: none;
            cursor: pointer;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            font-weight: 600;
            letter-spacing: 0.2px;
            transition: transform .24s ease, box-shadow .24s ease, filter .24s ease;
            box-shadow: 0 8px 18px rgba(176, 58, 46, 0.26);
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(176, 58, 46, 0.3);
            filter: brightness(1.03);
        }
        .btn:active {
            transform: scale(0.97);
            box-shadow: 0 4px 12px rgba(176, 58, 46, 0.2);
        }
        .btn-secondary {
            background: linear-gradient(180deg, #342318, #2a1c13);
            color: #f5f5f5;
            box-shadow: 0 8px 18px rgba(19, 18, 16, 0.2);
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 6px;
        }
        .section {
            padding: 104px 0 42px;
        }
        body.menu-page .section {
            padding-bottom: 0;
        }
        .section.section-home {
            padding-top: 0;
        }
        .card {
            background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.92));
            border: 1px solid rgba(241, 200, 118, 0.16);
            border-radius: 20px;
            padding: 26px;
            box-shadow: 0 12px 30px rgba(20, 14, 5, .18);
            transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 32px rgba(20, 14, 5, 0.24);
            border-color: rgba(241, 200, 118, 0.28);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
        }
        .stack {
            display: grid;
            gap: 18px;
        }
        .page-header {
            margin-bottom: 26px;
            max-width: 74ch;
        }
        .page-kicker {
            margin: 0 0 10px;
            color: #f1c876;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .page-lead {
            margin: 0;
            color: #cfcfcf;
            font-size: 1.02rem;
            line-height: 1.85;
            max-width: 64ch;
        }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: 700; margin-bottom: 6px; }
        input, textarea, select {
            width: 100%;
            border: 1px solid rgba(241, 200, 118, 0.18);
            border-radius: 10px;
            padding: 11px 12px;
            background: rgba(255, 255, 255, 0.04);
            color: #f5f5f5;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            font-size: 0.98rem;
            transition: border-color .22s ease, box-shadow .22s ease, background .22s ease;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: rgba(255, 123, 50, 0.8);
            box-shadow: 0 0 0 3px rgba(255, 123, 50, 0.16);
            background: rgba(255, 255, 255, 0.06);
        }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            text-align: left;
            border-bottom: 1px solid var(--line);
            padding: 11px 8px;
            vertical-align: top;
        }
        th {
            color: #f1c876;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
            margin-bottom: 20px;
            margin: 15px 0;
            border-radius: 10px;
            padding: 10px 12px;
        }
        .alert-success { background: rgba(255, 123, 50, 0.08); border: 1px solid rgba(241, 200, 118, 0.18); color: #f5f5f5; }
        .alert-error { background: rgba(255, 123, 50, 0.12); border: 1px solid rgba(255, 123, 50, 0.22); color: #f5f5f5; }
        .ux-reveal {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity .34s ease, transform .34s ease;
            will-change: opacity, transform;
        }
        .ux-reveal.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .site-footer {
            margin-top: 44px;
            background: linear-gradient(180deg, #1a120b, #120903);
            color: #cfcfcf;
            border-top: 1px solid rgba(241, 200, 118, 0.16);
        }
        .main-content {
            min-height: 70vh;
            padding-top: 70px;
            padding-bottom: 40px;
        }
        .footer {
            margin-top: 40px;
        }
        body.menu-page .site-footer {
            margin-top: 40px;
        }
        .site-footer-inner {
            width: min(1100px, 92%);
            margin: 0 auto;
            padding: 48px 0 28px;
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 30px;
        }
        .footer-brand {
            margin: 0;
            color: #f5f5f5;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(1.55rem, 3vw, 2.15rem);
            line-height: 1.08;
        }
        .footer-tagline {
            margin: 12px 0 0;
            color: #cfcfcf;
            max-width: 34ch;
            line-height: 1.8;
        }
        .footer-title {
            margin: 0 0 12px;
            color: #f1c876;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.18rem;
        }
        .footer-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 10px;
        }
        .footer-list li,
        .footer-list a {
            color: #cfcfcf;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .footer-list a {
            display: inline-block;
            transition: color .22s ease, transform .22s ease;
        }
        .footer-list a:hover {
            color: #f1c876;
            transform: translateX(2px);
        }
        .hours-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            color: #cfcfcf;
            font-size: 0.95rem;
        }
        .site-footer-bottom {
            width: min(1100px, 92%);
            margin: 0 auto;
            padding: 16px 0 22px;
            border-top: 1px solid rgba(233, 188, 117, 0.16);
            color: #cfcfcf;
            font-size: 0.88rem;
            letter-spacing: 0.18px;
        }
        @media (max-width: 900px) {
            .site-footer-inner {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            }
        }
        @media (max-width: 760px) {
            .topbar {
                padding: 12px 0;
            }
            .topbar-inner {
                row-gap: 10px;
            }
            .brand {
                font-size: 1.08rem;
            }
            .nav {
                gap: 8px;
            }
            .nav a {
                padding: 5px 8px;
                font-size: 0.88rem;
            }
            .section {
                padding-top: 122px;
                padding-bottom: 34px;
            }
            .section.section-home {
                padding-top: 0;
            }
            .site-footer-inner {
                grid-template-columns: 1fr;
                gap: 22px;
                padding: 32px 0 18px;
            }
            .site-footer-bottom {
                padding-bottom: 16px;
            }
        }

        @media (max-width: 768px) {
            .hero {
                height: 70vh;
            }

            .hero-content h1 {
                font-size: 32px;
            }

            .menu-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                height: 60px;
                font-size: 14px;
            }

            .main-content {
                padding-top: 60px;
            }

            .cta-btn {
                width: 100%;
                text-align: center;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            .ux-reveal,
            .ux-reveal.is-visible {
                opacity: 1;
                transform: none;
                filter: none;
                transition: none;
            }
        }
    </style>
</head>
@php
    $isHome = request()->routeIs('home');
    $isMenu = request()->routeIs('menu');
@endphp

<body class="{{ $isMenu ? 'menu-page' : '' }}">
<header class="topbar{{ $isHome ? ' topbar--overlay' : ' is-scrolled' }}" id="site-topbar">
    <div class="container topbar-inner">
        <a class="brand" href="{{ route('home') }}">Steam & Spice</a>
        <button class="hamburger" id="nav-toggle" aria-label="Toggle menu" aria-expanded="false">
            <span class="bar"></span>
        </button>
        <nav class="nav">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('menu') }}">Menu</a>
            <a href="{{ route('updates') }}">Updates</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('ordering') }}">Order Online</a>
            @if(count(session('cart', [])) > 0)
                <a id="site-cart-count" href="{{ route('cart.index') }}">Cart (<span id="site-cart-count-value" aria-live="polite">{{ count(session('cart', [])) }}</span>)</a>
            @endif
            {{-- Show Admin only to authenticated admin users. --}}
            @if(Auth::check() && Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endif
        </nav>
    </div>
</header>
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-error">
            <ul style="margin: 0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            try {
                var toggle = document.getElementById('nav-toggle');
                var topbar = document.getElementById('site-topbar');
                var nav = topbar && topbar.querySelector('.nav');

                if (!toggle || !topbar) return;

                toggle.addEventListener('click', function () {
                    var isOpen = topbar.classList.toggle('nav-open');
                    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });

                // Close menu when a nav link is clicked (mobile)
                if (nav) {
                    nav.addEventListener('click', function (e) {
                        if (e.target && e.target.tagName === 'A' && topbar.classList.contains('nav-open')) {
                            topbar.classList.remove('nav-open');
                            toggle.setAttribute('aria-expanded', 'false');
                        }
                    });
                }
            } catch (err) {
                console.error('Navigation init error:', err);
            }
        });
    </script>

    <main class="main-content">
        @yield('content')
</main>
<footer class="site-footer footer">
    <div class="site-footer-inner">
        <section>
            <h2 class="footer-brand">Steam & Spice</h2>
            <p class="footer-tagline">A modern Nepali fusion kitchen in London, serving handcrafted dishes, vibrant street food, and warm hospitality.</p>
        </section>

        <section>
            <h3 class="footer-title">Contact</h3>
            <ul class="footer-list">
                <li>{{ $siteSettings->address ?? '221B Baker Street, London, UK' }}</li>
                <li><a href="tel:{{ $siteSettings->phone ?? '+44 20 1234 5678' }}">{{ $siteSettings->phone ?? '+44 20 1234 5678' }}</a></li>
                <li><a href="mailto:{{ $siteSettings->email ?? 'hello@steamandspice.co.uk' }}">{{ $siteSettings->email ?? 'hello@steamandspice.co.uk' }}</a></li>
            </ul>
        </section>

        <section>
            <h3 class="footer-title">Opening Hours</h3>
            <ul class="footer-list">
                <li class="hours-row"><span>Mon - Thu</span><span>12:00 - 22:30</span></li>
                <li class="hours-row"><span>Fri - Sat</span><span>12:00 - 23:30</span></li>
                <li class="hours-row"><span>Sunday</span><span>12:00 - 21:30</span></li>
            </ul>
        </section>

        <section>
            <h3 class="footer-title">Quick Links</h3>
            <ul class="footer-list">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('menu') }}">Menu</a></li>
                <li><a href="{{ route('updates') }}">Updates</a></li>
                <li><a href="{{ route('ordering') }}">Order Online</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </section>
    </div>
    <div class="site-footer-bottom">
        Steam & Spice, London. Crafted with flavor.
    </div>
</footer>
<script>
(() => {
    const topbar = document.getElementById('site-topbar');
    if (!topbar) {
        return;
    }

    const updateTopbar = () => {
        if (window.scrollY > 24) {
            topbar.classList.add('is-scrolled');
        } else {
            topbar.classList.remove('is-scrolled');
        }
    };

    updateTopbar();

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(() => {
            updateTopbar();
            ticking = false;
        });
    }, { passive: true });
})();
</script>
<script>
(() => {
    const revealElements = document.querySelectorAll('.reveal');

    if (!revealElements.length) {
        return;
    }

    const revealOnScroll = () => {
        revealElements.forEach((el) => {
            const top = el.getBoundingClientRect().top;
            if (top < window.innerHeight - 100) {
                el.classList.add('active');
            }
        });
    };

    revealOnScroll();
    window.addEventListener('scroll', revealOnScroll, { passive: true });
})();
</script>
</body>
</html>
