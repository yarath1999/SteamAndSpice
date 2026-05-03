<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        return view('pages.cart', [
            'cart' => $cart,
            'total' => $this->calculateTotal($cart),
        ]);
    }

    public function add(Request $request)
    {
        if (!Schema::hasTable('menu_items')) {
            $response = [
                'success' => false,
                'message' => 'Menu is temporarily unavailable. Please try again later.',
                'error' => 'database_unavailable',
            ];
            return $request->expectsJson() ? response()->json($response, 503) : redirect()->route('ordering')->with('error', $response['message']);
        }

        $validated = $request->validate([
            'menu_item_id' => ['required', 'integer', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $menuItem = MenuItem::query()->findOrFail($validated['menu_item_id']);
        $cart = session('cart', []);

        if (isset($cart[$menuItem->id])) {
            $cart[$menuItem->id]['quantity'] += $validated['quantity'];
        } else {
            $cart[$menuItem->id] = [
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => (float) $menuItem->price,
                'quantity' => $validated['quantity'],
            ];
        }

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$menuItem->name} added to cart.",
                'item' => [
                    'id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'quantity' => $validated['quantity'],
                    'price' => (float) $menuItem->price,
                ],
                'cartCount' => count($cart),
                'cartTotal' => $this->calculateTotal($cart),
            ], 200);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $cart = session('cart', []);
        $updatedItem = null;

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $validated['quantity'];
            $updatedItem = $cart[$id];
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            if (!$updatedItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found.',
                ], 404);
            }

            $lineTotal = ((float) $updatedItem['price']) * ((int) $updatedItem['quantity']);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'item' => [
                    'id' => (int) $updatedItem['menu_item_id'],
                    'quantity' => (int) $updatedItem['quantity'],
                    'price' => (float) $updatedItem['price'],
                    'lineTotal' => $lineTotal,
                ],
                'cartCount' => count($cart),
                'cartTotal' => $this->calculateTotal($cart),
            ], 200);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove(int $id)
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Item removed.');
    }

    public function checkoutForm()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('ordering')->with('error', 'Your cart is empty.');
        }

        return view('pages.checkout', [
            'cart' => $cart,
            'total' => $this->calculateTotal($cart),
        ]);
    }

    public function checkout(Request $request)
    {
        if (!Schema::hasTable('orders') || !Schema::hasTable('order_items')) {
            return redirect()->route('checkout.form')->with('error', 'Checkout is temporarily unavailable. Please try again later.');
        }

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('ordering')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $total = $this->calculateTotal($cart);

        $order = DB::transaction(function () use ($validated, $cart, $total) {
            $order = Order::create([
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'payment_status' => 'pending',
                'total_amount' => $total,
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'menu_item_id' => $item['menu_item_id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'line_total' => $item['price'] * $item['quantity'],
                ]);
            }

            return $order;
        });

        $localCheckoutFallback = function () use ($order) {
            return redirect()->route('checkout.success', [
                'session_id' => 'local_' . $order->id,
            ]);
        };

        $stripeSecret = (string) config('services.stripe.secret');
        if ($stripeSecret === '') {
            if (app()->environment(['local', 'development', 'testing'])) {
                return $localCheckoutFallback();
            }

            $order->items()->delete();
            $order->delete();

            return redirect()->route('checkout.form')->with('error', 'Stripe test keys are missing. Set STRIPE_SECRET in .env.');
        }

        $payload = [
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel') . '?order=' . $order->id,
            'metadata[order_id]' => (string) $order->id,
            'client_reference_id' => (string) $order->id,
            'payment_method_types[0]' => 'card',
        ];

        $index = 0;
        foreach ($cart as $item) {
            $payload["line_items[$index][price_data][currency]"] = 'gbp';
            $payload["line_items[$index][price_data][unit_amount]"] = (int) round(((float) $item['price']) * 100);
            $payload["line_items[$index][price_data][product_data][name]"] = $item['name'];
            $payload["line_items[$index][quantity]"] = (int) $item['quantity'];
            $index++;
        }

        try {
            $stripeRequest = Http::asForm()->withToken($stripeSecret);

            if (app()->environment(['local', 'development', 'testing'])) {
                $stripeRequest = $stripeRequest->withoutVerifying();
            }

            $response = $stripeRequest->post('https://api.stripe.com/v1/checkout/sessions', $payload);
        } catch (\Throwable $exception) {
            if (app()->environment(['local', 'development', 'testing'])) {
                report($exception);

                return $localCheckoutFallback();
            }

            $order->items()->delete();
            $order->delete();

            report($exception);

            return redirect()->route('checkout.form')->with('error', 'Unable to start Stripe checkout. Please try again.');
        }

        if (!$response->successful() || empty($response->json('url'))) {
            if (app()->environment(['local', 'development', 'testing'])) {
                return $localCheckoutFallback();
            }

            $order->items()->delete();
            $order->delete();

            return redirect()->route('checkout.form')->with('error', 'Unable to start Stripe checkout. Please try again.');
        }

        return redirect()->away($response->json('url'));
    }

    public function checkoutSuccess(Request $request)
    {
        if (!Schema::hasTable('orders')) {
            return redirect()->route('ordering')->with('success', 'Payment received. Order updates are currently unavailable.');
        }

        $sessionId = (string) $request->query('session_id', '');
        $stripeSecret = (string) config('services.stripe.secret');

        if (app()->environment(['local', 'development', 'testing']) && str_starts_with($sessionId, 'local_')) {
            $orderId = (int) substr($sessionId, 6);
            $order = Order::query()->find($orderId);

            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                ]);
            }

            session()->forget('cart');

            return redirect()->route('ordering')->with('success', 'Payment successful. Your order has been received.');
        }

        if ($sessionId === '' || $stripeSecret === '') {
            return redirect()->route('ordering')->with('error', 'Stripe payment verification failed.');
        }

        $response = Http::withToken($stripeSecret)->get('https://api.stripe.com/v1/checkout/sessions/' . $sessionId, [
            'expand[]' => 'payment_intent',
        ]);

        if (!$response->successful()) {
            return redirect()->route('ordering')->with('error', 'Unable to verify Stripe payment.');
        }

        $session = $response->json();
        $orderId = (int) data_get($session, 'metadata.order_id', data_get($session, 'client_reference_id'));
        $order = Order::query()->find($orderId);

        if ($order) {
            $isPaid = data_get($session, 'payment_status') === 'paid';
            $order->update([
                'payment_status' => $isPaid ? 'paid' : 'pending',
                'stripe_payment_intent_id' => (string) (data_get($session, 'payment_intent.id') ?? data_get($session, 'payment_intent')),
            ]);
        }

        session()->forget('cart');

        return redirect()->route('ordering')->with('success', 'Payment successful. Your order has been received.');
    }

    public function checkoutCancel(Request $request)
    {
        if (!Schema::hasTable('orders')) {
            return redirect()->route('checkout.form')->with('error', 'Payment was cancelled. You can retry checkout in test mode.');
        }

        $orderId = (int) $request->query('order');
        $order = Order::query()->find($orderId);

        if ($order && $order->payment_status === 'pending') {
            $order->update([
                'payment_status' => 'cancelled',
            ]);
        }

        return redirect()->route('checkout.form')->with('error', 'Payment was cancelled. You can retry checkout in test mode.');
    }

    private function calculateTotal(array $cart): float
    {
        return (float) collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }
}
