@extends('layouts.app')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Managing Committee</h3>
        <div class="panel-body">
            <div class="portal-table-wrap">
                <table class="portal-table">
                    <thead>
                    <tr>
                        <th style="width:8%;">SL</th>
                        <th style="width:20%;">Committee</th>
                        <th style="width:22%;">Name</th>
                        <th style="width:20%;">Position</th>
                        <th>Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($directors as $index => $director)
                        <tr>
                            <td>{{ $directors->firstItem() + $index }}</td>
                            <td>
                                @if($director->committee)
                                    <span class="pill">{{ $director->committee }}</span>
                                @else
                                    <span class="text-muted">General</span>
                                @endif
                            </td>
                            <td><strong>{{ $director->name }}</strong></td>
                            <td>{{ $director->position }}</td>
                            <td>{{ $director->details ?: 'No additional details provided.' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">No committee members have been published.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $directors->links() }}</div>
        </div>
    </section>
@endsection
