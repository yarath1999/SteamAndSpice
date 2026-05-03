<div class="form-group">
    <label for="customer_name">Customer Name</label>
    <input id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name ?? '') }}" required>
</div>
<div class="grid">
    <div class="form-group">
        <label for="phone">Phone</label>
        <input id="phone" name="phone" value="{{ old('phone', $order->phone ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="address">Address</label>
        <input id="address" name="address" value="{{ old('address', $order->address ?? '') }}">
    </div>
</div>
<div class="grid">
    <div class="form-group">
        <label for="total_amount">Total Amount</label>
        <input id="total_amount" type="number" step="0.01" min="0" name="total_amount" value="{{ old('total_amount', $order->total_amount ?? 0) }}" required>
    </div>
</div>
<div class="grid">
    <div class="form-group">
        <label for="status">Status</label>
        <select id="status" name="status" required>
            @php($statusValue = old('status', $order->status ?? 'pending'))
            @foreach(['pending', 'confirmed', 'preparing', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected($statusValue === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="payment_status">Payment Status</label>
        <select id="payment_status" name="payment_status">
            @php($paymentStatus = old('payment_status', $order->payment_status ?? 'pending'))
            @foreach(['pending', 'paid', 'failed', 'refunded'] as $payment)
                <option value="{{ $payment }}" @selected($paymentStatus === $payment)>{{ ucfirst($payment) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label for="notes">Notes</label>
    <textarea id="notes" name="notes" rows="4">{{ old('notes', $order->notes ?? '') }}</textarea>
</div>
