@extends('layouts.app')

@section('content')
    <section class="hero mb-4">
        <h1>Welcome to School Management Portal</h1>
        <p class="muted">
            A unified platform for school communication, student records, attendance monitoring,
            and public information publishing.
        </p>
    </section>

    @if($sliders->isNotEmpty())
        <section class="card mb-4">
            <div class="section-header">
                <h2>Highlights</h2>
            </div>
            <div class="grid grid-3">
                @foreach($sliders as $slider)
                    <div class="card">
                        <img class="preview" style="max-width:100%;max-height:170px;" src="{{ asset('storage/'.$slider->image) }}" alt="Slider image">
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <div class="grid grid-2">
        <section class="card">
            <div class="section-header">
                <h2>Latest Notices</h2>
                <a class="btn btn-secondary" href="{{ route('public.notices') }}">View All</a>
            </div>
            @forelse($notices as $notice)
                <div class="notice">
                    <strong>{{ $notice->title }}</strong>
                    <div class="muted">{{ $notice->date->format('d M, Y') }}</div>
                    <p class="mb-2">{{ \Illuminate\Support\Str::limit($notice->content, 130) }}</p>
                    @if($notice->badge)
                        <span class="badge">{{ $notice->badge }}</span>
                    @endif
                </div>
            @empty
                <p class="muted">No notices available.</p>
            @endforelse
        </section>

        <section class="card">
            <div class="section-header">
                <h2>Recent Activities</h2>
            </div>
            @forelse($activities as $activity)
                <div class="notice">
                    <strong>{{ $activity->title }}</strong>
                    <div class="muted">{{ $activity->date->format('d M, Y') }} @if($activity->author) by {{ $activity->author }} @endif</div>
                </div>
            @empty
                <p class="muted">No activities posted yet.</p>
            @endforelse
        </section>
    </div>

    <section class="card mt-4">
        <div class="section-header">
            <h2>Awards & Achievements</h2>
        </div>
        <div class="grid grid-3">
            @forelse($awards as $award)
                <article class="card">
                    <h3 style="font-size:18px;">{{ $award->title }}</h3>
                    <p class="muted">{{ $award->subtitle }}</p>
                    @if($award->image)
                        <img class="preview" style="max-width:100%;max-height:130px;" src="{{ asset('storage/'.$award->image) }}" alt="{{ $award->title }}">
                    @endif
                </article>
            @empty
                <p class="muted">No awards added yet.</p>
            @endforelse
        </div>
    </section>
@endsection
