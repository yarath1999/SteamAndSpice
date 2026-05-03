@extends('layouts.admin')

@section('content')
<h1>Site Settings</h1>
<form class="panel" method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="phone">Phone</label>
        <input id="phone" name="phone" value="{{ old('phone', $siteSettings->phone) }}">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $siteSettings->email) }}">
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" rows="4">{{ old('address', $siteSettings->address) }}</textarea>
    </div>

    <button class="btn cta-btn" type="submit">Save Settings</button>
</form>
@endsection
