@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(180deg, #1a120b 0%, #22170f 48%, #2b1d14 100%);
        color: #f5f5f5;
    }

    .site-footer {
        margin-top: 40px;
    }

    .cart-shell {
        width: 100vw;
        margin-left: calc(50% - 50vw);
        margin-right: calc(50% - 50vw);
        background: linear-gradient(180deg, #1a120b 0%, #22170f 48%, #2b1d14 100%);
        color: #f5f5f5;
        padding: 42px 0;
        overflow: hidden;
    }

    .cart-wrap {
        width: min(1100px, 92%);
        margin: 0 auto;
    }

    .cart-header {
        max-width: 68ch;
        margin-bottom: 28px;
    }

    .cart-kicker {
        margin: 0 0 14px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 0.8rem;
        font-weight: 700;
        color: #f1c876;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .cart-title {
        margin: 0;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: clamp(2rem, 5vw, 3rem);
        line-height: 1.18;
        color: #f5f5f5;
        letter-spacing: -0.5px;
        font-weight: 700;
    }

    .cart-lead {
        margin: 20px 0 0;
        color: #cfcfcf;
        font-size: clamp(0.95rem, 1.8vw, 1.05rem);
        line-height: 1.75;
        font-family: 'Jost', 'Segoe UI', sans-serif;
    }

    .cart-empty {
        color: #cfcfcf;
        padding: 8px 0;
        font-size: 1.05rem;
    }

    .cart-card {
        background: linear-gradient(165deg, rgba(43, 29, 20, 0.96), rgba(26, 18, 11, 0.92));
        border: 1px solid rgba(241, 200, 118, 0.16);
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 12px 30px rgba(20, 14, 5, .18);
        transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
    }

    .cart-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 32px rgba(20, 14, 5, 0.24);
        border-color: rgba(241, 200, 118, 0.28);
    }

    .cart-card table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-card th,
    .cart-card td {
        text-align: left;
        border-bottom: 1px solid rgba(241, 200, 118, 0.16);
        padding: 14px 12px;
        vertical-align: middle;
        color: #f5f5f5;
        font-size: 0.95rem;
    }

    .cart-card th {
        color: #f1c876;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        background: rgba(255, 255, 255, 0.02);
    }

    .cart-card th:last-child,
    .cart-card td:last-child {
        text-align: right;
    }

    .cart-card input[type="number"] {
        width: 70px;
        border: 1px solid rgba(241, 200, 118, 0.18);
        border-radius: 8px;
        padding: 6px 8px;
        background: rgba(255, 255, 255, 0.04);
        color: #f5f5f5;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        text-align: center;
    }

    .cart-card input[type="number"]:focus {
        outline: none;
        border-color: rgba(255, 123, 50, 0.8);
        box-shadow: 0 0 0 3px rgba(255, 123, 50, 0.16);
        background: rgba(255, 255, 255, 0.06);
    }

    .cart-totals {
        display: flex;
        justify-content: flex-end;
        gap: 40px;
        align-items: center;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(241, 200, 118, 0.16);
    }

    .cart-total-label {
        color: #cfcfcf;
        font-size: 0.95rem;
    }

    .cart-total-value {
        color: #f1c876;
        font-weight: 700;
        font-size: 1.35rem;
        font-family: 'Playfair Display', Georgia, serif;
    }

    .cart-actions {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .cart-actions a,
    .cart-actions button {
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-family: 'Jost', 'Segoe UI', sans-serif;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
        display: inline-block;
    }

    .checkout-btn {
        background: linear-gradient(90deg, #ff7b32, #f1c876);
        color: #1a120b;
        box-shadow: 0 6px 15px rgba(255, 123, 50, 0.3);
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(255, 123, 50, 0.4);
        filter: brightness(1.05);
    }

    .checkout-btn:active {
        transform: scale(0.97);
    }

    .continue-shopping-btn {
        background: linear-gradient(135deg, #342318, #2a1c13);
        color: #f5f5f5;
        border: 1px solid rgba(241, 200, 118, 0.24);
    }

    .continue-shopping-btn:hover {
        background: linear-gradient(135deg, #3f2920, #342318);
        border-color: rgba(241, 200, 118, 0.4);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .cart-wrap {
            width: 100%;
            padding: 0 12px;
        }

        .cart-shell {
            padding: 28px 0;
        }

        .cart-card {
            padding: 20px;
        }

        .cart-card table {
            font-size: 0.85rem;
        }

        .cart-card th,
        .cart-card td {
            padding: 10px 8px;
        }

        .cart-card input[type="number"] {
            width: 60px;
            font-size: 0.9rem;
        }

        .cart-totals {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
            padding-top: 16px;
            margin-top: 20px;
        }

        .cart-actions {
            width: 100%;
            justify-content: stretch;
            flex-direction: column;
        }

        .cart-actions a,
        .cart-actions button {
            width: 100%;
            text-align: center;
            padding: 14px 16px;
        }

        .cart-title {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
        }
    }

    @media (max-width: 480px) {
        .cart-card table {
            font-size: 0.8rem;
        }

        .cart-card th,
        .cart-card td {
            padding: 8px 6px;
        }

        .cart-card input[type="number"] {
            width: 50px;
            font-size: 0.85rem;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .cart-card {
            transition: none;
        }
    }
</style>

<section class="cart-shell">
    <div class="cart-wrap">
        <header class="cart-header">
            <p class="cart-kicker">Cart Summary</p>
            <h1 class="cart-title">Your Selected Dishes</h1>
            <p class="cart-lead">Review items, adjust quantities, and continue to secure checkout when ready.</p>
        </header>

        @if(empty($cart))
            <p class="cart-empty">Your cart is currently empty.</p>
        @else
            <div class="cart-card">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $item)
                            <tr data-cart-item-id="{{ $item['menu_item_id'] }}">
                                <td>{{ $item['name'] }}</td>
                                <td>GBP {{ number_format($item['price'], 2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.update', $item['menu_item_id']) }}" class="cart-qty-form" style="display:flex; gap:8px; align-items:center;">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" min="1" value="{{ $item['quantity'] }}" aria-label="Quantity for {{ $item['name'] }}" class="cart-qty-input" data-item-id="{{ $item['menu_item_id'] }}">
                                        <span class="cart-qty-status" style="display:none; font-size:0.8rem; color:#f1c876;">Updating...</span>
                                    </form>
                                </td>
                                <td class="cart-line-total" data-item-id="{{ $item['menu_item_id'] }}">GBP {{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.remove', $item['menu_item_id']) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-secondary" type="submit">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="cart-totals">
                    <div>
                        <span class="cart-total-label">Grand Total:</span>
                        <span class="cart-total-value" id="cart-grand-total" aria-live="polite">GBP {{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <div class="cart-actions">
                    <a href="{{ route('menu') }}" class="continue-shopping-btn">Continue Shopping</a>
                    <a href="{{ route('checkout.form') }}" class="checkout-btn">Proceed to Checkout</a>
                </div>
            </div>
        @endif
    </div>
</section>

<script>
(() => {
    // Auto-submit cart quantity forms on change with JSON-first progressive enhancement
    const qtyInputs = document.querySelectorAll('.cart-qty-input');
    const activeRequests = new WeakSet();

    const formatGBP = (amount) => {
        const value = Number(amount || 0);
        return 'GBP ' + value.toLocaleString('en-GB', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    };

    const updateHeaderCartCount = (count) => {
        const nextCount = Number(count || 0);
        const existingLink = document.getElementById('site-cart-count');
        const existingValue = document.getElementById('site-cart-count-value');

        if (nextCount < 1) {
            if (existingLink) {
                existingLink.remove();
            }
            return;
        }

        if (existingValue) {
            existingValue.textContent = String(nextCount);
            return;
        }

        const nav = document.querySelector('.nav');
        if (!nav) {
            return;
        }

        const cartLink = document.createElement('a');
        cartLink.id = 'site-cart-count';
        cartLink.href = '{{ route('cart.index') }}';
        cartLink.setAttribute('aria-label', 'View cart with ' + nextCount + ' items');
        cartLink.innerHTML = 'Cart (<span id="site-cart-count-value" aria-live="polite">' + nextCount + '</span>)';
        nav.appendChild(cartLink);
    };

    const setUpdatingState = (input, statusSpan, isUpdating) => {
        input.disabled = isUpdating;
        if (!statusSpan) {
            return;
        }

        if (isUpdating) {
            statusSpan.style.display = 'inline';
            statusSpan.textContent = 'Updating...';
            statusSpan.style.color = '#f1c876';
        } else {
            statusSpan.style.display = 'none';
            statusSpan.textContent = 'Updating...';
            statusSpan.style.color = '#f1c876';
        }
    };
    
    qtyInputs.forEach((input) => {
        input.addEventListener('focus', () => {
            input.dataset.previousValue = input.value;
        });

        input.addEventListener('change', async (event) => {
            const form = input.closest('.cart-qty-form');
            if (!form) {
                return;
            }

            const statusSpan = form.querySelector('.cart-qty-status');
            const previousValue = input.dataset.previousValue ?? input.defaultValue ?? input.value;
            const currentValue = input.value;
            const itemId = input.dataset.itemId;
            
            if (activeRequests.has(form)) {
                return;
            }
            
            activeRequests.add(form);
            const formData = new FormData(form);
            setUpdatingState(input, statusSpan, true);

            try {
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                
                if (!response.ok) {
                    throw new Error('Request failed with status ' + response.status);
                }

                const contentType = response.headers.get('content-type') || '';
                if (!contentType.includes('application/json')) {
                    window.location.reload();
                    return;
                }

                const payload = await response.json();
                if (!payload.success) {
                    throw new Error(payload.message || 'Unable to update cart item.');
                }

                const lineTotalEl = document.querySelector('.cart-line-total[data-item-id="' + itemId + '"]');
                if (lineTotalEl && payload.item && typeof payload.item.lineTotal !== 'undefined') {
                    lineTotalEl.textContent = formatGBP(payload.item.lineTotal);
                }

                const grandTotalEl = document.getElementById('cart-grand-total');
                if (grandTotalEl && typeof payload.cartTotal !== 'undefined') {
                    grandTotalEl.textContent = formatGBP(payload.cartTotal);
                }

                if (typeof payload.cartCount !== 'undefined') {
                    updateHeaderCartCount(payload.cartCount);
                }

                input.dataset.previousValue = currentValue;
                input.defaultValue = currentValue;
            } catch (error) {
                console.error('Cart update error:', error);
                input.value = previousValue;
            } finally {
                activeRequests.delete(form);
                setUpdatingState(input, statusSpan, false);
            }
        });
    });
})();
</script>

@endsection
