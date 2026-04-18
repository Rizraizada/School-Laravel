<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <style>
        :root {
            --primary: #0056a8;
            --primary-dark: #00427f;
            --surface: #f3f7fb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #dbe4ef;
            --danger: #c0392b;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background: var(--surface);
        }
        a { color: var(--primary); text-decoration: none; }
        .layout {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }
        .sidebar {
            background: var(--primary-dark);
            color: #fff;
            padding: 18px;
        }
        .sidebar h2 {
            margin: 0 0 12px;
            font-size: 20px;
        }
        .sidebar small {
            display: block;
            opacity: .85;
            margin-bottom: 18px;
        }
        .sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .sidebar nav a {
            color: #fff;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 14px;
            opacity: .95;
        }
        .sidebar nav a:hover {
            background: rgba(255,255,255,.15);
        }
        .content-area {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        header.topbar {
            background: var(--card);
            border-bottom: 1px solid var(--border);
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        main {
            padding: 20px;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,.03);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 6px;
            background: var(--primary);
            color: #fff;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover { background: var(--primary-dark); }
        .btn-danger { background: var(--danger); }
        .btn-link {
            border: 0;
            background: transparent;
            color: var(--primary);
            cursor: pointer;
            padding: 0;
        }
        .table-wrap {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            background: #fff;
        }
        th, td {
            border: 1px solid var(--border);
            padding: 9px 10px;
            text-align: left;
            font-size: 14px;
            vertical-align: top;
        }
        th {
            background: #f0f5fb;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        label { font-weight: 600; font-size: 13px; }
        input, select, textarea {
            border: 1px solid #c8d4e1;
            border-radius: 6px;
            padding: 9px 10px;
            width: 100%;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
        }
        textarea { min-height: 110px; }
        .alert {
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 14px;
        }
        .alert-success {
            background: #e8f7ee;
            border: 1px solid #9ad0ad;
            color: #1f6b3c;
        }
        .alert-danger {
            background: #fdeeed;
            border: 1px solid #f3b9b4;
            color: #932820;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }
        .stat {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
        }
        .stat p { margin: 0; color: var(--muted); font-size: 13px; }
        .stat h3 { margin: 6px 0 0; font-size: 22px; }
        .inline-actions {
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h2>School Panel</h2>
        <small>{{ auth()->user()->full_name }} ({{ auth()->user()->role }})</small>
        <nav>
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.students.index') }}">Students</a>
            <a href="{{ route('admin.attendance.index') }}">Attendance</a>
            <a href="{{ route('admin.attendance.report') }}">Attendance Report</a>
            <a href="{{ route('admin.quick-attendance.index') }}">Quick Attendance</a>

            @if(auth()->user()->isAdminLike())
                <a href="{{ route('admin.users.index') }}">Users</a>
                <a href="{{ route('admin.classes.index') }}">Classes</a>
                <a href="{{ route('admin.sections.index') }}">Sections</a>
                <a href="{{ route('admin.teacher-sections.index') }}">Teacher Sections</a>
                <a href="{{ route('admin.notices.index') }}">Notices</a>
                <a href="{{ route('admin.sliders.index') }}">Sliders</a>
                <a href="{{ route('admin.awards.index') }}">Awards</a>
                <a href="{{ route('admin.galleries.index') }}">Gallery</a>
                <a href="{{ route('admin.directors.index') }}">Board Directors</a>
                <a href="{{ route('admin.activities.index') }}">Activities</a>
            @endif

            <a href="{{ route('home') }}" target="_blank">View Public Site</a>
        </nav>
    </aside>

    <div class="content-area">
        <header class="topbar">
            <div>
                <strong>@yield('title', 'Dashboard')</strong>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger" type="submit">Logout</button>
            </form>
        </header>

        <main>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Please fix the following:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
