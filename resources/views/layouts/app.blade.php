<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'School Management') }}</title>
    <style>
        :root {
            --primary: #005aa7;
            --primary-dark: #003f73;
            --accent: #f4a000;
            --bg: #f4f7fb;
            --text: #1f2937;
            --muted: #6b7280;
            --card: #ffffff;
            --border: #d5deea;
            --danger: #b91c1c;
            --success: #166534;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .topbar {
            background: linear-gradient(120deg, var(--primary-dark), var(--primary));
            color: #fff;
            padding: 10px 0;
            border-bottom: 4px solid var(--accent);
        }
        .container {
            width: min(1180px, 94%);
            margin: 0 auto;
        }
        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .brand {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-decoration: none;
            color: #fff;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
        }
        nav a:hover { background: rgba(255, 255, 255, 0.2); }
        .auth-block {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .auth-name {
            font-size: 13px;
            color: #e2e8f0;
        }
        .btn {
            border: 1px solid transparent;
            border-radius: 4px;
            padding: 8px 12px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
        }
        .btn-primary:hover { background: #004b8d; }
        .btn-secondary {
            background: #fff;
            border-color: var(--primary);
            color: var(--primary);
        }
        .btn-danger {
            background: #ef4444;
            color: #fff;
        }
        .btn-link {
            background: transparent;
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }
        .main {
            padding: 20px 0 32px;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 3px 8px rgba(15, 23, 42, 0.04);
        }
        h1, h2, h3 {
            margin: 0 0 12px;
            color: #0f172a;
        }
        h1 { font-size: 24px; }
        h2 { font-size: 20px; }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .grid {
            display: grid;
            gap: 16px;
        }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .form-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 5px;
        }
        input, select, textarea {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 9px 10px;
            font-size: 14px;
            background: #fff;
        }
        textarea { min-height: 100px; resize: vertical; }
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #dbe4f0;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #eaf2fb;
            color: #0f172a;
        }
        .muted { color: var(--muted); }
        .badge {
            background: #e8f2fe;
            color: var(--primary);
            border: 1px solid #c6dcf7;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 700;
        }
        .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; }
        .mt-4 { margin-top: 16px; }
        .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; }
        .mb-4 { margin-bottom: 16px; }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .notice {
            border-left: 4px solid var(--primary);
            background: #f8fbff;
            padding: 10px 12px;
            margin-bottom: 10px;
            border-radius: 4px;
        }
        .hero {
            background: linear-gradient(130deg, #dceeff, #ffffff);
            border: 1px solid #c5ddf6;
            border-radius: 8px;
            padding: 20px;
        }
        .hero h1 {
            margin-bottom: 10px;
            color: var(--primary-dark);
        }
        .footer {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #c9d7ea;
            color: #4b5563;
            font-size: 13px;
        }
        .error-text {
            color: var(--danger);
            font-size: 12px;
            margin-top: 4px;
        }
        img.preview {
            max-height: 80px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }
        .pagination {
            margin-top: 12px;
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="{{ route('home') }}">School Management Portal</a>
        <nav>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('public.notices') }}">Notices</a></li>
                <li><a href="{{ route('public.gallery') }}">Gallery</a></li>
                <li><a href="{{ route('public.committee') }}">Committee</a></li>
                <li><a href="{{ route('public.staff') }}">Staff</a></li>
                <li><a href="{{ route('public.messages') }}">Message</a></li>
                <li><a href="{{ route('public.contact') }}">Contact</a></li>
                @auth
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                @endauth
            </ul>
        </nav>
        <div class="auth-block">
            @auth
                <span class="auth-name">{{ auth()->user()->full_name }} ({{ auth()->user()->role }})</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-link" type="submit">Logout</button>
                </form>
            @else
                <a class="btn btn-link" href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>
</header>

<main class="main">
    <div class="container">
        @include('partials.alerts')
        @yield('content')
        <div class="footer">
            <strong>School Management + CMS</strong><br>
            Built with Laravel & Blade for academic operations, attendance, and public content publishing.
        </div>
    </div>
</main>
</body>
</html>
