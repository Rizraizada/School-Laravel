@extends('layouts.app')

@section('content')
    <section class="panel">
        <h2 class="panel-header">Notice Board</h2>
        <div class="panel-body">
            <div class="portal-table-wrap">
                <table class="portal-table">
                    <thead>
                    <tr>
                        <th style="width:80px;">SL</th>
                        <th>Notice Title</th>
                        <th style="width:160px;">Publish Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($notices as $notice)
                        <tr>
                            <td>{{ $loop->iteration + (($notices->currentPage() - 1) * $notices->perPage()) }}</td>
                            <td>
                                <strong>{{ $notice->title }}</strong>
                                @if($notice->badge)
                                    <div style="margin-top:5px;">
                                        <span class="pill">{{ $notice->badge }}</span>
                                    </div>
                                @endif
                                <div class="text-muted" style="margin-top:6px;">
                                    {{ \Illuminate\Support\Str::limit($notice->content, 220) }}
                                </div>
                            </td>
                            <td>{{ $notice->date->format('d M, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted">No notices found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $notices->links() }}</div>
        </div>
    </section>
@endsection
