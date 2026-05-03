@extends('layouts.admin')

@section('content')
<h1>Create Menu Item</h1>
<form class="panel" method="POST" action="{{ route('admin.menu-items.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.menu-items._form')
    <button class="btn cta-btn" type="submit">Create</button>
</form>
@endsection
