@extends('layouts.dashboard')

@section('title', 'Notices')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:12px;">
            <h2 style="margin:0;">Notice Board</h2>
            <a class="btn" href="{{ route('admin.notices.create') }}">Add Notice</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Badge</th>
                        <th>Content</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $notice)
                        <tr>
                            <td>{{ $notice->title }}</td>
                            <td>{{ $notice->date->format('d M Y') }}</td>
                            <td>{{ $notice->badge ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($notice->content, 80) }}</td>
                            <td>
                                <div class="inline-actions">
                                    <a href="{{ route('admin.notices.edit', $notice) }}">Edit</a>
                                    <form action="{{ route('admin.notices.destroy', $notice) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-link" onclick="return confirm('Delete notice?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No notices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $records->links() }}</div>
    </div>
@endsection
