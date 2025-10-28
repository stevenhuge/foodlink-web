@extends('admin.layouts.app')

@section('title', 'Edit Mitra: ' . $mitra->nama_mitra)

@section('content')
    <h1>Edit Mitra: {{ $mitra->nama_mitra }}</h1>

    {{-- Tampilkan error validasi jika ada --}}
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

    <form action="{{ route('admin.mitra.update', $mitra->mitra_id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nama Mitra --}}
        <div>
            <label for="nama_mitra">Nama Mitra</label><br>
            <input type="text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required style="width: 300px; padding: 8px;">
            @error('nama_mitra') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Email Bisnis --}}
        <div style="margin-top: 10px;">
            <label for="email_bisnis">Email Bisnis</label><br>
            <input type="email" id="email_bisnis" name="email_bisnis" value="{{ old('email_bisnis', $mitra->email_bisnis) }}" required style="width: 300px; padding: 8px;">
             @error('email_bisnis') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Nomor Telepon --}}
        <div style="margin-top: 10px;">
            <label for="nomor_telepon">Nomor Telepon</label><br>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $mitra->nomor_telepon) }}" required style="width: 300px; padding: 8px;">
             @error('nomor_telepon') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Alamat --}}
        <div style="margin-top: 10px;">
            <label for="alamat">Alamat</label><br>
            <textarea id="alamat" name="alamat" rows="3" style="width: 300px; padding: 8px;" required>{{ old('alamat', $mitra->alamat) }}</textarea>
             @error('alamat') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Deskripsi --}}
        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 300px; padding: 8px;">{{ old('deskripsi', $mitra->deskripsi) }}</textarea>
            @error('deskripsi') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Kategori Usaha --}}
        <div style="margin-top: 10px;">
            <label for="kategori_usaha_id">Kategori Usaha</label><br>
            <select id="kategori_usaha_id" name="kategori_usaha_id" style="width: 318px; padding: 8px;">
                {{-- Opsi ini penting agar admin bisa menghapus kategori --}}
                <option value="">-- Tidak Ada Kategori --</option>
                @foreach($kategoriUsaha as $kategori)
                    <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_usaha_id', $mitra->kategori_usaha_id) == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('kategori_usaha_id') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <hr style="margin: 20px 0;">

        <h3>Reset Password (Opsional)</h3>
        <p>Biarkan kosong jika Anda tidak ingin mengubah password Mitra.</p>

        {{-- Password Baru --}}
        <div style="margin-top: 10px;">
            <label for="password_baru">Password Baru</label><br>
            <input type="password" id="password_baru" name="password_baru" style="width: 300px; padding: 8px;">
             @error('password_baru') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Konfirmasi Password Baru --}}
        <div style="margin-top: 10px;">
            <label for="password_baru_confirmation">Konfirmasi Password Baru</label><br>
            <input type="password" id="password_baru_confirmation" name="password_baru_confirmation" style="width: 300px; padding: 8px;">
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan</button>
            <a href="{{ route('admin.mitra.index') }}" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
@endsection
