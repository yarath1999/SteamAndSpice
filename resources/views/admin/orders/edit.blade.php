@extends('layouts.admin')

@section('content')
<h1>Edit Order #{{ $order->id }}</h1>
<form class="panel" method="POST" action="{{ route('admin.orders.update', $order) }}">
    @csrf
    @method('PUT')
    @include('admin.orders._form')
    <button class="btn cta-btn" type="submit">Update</button>
</form>
@endsection
