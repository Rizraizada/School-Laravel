@extends('layouts.app')

@section('content')
    <div class="card" style="max-width: 520px; margin: 0 auto;">
        <h1>Login</h1>
        <p class="muted mb-3">Sign in with your school portal credentials.</p>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf
            <div class="mb-3">
                <label for="username">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" required>
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>

            <div class="mb-3">
                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal;">
                    <input type="checkbox" name="remember" value="1" style="width: auto;">
                    Remember me
                </label>
            </div>

            <button class="btn btn-primary" type="submit">Login</button>
        </form>
    </div>
@endsection
