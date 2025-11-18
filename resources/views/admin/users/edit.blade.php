@extends('admin.layouts.app') {{-- Sesuaikan layout admin Anda --}}

@section('title', 'Edit User')

@section('content')
<h1>Edit User: {{ $user->nama_lengkap }}</h1>

<form action="{{ route('admin.users.update', $user->user_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="nama_lengkap">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required>
    </div>

    <div class="form-group">
        <label for="nomor_telepon">Nomor Telepon</label>
        <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control" value="{{ old('nomor_telepon', $user->nomor_telepon) }}" required>
    </div>

    <div class="form-group">
        <label for="jenis_kelamin">Jenis Kelamin</label>
        <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>

            @php
                $selectedJenisKelamin = old('jenis_kelamin', $user->jenis_kelamin ?? 'Pilih');
            @endphp

            <option value="Pilih" {{ $selectedJenisKelamin == 'Pilih' ? 'selected' : '' }} disabled>Pilih</option>
            <option value="Laki-laki" {{ $selectedJenisKelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ $selectedJenisKelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="form-group">
        <label for="poin_reward">Poin Reward</label>
        <input type="number" name="poin_reward" id="poin_reward" class="form-control" value="{{ old('poin_reward', $user->poin_reward) }}" required>
    </div>

    <div class="form-group mb-3">
        <label for="password_hash">Password</label>
        {{-- Input form menggunakan nama kolom tabel --}}
        <input type="password" name="password_hash" id="password_hash"
            class="form-control @error('password_hash') is-invalid @enderror"
            placeholder="Minimal 8 karakter (Opsional)">
            @error('password_hash') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <p><small class="text-muted">Catatan: Kolom password dikosongkan jika Anda tidak ingin mengubah password.</small></p>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
</form>

@endsection
