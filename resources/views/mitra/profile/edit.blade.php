@extends('mitra.layouts.app') {{-- Pastikan nama layout Anda benar --}}
@section('title', 'Edit Profil Saya')
@section('content')
    <h1>Edit Profil Usaha</h1>

    {{-- Tampilkan Pesan Sukses/Error --}}
    @if (session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif
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

    <form action="{{ route('mitra.profile.update') }}" method="POST">
        @csrf
        @method('PATCH') {{-- Gunakan PATCH karena kita update data --}}

        {{-- Input Nama Usaha --}}
        <div>
            <label for="nama_mitra">Nama Usaha / Mitra</label><br>
            <input type="text" id="nama_mitra" name="nama_mitra" value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required style="width: 300px; padding: 8px;">
            @error('nama_mitra') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Input Email (Readonly) --}}
        <div style="margin-top: 10px;">
            <label>Email Bisnis (Tidak dapat diubah)</label><br>
            <input type="email" value="{{ $mitra->email_bisnis }}" disabled readonly style="width: 300px; padding: 8px; background-color: #e9ecef;">
         </div>

         {{-- Input No Telp --}}
        <div style="margin-top: 10px;">
            <label for="nomor_telepon">Nomor Telepon (PIC)</label><br>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $mitra->nomor_telepon) }}" required style="width: 300px; padding: 8px;">
            @error('nomor_telepon') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Input Alamat --}}
        <div style="margin-top: 10px;">
            <label for="alamat">Alamat Lengkap Usaha</label><br>
            <textarea id="alamat" name="alamat" rows="3" style="width: 300px; padding: 8px;" required>{{ old('alamat', $mitra->alamat) }}</textarea>
            @error('alamat') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        {{-- Input Deskripsi --}}
        <div style="margin-top: 10px;">
            <label for="deskripsi">Deskripsi Singkat Usaha</label><br>
            <textarea id="deskripsi" name="deskripsi" rows="3" style="width: 300px; padding: 8px;">{{ old('deskripsi', $mitra->deskripsi) }}</textarea>
            @error('deskripsi') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

         {{-- Dropdown Kategori --}}
        <div style="margin-top: 10px;">
            <label for="kategori_usaha_id">Kategori Usaha</label><br>
            <select id="kategori_usaha_id" name="kategori_usaha_id" required style="width: 318px; padding: 8px;"> {{-- Sesuaikan width jika perlu --}}
                <option value="">-- Pilih Kategori --</option>
                {{-- Pastikan variabel $kategoriUsaha dikirim dari controller --}}
                @isset($kategoriUsaha)
                    @foreach($kategoriUsaha as $kategori)
                        <option value="{{ $kategori->kategori_usaha_id }}" {{ old('kategori_usaha_id', $mitra->kategori_usaha_id) == $kategori->kategori_usaha_id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                @endisset
            </select>
             @error('kategori_usaha_id') <span style="color: red;">{{ $message }}</span> @enderror
        </div>

        <hr style="margin: 20px 0;">
        <h3>Ubah Password (Kosongkan jika tidak ingin diubah)</h3>

         {{-- Input Password Lama --}}
         <div style="margin-top: 10px;">
            <label for="current_password">Password Saat Ini (Wajib diisi jika ingin ubah password)</label><br>
            <input type="password" id="current_password" name="current_password" style="width: 300px; padding: 8px;">
             @error('current_password') <span style="color: red;">{{ $message }}</span> @enderror
         </div>

         {{-- Input Password Baru --}}
         <div style="margin-top: 10px;">
            <label for="new_password">Password Baru</label><br>
            <input type="password" id="new_password" name="new_password" style="width: 300px; padding: 8px;">
             @error('new_password') <span style="color: red;">{{ $message }}</span> @enderror
         </div>

         {{-- Input Konfirmasi Password Baru --}}
         <div style="margin-top: 10px;">
            <label for="new_password_confirmation">Konfirmasi Password Baru</label><br>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" style="width: 300px; padding: 8px;">
         </div>

        <div style="margin-top: 20px;">
            <button type="submit" style="background: green; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer;">Simpan Perubahan Profil</button>
        </div>
    </form>
@endsection
