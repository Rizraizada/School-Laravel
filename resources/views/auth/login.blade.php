@extends('layouts.app')

@section('full_width', '1')

@section('content')
    <div class="panel portal-login">
        <h1 class="panel-header">Admin Login Panel</h1>
        <div class="panel-body">
            <p class="text-muted">Sign in with your school portal credentials.</p>

            <form method="POST" action="{{ route('login.attempt') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" value="{{ old('username') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" required>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;">
                        <input type="checkbox" name="remember" value="1" style="width: auto;">
                        Remember me
                    </label>
                </div>

                <button class="btn" type="submit">Login</button>
            </form>
        </div>
    </div>
@endsection
