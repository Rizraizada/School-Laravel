@extends('layouts.dashboard')

@section('title', 'Classes')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;">
            <h2 style="margin:0;">Class List</h2>
            <a class="btn" href="{{ route('admin.classes.create') }}">Add Class</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Class Name</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($items as $item)
                    <tr>
                        <td>{{ $item->class_name }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.classes.edit', $item) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.classes.destroy', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this class?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2">No classes created yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $items->links() }}</div>
    </div>
@endsection
