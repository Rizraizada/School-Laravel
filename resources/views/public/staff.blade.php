@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1>Staff Directory</h1>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Expertise</th>
                    <th>Position</th>
                </tr>
                </thead>
                <tbody>
                @forelse($staffMembers as $member)
                    <tr>
                        <td>{{ $member->full_name }}</td>
                        <td><span class="badge">{{ ucfirst($member->role) }}</span></td>
                        <td>{{ $member->phone ?: '-' }}</td>
                        <td>{{ $member->expertise ?: '-' }}</td>
                        <td>{{ $member->position ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">No staff records found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $staffMembers->links() }}</div>
    </div>
@endsection
