@extends('layouts.admin')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px;">
    <h1 style="margin:0;">Orders</h1>
    <a class="btn cta-btn" href="{{ route('admin.orders.create') }}">Create Order</a>
</div>
<div class="panel">
    <table>
        <thead><tr><th>ID</th><th>Customer</th><th>Phone</th><th>Address</th><th>Status</th><th>Payment</th><th>Total</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->address ?: '-' }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td>GBP {{ number_format((float) $order->total_amount, 2) }}</td>
                    <td>
                        <a class="btn cta-btn" href="{{ route('admin.orders.show', $order) }}">View</a>
                        <a class="btn cta-btn" href="{{ route('admin.orders.edit', $order) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-muted cta-btn" type="submit" onclick="return confirm('Delete this order?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 12px;">{{ $orders->links() }}</div>
</div>
@endsection
