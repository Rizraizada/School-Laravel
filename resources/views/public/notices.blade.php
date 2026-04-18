@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="section-header">
            <h1>All Notices</h1>
        </div>

        @forelse($notices as $notice)
            <div class="notice">
                <div style="display:flex;justify-content:space-between;gap:8px;flex-wrap:wrap;">
                    <strong>{{ $notice->title }}</strong>
                    <small class="muted">{{ $notice->date->format('d M Y') }}</small>
                </div>
                @if($notice->badge)
                    <span class="badge mt-2">{{ $notice->badge }}</span>
                @endif
                <p class="mt-2">{{ $notice->content }}</p>
            </div>
        @empty
            <p class="muted">No notices found.</p>
        @endforelse

        <div class="pagination">{{ $notices->links() }}</div>
    </div>
@endsection
