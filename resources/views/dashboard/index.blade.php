@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <h2 style="margin-top:0;">Overview</h2>
        <div class="stats">
            <div class="stat"><p>Total Users</p><h3>{{ $stats['users'] }}</h3></div>
            <div class="stat"><p>Total Students</p><h3>{{ $stats['students'] }}</h3></div>
            <div class="stat"><p>Total Sections</p><h3>{{ $stats['sections'] }}</h3></div>
            <div class="stat"><p>Attendance Entries</p><h3>{{ $stats['attendances'] }}</h3></div>
            <div class="stat"><p>Quick Attendance</p><h3>{{ $stats['quick_attendances'] }}</h3></div>
            <div class="stat"><p>Student Results</p><h3>{{ $stats['student_results'] }}</h3></div>
            <div class="stat"><p>Subject Config</p><h3>{{ $stats['subject_configs'] }}</h3></div>
            <div class="stat"><p>Notices</p><h3>{{ $stats['notices'] }}</h3></div>
            <div class="stat"><p>Slider Images</p><h3>{{ $stats['sliders'] }}</h3></div>
            <div class="stat"><p>Activities</p><h3>{{ $stats['activities'] }}</h3></div>
        </div>
    </div>

    <div style="height: 12px;"></div>

    <div class="card">
        <h3>Recent Notices</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Badge</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentNotices as $notice)
                        <tr>
                            <td>{{ $notice->date->format('d M Y') }}</td>
                            <td>{{ $notice->title }}</td>
                            <td>{{ $notice->badge ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No notices found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="height: 12px;"></div>

    <div class="card">
        <h3>Recent Activities</h3>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Author</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentActivities as $activity)
                        <tr>
                            <td>{{ $activity->date->format('d M Y') }}</td>
                            <td>{{ $activity->title }}</td>
                            <td>{{ $activity->author ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No activities found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
