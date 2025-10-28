@extends('mitra.layouts.app')

@section('title', 'Registrasi Mitra')

@section('content')
    <h2>Bergabung Menjadi Mitra Foodlink</h2>
    <p>Daftarkan usaha Anda untuk membantu mengurangi food waste.</p>

    {{-- Tampilkan error validasi jika ada --}}
    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 15px;">
            <strong>Oops! Ada kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('mitra.register') }}">
        @csrf
        <div>
            <label for="nama_mitra">Nama Usaha / Mitra</label><br>
            {{-- Perbaikan: type="text" --}}
            <input type="text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra') }}" required autofocus>
            @error('nama_mitra') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 10px;">
            <label for="email_bisnis">Email Bisnis</label><br>
            <input type="email" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis') }}" required>
            @error('email_bisnis') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 10px;">
            <label for="nomor_telepon">Nomor Telepon (PIC)</label><br>
            {{-- Perbaikan: type="text" --}}
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
            @error('nomor_telepon') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 10px;">
            <label for="alamat">Alamat Lengkap Usaha</label><br>
            <textarea id="alamat" name="alamat" rows="3" style="width: 300px;" required>{{ old('alamat') }}</textarea>
            @error('alamat') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi Singkat Usaha (Opsional)</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 300px;">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- === TAMBAHKAN BLOK INI === --}}
        <div style="margin-top: 10px;">
            <label for="kategori_usaha_id">Kategori Usaha</label><br>
            <select id="kategori_usaha_id" name="kategori_usaha_id" required>
                <option value="">-- Pilih Kategori Usaha Anda --</option>
                {{-- Loop data kategori yang dikirim dari controller --}}
                @foreach($kategoriUsaha as $kategori)
                    <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_usaha_id') == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
            {{-- Tampilkan error jika validasi gagal --}}
            @error('kategori_usaha_id')
                <span style="color: red;">{{ $message }}</span>
            @enderror
        </div>
        {{-- ======================== --}}

        <hr>

        <div style="margin-top: 10px;">
            <label for="password">Password</label><br>
            <input type="password" id="password" name="password" required>
            @error('password') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-top: 10px;">
            <label for="password_confirmation">Konfirmasi Password</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit">Daftar Sekarang</button>
        </div>
    </form>
@endsection
