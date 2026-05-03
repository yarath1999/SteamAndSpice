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
<header class="page-header reveal">
    <p class="page-kicker">Takeaway & Delivery</p>
    <h1>Order From The Full Kitchen</h1>
    <p class="page-lead">Choose from momos, noodles, street-food favorites, and house specials prepared fresh for online orders.</p>
</header>

<!-- Search & Filter Bar -->
<div class="filters-bar reveal">
    <div class="filter-group">
        <label for="search-input">Search</label>
        <input id="search-input" type="text" placeholder="Search items..." aria-label="Search menu items by name or description">
    </div>

    @php
        $categories = $menuItems->pluck('category')->unique('id')->values();
    @endphp
    <div class="filter-group">
        <label for="category-filter">Category</label>
        <select id="category-filter" aria-label="Filter items by category">
            <option value="">All Categories</option>
            @forelse($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @empty
            @endforelse
        </select>
    </div>

    <div class="filter-group">
        <label for="sort-filter">Sort</label>
        <select id="sort-filter" aria-label="Sort items by criteria">
            <option value="name-asc">Name (A-Z)</option>
            <option value="name-desc">Name (Z-A)</option>
            <option value="price-asc">Price (Low to High)</option>
            <option value="price-desc">Price (High to Low)</option>
        </select>
    </div>

    <div class="filter-actions">
        <button class="filter-btn" id="reset-filters" aria-label="Reset all filters">Reset</button>
    </div>
</div>

<style>
    :root{
        --accent:#ff7b32;
        --accent-2:#f1c876;
        --panel:rgba(43,29,20,0.94);
        --muted:#cfcfcf;
    }
    
    .filters-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 32px;
        margin-bottom: 32px;
        padding: 20px;
        background: rgba(43,29,20,0.4);
        border-radius: 12px;
        align-items: center;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 0.8rem;
        color: #999;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .filter-group input,
    .filter-group select {
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid rgba(241,200,118,0.18);
        background: rgba(255,255,255,0.04);
        color: #f5f5f5;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        font-size: 0.9rem;
        min-width: 160px;
    }

    .filter-group select option {
        background: #1a120b;
        color: #f5f5f5;
    }

    .filter-group input::placeholder {
        color: #666;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--accent-2);
        box-shadow: 0 0 0 2px rgba(241,200,118,0.1);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }

    .filter-btn {
        background: transparent;
        border: 1px solid rgba(241,200,118,0.18);
        color: var(--accent-2);
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .filter-btn:hover {
        background: rgba(241,200,118,0.1);
        border-color: var(--accent-2);
    }

    .no-results {
        text-align: center;
        padding: 48px 24px;
        color: #999;
    }

    .no-results h3 {
        color: #cfcfcf;
        margin-bottom: 12px;
    }

    @media (max-width: 768px) {
        .filters-bar {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-group input,
        .filter-group select {
            min-width: auto;
            width: 100%;
        }
        .filter-actions {
            margin-left: 0;
            justify-content: stretch;
        }
        .filter-btn {
            flex: 1;
        }
    }
    .order-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 28px;
        align-items: stretch;
        margin-top: 32px;
    }

    @media (max-width: 768px) {
        .order-grid {
            gap: 20px;
            margin-top: 24px;
        }
    }

    @media (max-width: 480px) {
        .order-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    .order-card {
        background: linear-gradient(165deg, rgba(43,29,20,0.96), rgba(26,18,11,0.92));
        border-radius: 16px;
        overflow: hidden;
        transition: 0.3s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.22);
        display: flex;
        flex-direction: column;
        color: #f5f5f5;
        cursor: pointer;
        will-change: transform, opacity;
    }

    .order-card:hover{
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.3), 0 0 24px rgba(255, 123, 50, 0.12);
    }

    .menu-img {
        width: 100%;
        height: 256px;
        object-fit: cover;
        object-position: center;
        display: block;
        transform: translateZ(0);
        transition: transform 0.35s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .order-card:hover .menu-img {
        transform: scale(1.04);
    }

    .menu-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    /* Push the meta/form section to the bottom so Add buttons align across cards */
    .menu-content .meta {
        margin-top: auto;
    }

    .order-card h3{
        margin:0; 
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(1rem, 2.2vw, 1.15rem); 
        color:#f5f5f5;
        font-weight: 700;
        letter-spacing: -0.2px;
    }
    .order-card .muted{
        color:#cfcfcf; 
        margin:0; 
        font-size: 0.85rem;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .menu-price{
        color:#f1c876; 
        font-weight:700; 
        margin:10px 0 0;
        font-size: 1.1rem;
        font-family: 'Playfair Display', Georgia, serif;
    }
    .menu-desc{
        margin: 10px 0 0;
        color:#cfcfcf; 
        font-size: 0.93rem; 
        line-height:1.7;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .menu-desc.full {
        display: none;
    }

    .order-card.expanded .menu-desc.full { display: block; }
    .order-card.expanded .menu-desc.short { display: none; }

    .meta{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-top: 8px;
    }
    .price{color:var(--accent); font-weight:700}

    .form-row{
        display:flex;
        align-items:center;
        gap:8px;
        margin-top:0;
        flex-wrap:wrap;
        justify-content:flex-end;
    }
    .form-row label{
        font-size:0.85rem;
        color:#999;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }
    input[type="number"]{
        appearance:none;
        -moz-appearance:textfield;
        -webkit-appearance:none;
        padding:6px 8px;
        border-radius:8px;
        border:1px solid rgba(241,200,118,0.18);
        background:rgba(255,255,255,0.04);
        color:#f5f5f5;
        width:64px;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        text-align: center;
    }

    .qty-stepper {
        display: inline-flex;
        align-items: center;
        border: 1px solid rgba(241,200,118,0.18);
        border-radius: 8px;
        background: rgba(255,255,255,0.04);
        overflow: hidden;
    }

    .qty-btn {
        background: transparent;
        border: none;
        color: #f1c876;
        width: 32px;
        height: 32px;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        padding: 0;
    }

    .qty-btn:hover:not(:disabled) {
        background: rgba(241,200,118,0.1);
        transform: scale(1.05);
    }

    .qty-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .qty-display {
        min-width: 50px;
        text-align: center;
        color: #f5f5f5;
        font-weight: 600;
        font-size: 0.95rem;
    }

    input[type="number"].qty-input {
        display: none;
    }

    .btn{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:linear-gradient(90deg,var(--accent),var(--accent-2));
        color:#1a120b;
        padding:10px 14px;
        border-radius:10px;
        border:0;
        font-weight:700;
        cursor:pointer;
        white-space:nowrap;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }
    .order-card .btn.cta-btn {
        margin-top: auto;
        width: 100%;
        display: block;
        text-align: center;
    }
    .cta-btn{width:100%; border-radius:10px;}
    .btn:hover{filter:brightness(1.04);transform:translateY(-2px)}

    @media (max-width:768px){.order-grid{grid-template-columns:1fr}}
</style>

<div class="order-grid" id="menu-grid">
    @forelse($menuItems as $item)
        <article class="order-card menu-card reveal" role="button" tabindex="0" aria-expanded="false" aria-label="{{ $item->name }} - GBP {{ number_format((float) $item->price, 2) }}. Click to view details and add to cart." data-category="{{ $item->category->id }}" data-name="{{ strtolower($item->name) }}" data-description="{{ strtolower($item->description ?? '') }}" data-price="{{ $item->price }}">
            <img class="menu-img" src="{{ $storageImageUrl($item->image_path) }}" alt="{{ $item->name }}" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='{{ $fallbackImage }}';"> 

            <div class="menu-content">
                <div>
                    <h3>{{ $item->name }}</h3>
                    <p class="muted" style="margin-top:4px;margin-bottom:0">{{ $item->category->name }}</p>
                </div>

                <p class="menu-price">GBP {{ number_format((float) $item->price, 2) }}</p>
                @if($item->description)
                    <p class="menu-desc short">
                        {{ \Illuminate\Support\Str::limit($item->description, 60) }}
                    </p>

                    <p class="menu-desc full">
                        {{ $item->description }}
                    </p>
                @else
                    <p class="menu-desc short">Freshly prepared with bold Nepali fusion flavors.</p>
                @endif

                <div class="meta">
                    <form method="POST" action="{{ route('cart.add') }}" style="margin:0; width:100%;">
                        @csrf
                        <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                        <div class="form-row" style="justify-content:stretch;">
                            <label for="qty-display-{{ $item->id }}">Qty</label>
                            <div class="qty-stepper" role="spinbutton" aria-valuenow="1" aria-valuemin="1" aria-valuemax="99" aria-label="Quantity for {{ $item->name }}">
                                <button class="qty-btn" data-action="decrease" aria-label="Decrease quantity" aria-controls="qty-display-{{ $item->id }}">−</button>
                                <span class="qty-display" id="qty-display-{{ $item->id }}">1</span>
                                <button class="qty-btn" data-action="increase" aria-label="Increase quantity" aria-controls="qty-display-{{ $item->id }}">+</button>
                            </div>
                            <input id="qty-{{ $item->id }}" class="qty-input" type="number" name="quantity" min="1" value="1" required aria-label="Quantity">
                            <button class="btn cta-btn" type="submit" aria-label="Add {{ $item->name }} to cart">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </article>
    @empty
        <p>No menu items available for ordering yet.</p>
    @endforelse
</div>

<div class="no-results" id="no-results-message" style="display:none;">
    <h3>No items found</h3>
    <p>Try adjusting your search or filters</p>
</div>

<script>
(() => {
    // ============= Filter & Search Logic =============
    const searchInput = document.getElementById('search-input');
    const categoryFilter = document.getElementById('category-filter');
    const sortFilter = document.getElementById('sort-filter');
    const resetFiltersBtn = document.getElementById('reset-filters');
    const menuGrid = document.getElementById('menu-grid');
    const noResultsMsg = document.getElementById('no-results-message');
    const allCards = Array.from(document.querySelectorAll('.menu-card'));

    function applyFilters() {
        const searchTerm = (searchInput.value || '').toLowerCase().trim();
        const selectedCategory = categoryFilter.value;
        const sortBy = sortFilter.value;

        // Filter cards
        let visibleCards = allCards.filter((card) => {
            const matchesSearch = !searchTerm || 
                card.dataset.name.includes(searchTerm) || 
                card.dataset.description.includes(searchTerm);
            const matchesCategory = !selectedCategory || card.dataset.category === selectedCategory;
            return matchesSearch && matchesCategory;
        });

        // Sort cards
        if (sortBy === 'name-asc') {
            visibleCards.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
        } else if (sortBy === 'name-desc') {
            visibleCards.sort((a, b) => b.dataset.name.localeCompare(a.dataset.name));
        } else if (sortBy === 'price-asc') {
            visibleCards.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
        } else if (sortBy === 'price-desc') {
            visibleCards.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
        }

        // Update DOM
        allCards.forEach((card) => {
            card.style.display = visibleCards.includes(card) ? '' : 'none';
        });

        // Show/hide no-results message
        noResultsMsg.style.display = visibleCards.length === 0 ? 'block' : 'none';

        // Reorder grid
        visibleCards.forEach((card) => {
            menuGrid.appendChild(card);
        });
    }

    function resetFilters() {
        searchInput.value = '';
        categoryFilter.value = '';
        sortFilter.value = 'name-asc';
        applyFilters();
    }

    // Event listeners
    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
    sortFilter.addEventListener('change', applyFilters);
    resetFiltersBtn.addEventListener('click', resetFilters);

    // ============= Menu Cards Logic =============
    const cards = document.querySelectorAll('.menu-card');

    cards.forEach((card) => {
        const handleToggle = () => {
            card.classList.toggle('expanded');
            const isExpanded = card.classList.contains('expanded');
            card.setAttribute('aria-expanded', isExpanded);
            // Force a small repaint/reflow to avoid compositor glitch
            try {
                card.style.willChange = 'transform,opacity';
                void card.offsetWidth;
                setTimeout(() => { card.style.willChange = 'auto'; }, 100);
            } catch (e) { /* ignore */ }
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

        // Quantity stepper handler
        const qtyBtns = card.querySelectorAll('.qty-btn');
        qtyBtns.forEach((btn) => {
            btn.addEventListener('click', (event) => {
                event.preventDefault();
                const stepper = btn.closest('.qty-stepper');
                const display = stepper.querySelector('.qty-display');
                const form = card.querySelector('form');
                const input = form.querySelector('.qty-input');

                let currentQty = parseInt(input.value, 10) || 1;
                const action = btn.dataset.action;

                if (action === 'increase' && currentQty < 99) {
                    currentQty++;
                } else if (action === 'decrease' && currentQty > 1) {
                    currentQty--;
                }

                input.value = currentQty;
                display.textContent = currentQty;
                stepper.setAttribute('aria-valuenow', currentQty);

                // Update decrease button disabled state
                const decreaseBtn = stepper.querySelector('[data-action="decrease"]');
                decreaseBtn.disabled = currentQty <= 1;
            });
        });

        // Initialize decrease button disabled state
        const initialDecrease = card.querySelector('[data-action="decrease"]');
        if (initialDecrease) {
            initialDecrease.disabled = true;
        }

        // AJAX form submission handler
        const form = card.querySelector('form');
        if (form) {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.textContent;

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
                        showToast(`✓ ${data.item.name} added to cart!`, 'success');
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
                        // Reset quantity stepper
                        const stepper = card.querySelector('.qty-stepper');
                        const display = stepper.querySelector('.qty-display');
                        const input = form.querySelector('.qty-input');
                        input.value = 1;
                        display.textContent = 1;
                        stepper.setAttribute('aria-valuenow', 1);
                        initialDecrease.disabled = true;
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

    // Toast notification function
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
})();
</script>
@endsection
