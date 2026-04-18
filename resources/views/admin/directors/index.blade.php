@extends('layouts.dashboard')

@section('title', 'Board Directors')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <h2 style="margin:0;">Board / Committee Members</h2>
            <a class="btn" href="{{ route('admin.directors.create') }}">Add Member</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Committee</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($directors as $director)
                    <tr>
                        <td>{{ $director->name }}</td>
                        <td>{{ $director->position }}</td>
                        <td>{{ $director->committee ?: '-' }}</td>
                        <td>
                            @if($director->image_url)
                                <img class="preview" src="{{ asset('storage/'.$director->image_url) }}" alt="{{ $director->name }}">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.directors.edit', $director) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.directors.destroy', $director) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this member?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No directors found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $directors->links() }}</div>
    </div>
@endsection
