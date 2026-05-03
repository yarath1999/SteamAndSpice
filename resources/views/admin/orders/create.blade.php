@extends('layouts.admin')

@section('content')
<h1>Create Order</h1>
<form class="panel" method="POST" action="{{ route('admin.orders.store') }}">
    @csrf
    @include('admin.orders._form')
    <button class="btn cta-btn" type="submit">Create</button>
</form>
@endsection
