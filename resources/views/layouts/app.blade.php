<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name', 'School Management') }}</title>
    <style>
        :root {
            --portal-blue: #0f4e8a;
            --portal-blue-dark: #08345e;
            --portal-accent: #f6d870;
            --portal-border: #d5dde7;
            --portal-text: #243043;
            --portal-muted: #627087;
            --portal-bg: #f3f7fb;
            --portal-danger: #b42318;
            --portal-success: #0f7f44;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Helvetica Neue", Arial, sans-serif;
            background: var(--portal-bg);
            color: var(--portal-text);
            min-height: 100vh;
        }
        .container {
            width: min(1200px, 95%);
            margin: 0 auto;
        }
        .portal-topbar {
            background: #e6eef7;
            border-bottom: 1px solid #ced9e6;
            font-size: 12px;
            color: #344054;
        }
        .portal-topbar-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            min-height: 34px;
            flex-wrap: wrap;
        }
        .portal-topbar-links {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .portal-topbar-links a {
            color: #344054;
            text-decoration: none;
        }
        .portal-header {
            background: linear-gradient(180deg, #2f78b9 0%, #0f4e8a 100%);
            border-bottom: 4px solid var(--portal-accent);
            color: #fff;
        }
        .portal-branding {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            padding: 14px 0;
            flex-wrap: wrap;
        }
        .portal-brand-main {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: #fff;
        }
        .portal-logo {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.75);
            background: radial-gradient(circle at 30% 30%, #f9e27a 10%, #debe4f 60%, #c0972b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #233b5b;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
        }
        .portal-title h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .portal-title p {
            margin: 4px 0 0;
            font-size: 14px;
            color: #dce9f7;
        }
        .portal-auth-block {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }
        .portal-auth-block a,
        .portal-auth-block button {
            border: 1px solid rgba(255, 255, 255, 0.65);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 3px;
            padding: 6px 10px;
            text-decoration: none;
            font-size: 12px;
            cursor: pointer;
        }
        .portal-nav {
            background: #08345e;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            border-bottom: 1px solid rgba(0, 0, 0, 0.2);
        }
        .portal-nav ul {
            margin: 0;
            padding: 0;
            list-style: none;
            display: flex;
            flex-wrap: wrap;
        }
        .portal-nav a {
            color: #f6f8fc;
            text-decoration: none;
            padding: 12px 14px;
            display: block;
            font-size: 14px;
            border-right: 1px solid rgba(255, 255, 255, 0.09);
        }
        .portal-nav a.active,
        .portal-nav a:hover {
            background: #0f4e8a;
        }
        .portal-alert-line {
            background: #fff6db;
            border-top: 1px solid #efd794;
            border-bottom: 1px solid #efd794;
            font-size: 13px;
        }
        .portal-alert-line p {
            margin: 0;
            padding: 8px 0;
        }
        .portal-main {
            padding: 18px 0 28px;
        }
        .portal-grid {
            display: grid;
            grid-template-columns: minmax(0, 2.2fr) minmax(270px, 1fr);
            gap: 16px;
            align-items: start;
        }
        .portal-single {
            max-width: 780px;
            margin: 0 auto;
        }
        .panel {
            background: #fff;
            border: 1px solid var(--portal-border);
            box-shadow: 0 1px 4px rgba(16, 24, 40, 0.06);
            border-radius: 2px;
            margin-bottom: 14px;
        }
        .panel-header {
            margin: 0;
            padding: 10px 12px;
            font-size: 17px;
            color: #0d3b69;
            border-bottom: 1px solid #dbe4ef;
            background: linear-gradient(180deg, #f8fbff 0%, #edf3fa 100%);
        }
        .panel-body {
            padding: 12px;
        }
        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(0, 1fr);
            gap: 12px;
        }
        .hero-feature {
            min-height: 220px;
            border: 1px solid #dce6f1;
            background: linear-gradient(145deg, #f6fbff 0%, #edf4fb 100%);
            padding: 14px;
        }
        .hero-feature h2 {
            margin: 0 0 8px;
            color: #113f6e;
            font-size: 21px;
        }
        .hero-feature p {
            margin: 0;
            color: var(--portal-muted);
            line-height: 1.5;
            font-size: 14px;
        }
        .hero-slider {
            border: 1px solid #dce6f1;
            background: #f8fbff;
            padding: 10px;
        }
        .slider-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .slider-item {
            margin: 0;
            border: 1px solid #d5deea;
            background: #fff;
            min-height: 88px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }
        .slider-item img {
            max-width: 100%;
            max-height: 86px;
            object-fit: cover;
        }
        .notice-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .notice-list li {
            border-bottom: 1px dashed #d2dbe7;
            padding: 8px 0;
            font-size: 14px;
            line-height: 1.45;
        }
        .notice-list li:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }
        .notice-date {
            font-size: 12px;
            color: var(--portal-muted);
            margin-top: 3px;
        }
        .two-col {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .mini-card {
            border: 1px solid #d8e2ee;
            background: #f9fbfe;
            padding: 10px;
            margin-bottom: 8px;
        }
        .mini-card:last-child {
            margin-bottom: 0;
        }
        .mini-card h4 {
            margin: 0 0 6px;
            font-size: 15px;
            color: #164774;
        }
        .mini-card p {
            margin: 0;
            color: #53637a;
            font-size: 13px;
            line-height: 1.45;
        }
        .widget {
            background: #fff;
            border: 1px solid var(--portal-border);
            margin-bottom: 14px;
        }
        .widget h3 {
            margin: 0;
            font-size: 15px;
            color: #fff;
            background: linear-gradient(180deg, #2c6ba4 0%, #114776 100%);
            padding: 9px 11px;
            border-bottom: 1px solid #0d375e;
        }
        .widget-content {
            padding: 10px 11px;
        }
        .link-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .link-list li {
            border-bottom: 1px dashed #d2dbe7;
        }
        .link-list li:last-child {
            border-bottom: 0;
        }
        .link-list a {
            display: block;
            color: #0f4e8a;
            text-decoration: none;
            font-size: 13px;
            padding: 8px 2px;
        }
        .portal-table-wrap {
            overflow-x: auto;
        }
        .portal-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }
        .portal-table th,
        .portal-table td {
            border: 1px solid #d7dfeb;
            padding: 9px;
            text-align: left;
            vertical-align: top;
        }
        .portal-table th {
            background: #f1f6fc;
            color: #1b3f65;
            font-size: 13px;
        }
        .pill {
            display: inline-block;
            font-size: 11px;
            border-radius: 99px;
            padding: 2px 8px;
            background: #ecf4ff;
            color: #0f4e8a;
            border: 1px solid #cbe0fa;
        }
        .pagination {
            margin-top: 12px;
        }
        .text-muted {
            color: var(--portal-muted);
        }
        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }
        .gallery-item {
            border: 1px solid #d7dfeb;
            background: #fff;
            padding: 10px;
        }
        .gallery-item img {
            width: 100%;
            height: 170px;
            object-fit: cover;
            border: 1px solid #d5deea;
        }
        .portal-login {
            max-width: 520px;
            margin: 30px auto;
        }
        .form-group {
            margin-bottom: 12px;
        }
        label {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
            color: #344054;
            font-weight: 600;
        }
        input, select, textarea {
            width: 100%;
            border: 1px solid #c9d5e4;
            border-radius: 3px;
            padding: 8px 10px;
            font-size: 14px;
        }
        button, .btn {
            border: 1px solid #0f4e8a;
            background: #0f4e8a;
            color: #fff;
            border-radius: 3px;
            padding: 8px 14px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-light {
            background: #fff;
            color: #0f4e8a;
        }
        .portal-footer {
            border-top: 4px solid #0f4e8a;
            background: #e8eff8;
            margin-top: 26px;
            font-size: 13px;
            color: #374151;
        }
        .portal-footer-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            padding: 15px 0;
        }
        .portal-footer h4 {
            margin: 0 0 8px;
            color: #1a3f66;
            font-size: 14px;
        }
        .portal-footer p,
        .portal-footer li {
            margin: 0 0 5px;
            line-height: 1.4;
        }
        .portal-footer ul {
            padding-left: 16px;
            margin: 0;
        }
        .portal-footer-bottom {
            border-top: 1px solid #c4d2e2;
            padding: 10px 0 14px;
            font-size: 12px;
            color: #4b5565;
        }
        .alert-box {
            background: #fff;
            border: 1px solid #d5dde7;
            padding: 10px 12px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .alert-box.success { border-left: 4px solid var(--portal-success); }
        .alert-box.error { border-left: 4px solid var(--portal-danger); }
        .alert-box.info { border-left: 4px solid var(--portal-blue); }
        @media (max-width: 1024px) {
            .portal-grid,
            .hero,
            .two-col,
            .portal-footer-grid,
            .grid-3 {
                grid-template-columns: 1fr;
            }
            .portal-title h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
@php($fullWidth = trim($__env->yieldContent('full_width')) === '1')
<div class="portal-topbar">
    <div class="container portal-topbar-inner">
        <span>Welcome to National Portal</span>
        <div class="portal-topbar-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('public.contact') }}">Contact</a>
            <a href="{{ route('public.notices') }}">Notice Board</a>
        </div>
    </div>
</div>

<header class="portal-header">
    <div class="container portal-branding">
        <a class="portal-brand-main" href="{{ route('home') }}">
            <div class="portal-logo">Gov<br>Portal</div>
            <div class="portal-title">
                <h1>School Management Portal</h1>
                <p>Board Information, Notice Board, e-Services and Public Communication</p>
            </div>
        </a>
        <div class="portal-auth-block">
            @auth
                <span>{{ auth()->user()->full_name }} ({{ ucfirst(auth()->user()->role) }})</span>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </div>
    <nav class="portal-nav">
        <div class="container">
            <ul>
                <li><a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li><a class="{{ request()->routeIs('public.notices') ? 'active' : '' }}" href="{{ route('public.notices') }}">Notices</a></li>
                <li><a class="{{ request()->routeIs('public.gallery') ? 'active' : '' }}" href="{{ route('public.gallery') }}">Gallery</a></li>
                <li><a class="{{ request()->routeIs('public.committee') ? 'active' : '' }}" href="{{ route('public.committee') }}">Committee</a></li>
                <li><a class="{{ request()->routeIs('public.staff') ? 'active' : '' }}" href="{{ route('public.staff') }}">Staff</a></li>
                <li><a class="{{ request()->routeIs('public.messages') ? 'active' : '' }}" href="{{ route('public.messages') }}">Message</a></li>
                <li><a class="{{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
            </ul>
        </div>
    </nav>
    <div class="portal-alert-line">
        <div class="container">
            <p><strong>Alert:</strong> Avoid unofficial financial transactions. All official communication is published through this portal notice board.</p>
        </div>
    </div>
</header>

<main class="portal-main">
    <div class="container">
        @if($fullWidth)
            <div class="portal-single">
                @include('partials.alerts')
                @yield('content')
            </div>
        @else
            <div class="portal-grid">
                <section>
                    @include('partials.alerts')
                    @yield('content')
                </section>
                <aside>
                    <div class="widget">
                        <h3>Important Links</h3>
                        <div class="widget-content">
                            <ul class="link-list">
                                <li><a href="{{ route('public.notices') }}">Latest Notices</a></li>
                                <li><a href="{{ route('public.gallery') }}">Photo Gallery</a></li>
                                <li><a href="{{ route('public.committee') }}">Board Committee</a></li>
                                <li><a href="{{ route('public.staff') }}">Staff Directory</a></li>
                                <li><a href="{{ route('public.contact') }}">Office Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="widget">
                        <h3>Internal e-Services</h3>
                        <div class="widget-content">
                            <ul class="link-list">
                                <li><a href="{{ route('login') }}">Online Application Tracking</a></li>
                                <li><a href="{{ route('login') }}">Certificate Verification</a></li>
                                <li><a href="{{ route('login') }}">Name & Age Correction</a></li>
                                <li><a href="{{ route('login') }}">Attendance Monitoring</a></li>
                            </ul>
                        </div>
                    </div>
                    @yield('sidebar')
                </aside>
            </div>
        @endif
    </div>
</main>

<footer class="portal-footer">
    <div class="container portal-footer-grid">
        <div>
            <h4>Board Information</h4>
            <p>School Management Portal centralizes notices, academic updates, and administrative communication.</p>
        </div>
        <div>
            <h4>Citizen Services</h4>
            <ul>
                <li>Public notice publication</li>
                <li>Committee and staff information</li>
                <li>Institution contact directory</li>
            </ul>
        </div>
        <div>
            <h4>Quick Access</h4>
            <ul>
                <li><a href="{{ route('home') }}">First Page</a></li>
                <li><a href="{{ route('public.notices') }}">Notice Board</a></li>
                <li><a href="{{ route('public.contact') }}">Site Map & Contact</a></li>
            </ul>
        </div>
    </div>
    <div class="container portal-footer-bottom">
        Site updated dynamically from the school CMS panel.
    </div>
</footer>
</body>
</html>
