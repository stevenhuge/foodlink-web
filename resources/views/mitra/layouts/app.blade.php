<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Mitra Dashboard') - Foodlink</title>
    <style>
        body { font-family: sans-serif; margin: 0; }
        .container { width: 90%; margin: 20px auto; }
        .navbar { background: #f0f0f0; padding: 10px 5%; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ddd;}
        .navbar .nav-links a { margin-right: 15px; text-decoration: none; color: #333; }
        .navbar .nav-links a:hover { color: #007bff; }
        .navbar .user-info { display: flex; align-items: center; }
        .navbar .user-info a { margin-right: 10px; text-decoration: none; color: #333; } /* Link profil */
        .navbar .user-info form { display: inline; margin-left: 10px;}
        .navbar .user-info button { background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px;}
        .navbar .user-info button:hover { background: #c82333; }
        .success-msg { padding: 10px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 15px; border-radius: 4px; }
        .error-msg { padding: 10px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; margin-bottom: 15px; border-radius: 4px; }
        h1, h2, h3 { color: #333; }
        a { color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        label { display: inline-block; margin-bottom: 5px; font-weight: bold;}
        input[type=text], input[type=email], input[type=password], input[type=number], input[type=file], input[type=datetime-local], select, textarea {
            width: 100%; padding: 8px; margin-bottom: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        button[type=submit] { background: green; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        button[type=submit]:hover { background: darkgreen; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-links">
            {{-- Link ke Dashboard HANYA jika sudah login --}}
            @auth('mitra')
                <a href="{{ route('mitra.dashboard') }}"><b>Foodlink Mitra Panel</b></a>
            @else
                 <b>Foodlink Mitra Panel</b> {{-- Tampilkan teks jika belum login --}}
            @endauth
        </div>

        <div class="user-info">
            {{-- Tampilkan info user & tombol logout JIKA sudah login --}}
            @auth('mitra')
                <span>Halo, <b>{{ auth('mitra')->user()->nama_mitra }}</b></span> |
                <a href="{{ route('mitra.profile.edit') }}">Edit Profil</a> |
                {{-- FORM LOGOUT --}}
                <form method="POST" action="{{ route('mitra.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" onclick="return confirm('Anda yakin ingin logout?')">Logout</button>
                </form>
            @else
                {{-- Tampilkan link Login/Register JIKA BELUM login --}}
                <a href="{{ route('mitra.login') }}">Login</a> |
                <a href="{{ route('mitra.register') }}">Register</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        {{-- Tampilkan Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif
         {{-- Tampilkan Error Validasi (Penting untuk Form) --}}
        @if ($errors->any())
            <div class="error-msg">
                <strong>Oops! Ada kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Konten Halaman --}}
        @yield('content')
    </div>
</body>
</html>
