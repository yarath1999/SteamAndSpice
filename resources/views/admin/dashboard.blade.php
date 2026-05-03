@extends('layouts.admin')

@section('content')
<h1>Dashboard</h1>
<div class="grid" style="margin-bottom: 16px;">
    <div class="panel"><strong>Menu Items:</strong> {{ $stats['menu_items'] }}</div>
    <div class="panel"><strong>Orders:</strong> {{ $stats['orders'] }}</div>
    <div class="panel"><strong>Pending Orders:</strong> {{ $stats['pending_orders'] }}</div>
</div>

<div class="grid">
    <section class="panel">
        <h2 style="margin-top: 0;">Latest Orders</h2>
        <table>
            <thead><tr><th>ID</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead>
            <tbody>
                @forelse($latestOrders as $order)
                    <tr><td>#{{ $order->id }}</td><td>{{ $order->customer_name }}</td><td>{{ $order->status }}</td><td>GBP {{ number_format((float) $order->total_amount, 2) }}</td></tr>
                @empty
                    <tr><td colspan="4">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

</div>
@endsection
