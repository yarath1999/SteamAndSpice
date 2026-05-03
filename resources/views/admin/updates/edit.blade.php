@extends('layouts.admin')

@section('content')
<h1>Edit Update #{{ $updatePost->id }}</h1>
<form class="panel" method="POST" action="{{ route('admin.updates.update', $updatePost) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.updates._form')
    <button class="btn cta-btn" type="submit">Update</button>
</form>
@endsection
