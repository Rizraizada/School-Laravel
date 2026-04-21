@extends('layouts.app')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Photo Gallery</h3>
        <div class="panel-body">
            <div class="grid-3">
                @forelse($galleries as $item)
                    <article class="gallery-item">
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}">
                        @endif
                        <h4 style="margin:10px 0 6px;font-size:15px;color:#174976;">{{ $item->title }}</h4>
                        <p class="text-muted" style="margin:0 0 6px;">
                            <strong>Category:</strong> {{ $item->category ?? 'General' }}
                        </p>
                        @if($item->description)
                            <p class="text-muted" style="margin:0;">
                                {{ \Illuminate\Support\Str::limit($item->description, 120) }}
                            </p>
                        @endif
                    </article>
                @empty
                    <p class="text-muted">No gallery entries found.</p>
                @endforelse
            </div>

            <div class="pagination">
                {{ $galleries->links() }}
            </div>
        </div>
    </section>
@endsection
