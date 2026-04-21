@extends('layouts.app')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Teachers & Staff Directory</h3>
        <div class="panel-body">
            <div class="portal-table-wrap">
                <table class="portal-table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Expertise</th>
                        <th>Designation</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($staffMembers as $member)
                        <tr>
                            <td>{{ $member->full_name }}</td>
                            <td><span class="pill">{{ ucfirst($member->role) }}</span></td>
                            <td>{{ $member->phone ?: '-' }}</td>
                            <td>{{ $member->expertise ?: '-' }}</td>
                            <td>{{ $member->position ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No staff records found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $staffMembers->links() }}</div>
        </div>
    </section>
@endsection
