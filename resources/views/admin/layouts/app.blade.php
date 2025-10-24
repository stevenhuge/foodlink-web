<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Admin Dashboard') - Foodlink</title>
    <style>
        body { font-family: sans-serif; }
        .container { width: 90%; margin: 20px auto; }
        .navbar { background: #f0f0f0; padding: 10px; }
        .navbar a { margin-right: 20px; text-decoration: none; color: #333; }
        .navbar form { display: inline; }
        .success-msg { padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 15px; }
        .error-msg { padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 15px; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('admin.dashboard') }}"><b>Foodlink Admin</b></a> |

        @can('is-admin')
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.mitra.index') }}">Verifikasi Mitra</a>
        @endcan

        @can('is-superadmin')
            <a href="{{ route('admin.admins.index') }}">Manajemen Admin</a>
        @endcan

        <div style="float: right;">
            Halo, <b>{{ auth()->guard('admin')->user()->nama_lengkap }}</b> ({{ auth()->guard('admin')->user()->role }})
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" onclick="return confirm('Anda yakin ingin logout?')">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>

</body>
</html>
