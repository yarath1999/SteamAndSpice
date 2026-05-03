@extends('layouts.admin')

@section('content')
<h1>Edit Menu Item</h1>
<form class="panel" method="POST" action="{{ route('admin.menu-items.update', $menuItem) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.menu-items._form')
    <button class="btn cta-btn" type="submit">Update</button>
</form>
@endsection
