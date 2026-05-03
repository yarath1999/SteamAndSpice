@extends('layouts.admin')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px;">
    <h1 style="margin:0;">Menu Management</h1>
    <a class="btn cta-btn" href="{{ route('admin.menu-items.create') }}">Add Menu Item</a>
</div>
<div class="panel">
    <table>
        <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Featured</th><th>Available</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($menuItems as $item)
                <tr>
                    <td>#{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category?->name }}</td>
                    <td>GBP {{ number_format((float) $item->price, 2) }}</td>
                    <td>{{ $item->is_featured ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->is_available ? 'Yes' : 'No' }}</td>
                    <td>
                        <a class="btn cta-btn" href="{{ route('admin.menu-items.edit', $item) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.menu-items.destroy', $item) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-muted cta-btn" type="submit" onclick="return confirm('Delete this menu item?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No menu items found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 12px;">{{ $menuItems->links() }}</div>
</div>
@endsection
