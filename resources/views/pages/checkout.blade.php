@extends('layouts.app')

@section('content')
<header class="page-header">
    <p class="page-kicker">Secure Payment</p>
    <h1>Checkout</h1>
    <p class="page-lead">Confirm your details and complete payment via Stripe test checkout.</p>
</header>

<style>
    .checkout-actions {
        display: flex;
        gap: 0.9rem;
        flex-wrap: wrap;
        align-items: center;
        margin-top: 1rem;
    }

    .checkout-back-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 0.85rem 1.25rem;
        border-radius: 999px;
        border: 1px solid rgba(112, 72, 34, 0.2);
        background: rgba(255, 255, 255, 0.75);
        color: #5c3821;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 10px 22px rgba(95, 55, 27, 0.08);
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }

    .checkout-back-link:hover {
        transform: translateY(-1px);
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0 14px 28px rgba(95, 55, 27, 0.12);
    }

    .checkout-submit {
        min-height: 48px;
        padding: 0.9rem 1.4rem;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: 0.01em;
        border: 0;
        border-radius: 12px;
        width: auto;
        flex: 1 1 240px;
        background: #FF7B32;
        color: #1a120b;
        box-shadow: 0 6px 15px rgba(255, 123, 50, 0.3);
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        cursor: pointer;
    }

    .checkout-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(255, 123, 50, 0.4);
        filter: brightness(1.05);
    }

    .checkout-submit:active {
        transform: scale(0.97);
    }

    @media (max-width: 640px) {
        .checkout-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .checkout-back-link,
        .checkout-submit {
            width: 100%;
        }
    }
</style>

<div class="grid" style="align-items: start;">
    <section class="card">
        <h2>Customer Details</h2>
        <form method="POST" action="{{ route('checkout') }}">
            @csrf
            <div class="form-group">
                <label for="customer_name">Name</label>
                <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
            </div>
            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
            </div>
            <p class="muted" style="font-size: .95rem;">You will be redirected to Stripe Checkout in test mode (card payments only) to complete payment securely.</p>
            <div class="checkout-actions">
                <a class="checkout-back-link" href="{{ route('cart.index') }}">Back to Cart</a>
                <button class="checkout-submit" type="submit">Pay on Counter/ Delivery</button>
            </div>
        </form>
    </section>
    <section class="card">
        <h2>Order Summary</h2>
        <ul style="padding-left: 18px; line-height: 1.75;">
            @foreach($cart as $item)
                <li>{{ $item['name'] }} x {{ $item['quantity'] }} = GBP {{ number_format($item['price'] * $item['quantity'], 2) }}</li>
            @endforeach
        </ul>
        <p><strong>Total: GBP {{ number_format($total, 2) }}</strong></p>
    </section>
</div>
@endsection
