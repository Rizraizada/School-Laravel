@extends('layouts.dashboard')

@section('title', 'Awards')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
            <h2 style="margin:0;">Awards</h2>
            <a class="btn" href="{{ route('admin.awards.create') }}">Add Award</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($awards as $award)
                    <tr>
                        <td>{{ $award->title }}</td>
                        <td>{{ $award->subtitle ?? '-' }}</td>
                        <td>
                            @if($award->image)
                                <img class="preview" src="{{ asset('storage/'.$award->image) }}" alt="{{ $award->title }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.awards.edit', $award) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.awards.destroy', $award) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this award?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No awards found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $awards->links() }}</div>
    </div>
@endsection
