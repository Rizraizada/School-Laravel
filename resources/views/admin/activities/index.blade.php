@extends('layouts.dashboard')

@section('title', 'Activities')

@section('content')
    <div class="card">
        <div class="section-header" style="display:flex;justify-content:space-between;align-items:center;">
            <h2 style="margin:0;">Activities</h2>
            <a class="btn" href="{{ route('admin.activities.create') }}">Add Activity</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Author</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->title }}</td>
                        <td>{{ $activity->date->format('d M, Y') }}</td>
                        <td>{{ $activity->author ?? '-' }}</td>
                        <td>
                            @if($activity->image)
                                <img class="preview" src="{{ asset('storage/'.$activity->image) }}" alt="activity">
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.activities.edit', $activity) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this activity?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No activities added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $activities->links() }}</div>
    </div>
@endsection
