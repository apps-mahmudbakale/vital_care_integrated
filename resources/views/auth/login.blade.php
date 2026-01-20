@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <!-- Logo -->
    <div class="app-brand mb-4 text-center">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <img src="{{ asset('assets/img/favicon/favicon.ico') }}" alt="Logo" width="40">
            <span class="fw-bold">{{ config('app.name') }}</span>
        </a>
    </div>

    <h3 class="mb-1 fw-bold">Welcome back! 👋</h3>
    <p class="mb-4">Please sign in to your account and start your adventure.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email or Username</label>
            <input id="email" type="text"
                   name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="Enter your email or username"
                   autofocus required>
            @error('email')
            <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3 form-password-toggle">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label">Password</label>
                <a href="{{ route('password.request') }}">
                    <small>Forgot Password?</small>
                </a>
            </div>
            <div class="input-group input-group-merge">
                <input id="password" type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••••••"
                       required>
                <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
            </div>
            @error('password')
            <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                <label class="form-check-label" for="remember">
                    Remember Me
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary d-grid w-100">Sign In</button>
    </form>
@endsection
