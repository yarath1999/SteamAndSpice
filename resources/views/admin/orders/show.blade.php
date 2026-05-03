@extends('layouts.admin')

@section('content')
<h1>Order #{{ $order->id }}</h1>
<section class="panel" style="margin-bottom: 14px;">
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Phone:</strong> {{ $order->phone }}</p>
    <p><strong>Address:</strong> {{ $order->address ?: '-' }}</p>
    <p><strong>Status:</strong> {{ $order->status }}</p>
    <p><strong>Payment:</strong> {{ $order->payment_status }}</p>
    <p><strong>Total:</strong> GBP {{ number_format((float) $order->total_amount, 2) }}</p>
    <p><strong>Notes:</strong> {{ $order->notes ?: '-' }}</p>
</section>
<section class="panel">
    <h2 style="margin-top: 0;">Items</h2>
    <table>
        <thead><tr><th>Item</th><th>Qty</th><th>Unit</th><th>Line Total</th></tr></thead>
        <tbody>
            @forelse($order->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>GBP {{ number_format((float) $item->unit_price, 2) }}</td>
                    <td>GBP {{ number_format((float) $item->line_total, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No items attached.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
