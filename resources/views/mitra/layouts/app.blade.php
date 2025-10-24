<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mitra Dashboard') - Foodlink</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 90%; margin: 20px auto; }
        .navbar { background: #f0f0f0; padding: 10px; }
        .navbar a { margin-right: 20px; text-decoration: none; color: #333; }
        .navbar form { display: inline; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('mitra.dashboard') }}"><b>Foodlink Mitra Panel</b></a>

        <div style="float: right;">
            @auth('mitra')
                Halo, <b>{{ auth('mitra')->user()->nama_mitra }}</b>
                <form method="POST" action="{{ route('mitra.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('Anda yakin ingin logout?')">Logout</button>
                </form>
            @else
                <a href="{{ route('mitra.login') }}">Login</a>
                <a href="{{ route('mitra.register') }}">Register</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div style="padding: 10px; background: #d4edda; color: #155724;">{{ session('success') }}</div>
        @endif
         @if ($errors->any())
            <div style="color: red;">
                <strong>Oops! Ada kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
