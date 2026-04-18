@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1>Message from the Headmaster</h1>
    </div>

    @if($headmaster)
        <div class="card">
            <h2>{{ $headmaster->full_name }}</h2>
            <p class="muted">{{ $headmaster->position ?: 'Headmaster' }}</p>
            @if($headmaster->image)
                <img class="preview mb-3" src="{{ asset('storage/'.$headmaster->image) }}" alt="{{ $headmaster->full_name }}">
            @endif
            <p>{{ $headmaster->description ?: 'Welcome to our institution. We are committed to excellence in education and student development.' }}</p>
        </div>
    @else
        <div class="card">
            <p class="muted">No headmaster profile has been configured yet.</p>
        </div>
    @endif
@endsection
