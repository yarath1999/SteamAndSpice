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
    body {
        background: linear-gradient(180deg, #1a120b 0%, #22170f 48%, #2b1d14 100%);
        color: #f5f5f5;
    }

    .section {
        padding-bottom: 0;
    }

    .site-footer {
        margin-top: 0;
    }

    .menu-shell {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        background:
            radial-gradient(circle at top left, rgba(255, 123, 50, 0.12), transparent 30%),
            radial-gradient(circle at top right, rgba(241, 200, 118, 0.09), transparent 26%),
            linear-gradient(180deg, #1a120b 0%, #2b1d14 54%, #1a120b 100%);
        color: #f5f5f5;
        padding: 42px 0 0;
        overflow: hidden;
    }

    .menu-wrap {
        width: min(1120px, 92%);
        margin: 0 auto;
        padding-bottom: 0;
    }

    .menu-header {
        max-width: 68ch;
        margin-bottom: 28px;
    }

    .menu-kicker {
        margin: 0 0 14px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #f1c876;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .menu-title {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2.6rem, 5.8vw, 4.2rem);
        line-height: 1.18;
        color: #f5f5f5;
        letter-spacing: -0.5px;
        font-weight: 700;
    }

    .menu-lead {
        margin: 20px 0 0;
        color: #cfcfcf;
        font-size: clamp(0.95rem, 1.8vw, 1.05rem);
        line-height: 1.75;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .menu-tabs {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        padding: 6px 0 12px;
        margin: 10px 0 24px;
        -webkit-overflow-scrolling: touch;
    }

    .tab {
        flex: 0 0 auto;
        border: 0;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        color: #f5f5f5;
        padding: 10px 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background .22s ease, color .22s ease, transform .22s ease;
        white-space: nowrap;
    }

    .tab:hover,
    .tab.is-active {
        background: #FF7B32;
        color: #1a120b;
        transform: translateY(-1px);
    }

    .menu-section {
        display: none;
        margin-top: 24px;
        padding: 28px;
        border-radius: 22px;
        border: 1px solid rgba(241, 200, 118, 0.18);
        background: linear-gradient(180deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.92));
        box-shadow: 0 16px 34px rgba(0, 0, 0, 0.28);
    }

    @media (max-width: 768px) {
        .menu-section {
            padding: 20px;
        }
    }

    .menu-section.is-active {
        display: block;
    }

    .menu-section h2 {
        margin: 0 0 24px;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1.6rem, 3.4vw, 2.3rem);
        color: #f5f5f5;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 28px;
        align-items: stretch;
    }

    @media (max-width: 768px) {
        .menu-grid {
            gap: 20px;
        }
    }

    @media (max-width: 480px) {
        .menu-grid {
            gap: 16px;
        }
    }

    .menu-card {
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.92));
        transition: all 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }

    .menu-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.3), 0 0 20px rgba(255, 123, 50, 0.12);
    }

    .menu-media {
        position: relative;
        overflow: hidden;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
        height: 256px;
        background: linear-gradient(180deg, rgba(255, 123, 50, 0.06), rgba(241, 200, 118, 0.04));
    }

    .menu-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        transition: transform 0.35s ease;
    }

    .menu-media__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.22) 60%);
        pointer-events: none;
    }

    .menu-card:hover .menu-media img {
        transform: scale(1.04);
    }

    .menu-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        color: #f5f5f5;
        flex: 1;
    }

    /* Push the whole form block to the card bottom so the Add button aligns across cards */
    .menu-content > form {
        margin-top: auto;
        width: 100%;
    }

    .item-name {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1rem, 2.2vw, 1.2rem);
        font-weight: 700;
        color: #f5f5f5;
        letter-spacing: -0.2px;
    }

    .menu-price {
        color: #FF7B32;
        font-size: clamp(1rem, 2vw, 1.15rem);
        font-weight: 700;
        margin-top: 8px;
        font-family: 'Playfair Display', Georgia, serif;
    }

    .menu-desc {
        margin: 16px 0 0;
        color: #cfcfcf;
        line-height: 1.72;
        font-size: 0.95rem;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .menu-desc.full {
        display: none;
    }

    .menu-card.expanded .menu-desc.full {
        display: block;
    }

    .menu-card.expanded .menu-desc.short {
        display: none;
    }

    .item-meta {
        margin-top: 12px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }

    .badge-hot {
        color: #ffb39b;
        border: 1px solid rgba(255, 123, 50, 0.45);
        background: rgba(255, 123, 50, 0.12);
    }

    .badge-new {
        color: #f6e1a5;
        border: 1px solid rgba(241, 200, 118, 0.45);
        background: rgba(241, 200, 118, 0.12);
    }

    .btn-cta {
        background: #FF7B32;
        color: #1a120b;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 800;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.2s;
        margin-top: auto;
        width: 100%;
        text-align: center;
        display: block;
    }

    .btn-cta:hover {
        filter: brightness(1.08);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 123, 50, 0.3);
    }

    .btn-cta:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .menu-empty {
        color: #cfcfcf;
        padding: 8px 0 2px;
    }

    @media (max-width: 860px) {
        .menu-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }

        .menu-content {
            padding: 14px;
        }
    }

    @media (max-width: 768px) {
        .menu-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .menu-card {
            opacity: 1;
            transform: none;
            transition: none;
        }
    }
</style>

