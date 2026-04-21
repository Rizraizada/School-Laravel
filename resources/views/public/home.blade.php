@extends('layouts.app')

@section('content')
    <section class="panel">
        <div class="panel-body hero">
            <div class="hero-feature">
                <h2>Board of Intermediate & Secondary Education Portal</h2>
                <p>
                    This portal publishes official notices, board activities, committee information, and
                    public-facing administrative updates. Citizens, teachers, and institutions can use
                    this platform as the authentic source of school board announcements.
                </p>
            </div>
            <div class="hero-slider">
                @if($sliders->isNotEmpty())
                    <div class="slider-grid">
                        @foreach($sliders as $slider)
                            <figure class="slider-item">
                                <img src="{{ asset('storage/'.$slider->image) }}" alt="Highlight image">
                            </figure>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No highlight images available at this moment.</p>
                @endif
            </div>
        </div>
    </section>

    <section class="panel">
        <h3 class="panel-header">Notice Board</h3>
        <div class="panel-body">
            <ul class="notice-list">
                @forelse($notices as $notice)
                    <li>
                        <strong>{{ $notice->title }}</strong>
                        <div class="notice-date">{{ $notice->date->format('d M Y') }}</div>
                        <div class="text-muted">{{ \Illuminate\Support\Str::limit($notice->content, 130) }}</div>
                        @if($notice->badge)
                            <span class="pill">{{ $notice->badge }}</span>
                        @endif
                    </li>
                @empty
                    <li class="text-muted">No notices have been published.</li>
                @endforelse
            </ul>
            <div class="pagination">
                <a class="btn btn-light" href="{{ route('public.notices') }}">View All Notices</a>
            </div>
        </div>
    </section>

    <div class="two-col">
        <section class="panel">
            <h3 class="panel-header">Recent Activities</h3>
            <div class="panel-body">
                @forelse($activities as $activity)
                    <article class="mini-card">
                        <h4>{{ $activity->title }}</h4>
                        <p>{{ $activity->date->format('d M Y') }} @if($activity->author) | {{ $activity->author }} @endif</p>
                    </article>
                @empty
                    <p class="text-muted">No recent activities found.</p>
                @endforelse
            </div>
        </section>

        <section class="panel">
            <h3 class="panel-header">Awards & Achievements</h3>
            <div class="panel-body">
                @forelse($awards as $award)
                    <article class="mini-card">
                        <h4>{{ $award->title }}</h4>
                        @if($award->subtitle)
                            <p>{{ $award->subtitle }}</p>
                        @endif
                        @if($award->image)
                            <div style="margin-top:8px;">
                                <img style="width:100%;max-height:130px;object-fit:cover;border:1px solid #d5deea;" src="{{ asset('storage/'.$award->image) }}" alt="{{ $award->title }}">
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="text-muted">No awards have been published.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection

@section('sidebar')
    <div class="widget">
        <h3>Latest Activities</h3>
        <div class="widget-content">
            <ul class="link-list">
                @forelse($activities->take(5) as $activity)
                    <li>
                        <a href="{{ route('public.messages') }}">
                            {{ \Illuminate\Support\Str::limit($activity->title, 45) }}
                        </a>
                    </li>
                @empty
                    <li><a href="{{ route('public.messages') }}">No activity item available</a></li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="widget">
        <h3>Public Communication</h3>
        <div class="widget-content">
            <ul class="link-list">
                <li><a href="{{ route('public.contact') }}">Office Contact Details</a></li>
                <li><a href="{{ route('public.staff') }}">Department Staff Directory</a></li>
                <li><a href="{{ route('public.committee') }}">Board Committee Information</a></li>
            </ul>
        </div>
    </div>
@endsection
