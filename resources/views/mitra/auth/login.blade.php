@extends('mitra.layouts.app')

@section('title', 'Login Mitra')

@section('content')
    <h2>Login Mitra</h2>

    <form method="POST" action="{{ route('mitra.login') }}">
        @csrf
        <div>
            <label for="email_bisnis">Email Bisnis</label><br>
            <input type="email" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}" required autofocus>
        </div>
        <div style="margin-top: 10px;">
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <div style="margin-top: 10px;">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember me</label>
        </div>
        <div style="margin-top: 20px;">
            <button type="submit">Login</button>
        </div>
    </form>
@endsection