<section class="menu-shell">
    <div class="menu-wrap">
        <header class="menu-header reveal">
            <p class="menu-kicker">Menu</p>
            <h1 class="menu-title">Steam & Spice</h1>
            <p class="menu-lead">Explore our categories below. Each section keeps the structure clean: image on the left, details in the middle, price aligned to the right, with HOT and NEW shown only when the item is flagged.</p>
        </header>

        <div class="menu-tabs reveal" role="tablist" aria-label="Menu categories">
            @forelse($categories as $category)
                <button type="button" class="tab cta-btn {{ $loop->first ? 'is-active' : '' }}" data-target="menu-section-{{ $loop->index }}">
                    {{ $category->name }}
                </button>
            @empty
                <span class="menu-empty">No menu categories have been added yet.</span>
            @endforelse
        </div>

        @forelse($categories as $category)
            @php
                $items = $category->menuItems
                    ->filter(fn ($item) => $item->is_available)
                    ->sortBy('name')
                    ->values();
            @endphp

            <div id="menu-section-{{ $loop->index }}" class="menu-section {{ $loop->first ? 'is-active' : '' }} reveal">
                <h2>{{ $category->name }}</h2>

                <div class="menu-grid">
                    @forelse($items as $item)
                        <article class="menu-card reveal" role="button" tabindex="0" aria-expanded="false" aria-label="{{ $item->name }} - £{{ number_format((float) $item->price, 2) }}. Click to view details and add to cart." data-category="{{ $item->category_id }}" data-name="{{ strtolower($item->name) }}" data-description="{{ strtolower($item->description ?? '') }}" data-price="{{ $item->price }}">
                            <div class="menu-media">
                                <img src="{{ $storageImageUrl($item->image_path) }}" alt="{{ $item->name }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';">
                                <div class="menu-media__overlay" aria-hidden="true"></div>
                            </div>

                            <div class="menu-content">
                                <h3 class="item-name">{{ $item->name }}</h3>
                                <p class="menu-price">£{{ number_format((float) $item->price, 2) }}</p>
                                @if($item->description)
                                    <p class="menu-desc short">
                                        {{ \Illuminate\Support\Str::limit($item->description, 60) }}
                                    </p>

                                    <p class="menu-desc full">
                                        {{ $item->description }}
                                    </p>
                                @endif
                                <div class="item-meta">
                                    @if($item->is_hot)
                                        <span class="badge badge-hot">Hot</span>
                                    @endif
                                    @if($item->is_new)
                                        <span class="badge badge-new">New</span>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('cart.add') }}">
                                    @csrf
                                    <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="btn-cta cta-btn" type="submit" aria-label="Add {{ $item->name }} to cart">Add</button>
                                </form>
                            </div>
                        </article>
                        @empty
                        <div class="menu-empty">No available items in this category yet.</div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="menu-empty reveal">No menu categories have been added yet.</div>
        @endforelse
    </div>
</section>

<script>
(() => {
    // ============= Tab Navigation =============
    const tabs = document.querySelectorAll('.tab');
    const sections = document.querySelectorAll('.menu-section');

    if (tabs.length && sections.length) {
        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const targetId = tab.getAttribute('data-target');

                tabs.forEach((button) => button.classList.remove('is-active'));
                sections.forEach((section) => section.classList.remove('is-active'));

                tab.classList.add('is-active');

                const target = document.getElementById(targetId);
                if (target) {
                    target.classList.add('is-active');
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    // ============= Menu Card Toggle & Accessibility =============
    const menuCards = document.querySelectorAll('.menu-card');

    menuCards.forEach((card) => {
        const handleToggle = () => {
            card.classList.toggle('expanded');
            const isExpanded = card.classList.contains('expanded');
            card.setAttribute('aria-expanded', isExpanded);
        };

        card.addEventListener('click', (event) => {
            if (event.target.closest('button, form, input, label, a')) {
                return;
            }
            handleToggle();
        });

        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                if (event.target.closest('button, form, input, label, a')) {
                    return;
                }
                handleToggle();
            }
        });

        // ============= AJAX Form Submission =============
        const form = card.querySelector('form');
        if (form) {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.textContent;
                const itemName = card.querySelector('.item-name')?.textContent || 'Item';

                try {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Adding...';

                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        showToast(`✓ ${itemName} added to cart!`, 'success');
                        // Update header cart count if available
                        try {
                            if (typeof data.cartCount !== 'undefined') {
                                let cartLink = document.getElementById('site-cart-count');
                                const updateCartText = (el, count) => {
                                    const valueSpan = el.querySelector('#site-cart-count-value');
                                    if (valueSpan) {
                                        valueSpan.textContent = count;
                                    } else {
                                        el.textContent = `Cart (${count})`;
                                    }
                                };

                                if (cartLink) {
                                    updateCartText(cartLink, data.cartCount);
                                } else if (data.cartCount > 0) {
                                    const nav = document.querySelector('#site-topbar .nav');
                                    if (nav) {
                                        const a = document.createElement('a');
                                        a.id = 'site-cart-count';
                                        a.href = '/cart';
                                        const span = document.createElement('span');
                                        span.id = 'site-cart-count-value';
                                        span.setAttribute('aria-live', 'polite');
                                        span.textContent = data.cartCount;
                                        a.appendChild(document.createTextNode('Cart ('));
                                        a.appendChild(span);
                                        a.appendChild(document.createTextNode(')'));
                                        nav.appendChild(a);
                                    }
                                }
                            }
                        } catch (e) { /* ignore */ }
                    } else {
                        showToast(data.message || 'Error adding item to cart', 'error');
                    }
                } catch (error) {
                    console.error('Add to cart error:', error);
                    showToast('Error adding item to cart. Please try again.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            });
        }
    });

    // ============= Toast Notification Function =============
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
            font-family: 'Jost', 'Segoe UI', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Add toast animation styles
    if (!document.querySelector('style[data-toast-animations]')) {
        const style = document.createElement('style');
        style.setAttribute('data-toast-animations', '');
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // ============= Reveal Animation =============
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