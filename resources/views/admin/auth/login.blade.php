<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Foodlink</title>
    </head>
<body>
    <div style="width: 300px; margin: 100px auto;">
        <h2>Login Admin Foodlink</h2>

        @if ($errors->any())
            <div style="color: red;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div>
                <label for="username">Username</label><br>
                <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
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
    </div>
</body>
</html>
