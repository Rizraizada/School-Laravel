@extends('layouts.app')

@section('content')
    <section class="card">
        <div class="section-header">
            <h1>Board of Directors / Committee</h1>
        </div>

        <div class="grid grid-3">
            @forelse($directors as $director)
                <article class="card">
                    <h3>{{ $director->name }}</h3>
                    <p class="muted">{{ $director->position }}</p>
                    @if($director->committee)
                        <p><span class="badge">{{ $director->committee }}</span></p>
                    @endif
                    @if($director->details)
                        <p>{{ $director->details }}</p>
                    @endif
                </article>
            @empty
                <p>No committee members published.</p>
            @endforelse
        </div>

        <div class="pagination">{{ $directors->links() }}</div>
    </section>
@endsection
