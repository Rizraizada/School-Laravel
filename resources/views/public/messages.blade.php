@extends('layouts.app')

@section('content')
    <section class="panel">
        <h3 class="panel-header">Message from the Headmaster</h3>
        <div class="panel-body">
            @if($headmaster)
                <div style="display:grid;grid-template-columns:minmax(0,180px) minmax(0,1fr);gap:12px;">
                    <div>
                        @if($headmaster->image)
                            <img style="width:100%;border:1px solid #d5deea;" src="{{ asset('storage/'.$headmaster->image) }}" alt="{{ $headmaster->full_name }}">
                        @else
                            <div style="border:1px solid #d5deea;background:#f5f8fc;padding:12px;text-align:center;color:#667085;">
                                No Image
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 style="margin:0 0 6px;color:#164774;">{{ $headmaster->full_name }}</h4>
                        <p class="text-muted" style="margin-top:0;">{{ $headmaster->position ?: 'Headmaster' }}</p>
                        <p style="line-height:1.6;">
                            {{ $headmaster->description ?: 'Welcome to our institution. We are committed to excellence in education and student development.' }}
                        </p>
                        @if($headmaster->phone)
                            <p style="margin:0;"><strong>Phone:</strong> {{ $headmaster->phone }}</p>
                        @endif
                        @if($headmaster->email)
                            <p style="margin:6px 0 0;"><strong>Email:</strong> {{ $headmaster->email }}</p>
                        @endif
                    </div>
                </div>
            @else
                <p class="text-muted">No headmaster profile has been configured yet.</p>
            @endif
        </div>
    </section>
@endsection
