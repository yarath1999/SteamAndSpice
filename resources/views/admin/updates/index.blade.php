@extends('layouts.admin')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; gap:10px; margin-bottom:12px;">
    <h1 style="margin:0;">Updates</h1>
    <a class="btn cta-btn" href="{{ route('admin.updates.create') }}">Add Update</a>
</div>
<div class="panel">
    <table>
        <thead><tr><th>ID</th><th>Title</th><th>Active</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($updatePosts as $post)
                <tr>
                    <td>#{{ $post->id }}</td>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a class="btn cta-btn" href="{{ route('admin.updates.show', $post) }}">View</a>
                        <a class="btn cta-btn" href="{{ route('admin.updates.edit', $post) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.updates.destroy', $post) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-muted cta-btn" type="submit" onclick="return confirm('Delete this update?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No updates found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top: 12px;">{{ $updatePosts->links() }}</div>
</div>
@endsection
