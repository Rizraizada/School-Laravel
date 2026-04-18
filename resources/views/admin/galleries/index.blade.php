@extends('layouts.dashboard')

@section('title', 'Gallery')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
            <h2 style="margin:0;">Gallery Items</h2>
            <a class="btn" href="{{ route('admin.galleries.create') }}">Add Gallery Item</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($galleries as $gallery)
                    <tr>
                        <td>
                            @if($gallery->image)
                                <img class="preview" src="{{ asset('storage/'.$gallery->image) }}" alt="{{ $gallery->title }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $gallery->title }}</td>
                        <td>{{ $gallery->category ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($gallery->description, 80) }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.galleries.edit', $gallery) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.galleries.destroy', $gallery) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this gallery item?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No gallery items found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $galleries->links() }}</div>
    </div>
@endsection
