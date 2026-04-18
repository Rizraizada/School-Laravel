@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="section-header">
            <h1>Photo Gallery</h1>
            <span class="badge">Moments & Memories</span>
        </div>

        <div class="grid grid-3">
            @forelse($galleries as $item)
                <article class="card">
                    <h3>{{ $item->title }}</h3>
                    @if($item->image)
                        <img class="preview" src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}">
                    @endif
                    <p class="muted mt-2"><strong>Category:</strong> {{ $item->category ?? 'General' }}</p>
                    @if($item->description)
                        <p class="mt-2">{{ \Illuminate\Support\Str::limit($item->description, 180) }}</p>
                    @endif
                </article>
            @empty
                <p>No gallery entries found.</p>
            @endforelse
        </div>

        <div class="pagination">
            {{ $galleries->links() }}
        </div>
    </div>
@endsection
