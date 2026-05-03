@extends('layouts.admin')

@section('content')
<h1>Create Update</h1>
<form class="panel" method="POST" action="{{ route('admin.updates.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.updates._form')
    <button class="btn cta-btn" type="submit">Create</button>
</form>
@endsection
