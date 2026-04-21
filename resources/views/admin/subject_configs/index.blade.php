@extends('layouts.dashboard')

@section('title', 'Subject Configuration')

@section('content')
    <div class="card">
        <div class="section-header">
            <h2 style="margin:0;">Subject Configuration</h2>
            <a class="btn" href="{{ route('admin.subject-config.create') }}">Add Subject</a>
        </div>

        <form method="GET" action="{{ route('admin.subject-config.index') }}" class="form-grid" style="margin-top:14px;">
            <div class="form-group">
                <label for="class_level">Class Level</label>
                <select id="class_level" name="class_level">
                    <option value="">All class levels</option>
                    @foreach($classLevels as $level)
                        <option value="{{ $level }}" @selected(($filters['class_level'] ?? '') === $level)>{{ $level }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="group_name">Group</label>
                <select id="group_name" name="group_name">
                    <option value="">All groups</option>
                    @foreach($groupNames as $group)
                        <option value="{{ $group }}" @selected(($filters['group_name'] ?? '') === $group)>{{ $group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>&nbsp;</label>
                <div class="inline-actions">
                    <button class="btn" type="submit">Filter</button>
                    <a class="btn" style="background:#6b7280;" href="{{ route('admin.subject-config.index') }}">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:16px;">
        <h2 style="margin-top:0;">Subject Configuration List</h2>
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Class Level</th>
                    <th>Group</th>
                    <th>Code</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Marks (Full/Pass)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($configs as $config)
                    <tr>
                        <td>{{ $config->class_level }}</td>
                        <td>{{ $config->group_name ?? '-' }}</td>
                        <td>{{ $config->subject_code ?? '-' }}</td>
                        <td>{{ $config->subject_name }}</td>
                        <td>{{ ucfirst($config->subject_type) }}</td>
                        <td>{{ $config->full_mark }} / {{ $config->pass_mark }}</td>
                        <td>{{ $config->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.subject-config.edit', $config) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.subject-config.destroy', $config) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this subject config?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">No subject configurations found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $configs->links() }}</div>
    </div>
@endsection
