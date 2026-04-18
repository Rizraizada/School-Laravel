@extends('layouts.dashboard')

@section('title', 'Sliders')

@section('content')
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;">
            <h2 style="margin:0;">Slider Images</h2>
            <a class="btn" href="{{ route('admin.sliders.create') }}">Add Slider</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($sliders as $slider)
                    <tr>
                        <td>
                            @if($slider->image)
                                <img class="preview" src="{{ asset('storage/'.$slider->image) }}" alt="slider">
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $slider->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.sliders.edit', $slider) }}">Edit</a>
                                <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-link" onclick="return confirm('Delete this slider?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">No slider images found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $sliders->links() }}
    </div>
@endsection
