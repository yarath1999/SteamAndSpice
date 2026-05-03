<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('orders')) {
            $orders = new LengthAwarePaginator([], 0, 15);

            return view('admin.orders.index', compact('orders'))
                ->with('error', 'Orders table is unavailable.');
        }

        $orders = Order::latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('orders')) {
            return redirect()->route('admin.dashboard')->with('error', 'Orders table is unavailable.');
        }

        $validated = $this->validateData($request);
        $validated['payment_status'] = $request->input('payment_status', 'pending');
        Order::create($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Order created.');
    }

    public function show(Order $order)
    {
        if (!Schema::hasTable('orders')) {
            return redirect()->route('admin.dashboard')->with('error', 'Orders table is unavailable.');
        }

        if (Schema::hasTable('order_items')) {
            $order->load('items');
        } else {
            $order->setRelation('items', collect());
        }

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        if (!Schema::hasTable('orders')) {
            return redirect()->route('admin.dashboard')->with('error', 'Orders table is unavailable.');
        }

        $validated = $this->validateData($request);
        $validated['payment_status'] = $request->input('payment_status', $order->payment_status);
        $order->update($validated);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated.');
    }

    public function destroy(Order $order)
    {
        if (!Schema::hasTable('orders')) {
            return redirect()->route('admin.dashboard')->with('error', 'Orders table is unavailable.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'string', 'max:30'],
            'payment_status' => ['nullable', 'string', 'max:30'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
