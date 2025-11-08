{{-- resources/views/admin/admins/_form.blade.php --}}

<!-- Tampilkan error validasi jika ada -->
@if ($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Oops! Ada kesalahan:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Layout Grid 2 Kolom untuk Info Dasar -->
<div class="row">
    <div class="col-md-6">
        <!-- Nama Lengkap -->
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            {{-- Perhatikan: $admin->nama_lengkap ?? '' menangani 'create' dan 'edit' --}}
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $admin->nama_lengkap ?? '') }}" required autofocus>
            @error('nama_lengkap')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <!-- Username -->
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $admin->username ?? '') }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div> <!-- End .row -->

<!-- Role -->
<div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
        {{-- Perhatikan: $admin->role ?? '' menangani 'create' dan 'edit' --}}
        <option value="Admin" @selected(old('role', $admin->role ?? 'Admin') == 'Admin')>Admin</option>
        <option value="SuperAdmin" @selected(old('role', $admin->role ?? '') == 'SuperAdmin')>SuperAdmin</option>
    </select>
    @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<hr class="my-4">

<!-- Bagian Password -->
{{-- Logika @if(empty($admin)) sudah diganti dengan @isset($admin) agar lebih jelas --}}
<h5 class="mb-3">@isset($admin) Reset Password (Opsional) @else Buat Password @endisset</h5>

<div class="row">
    <div class="col-md-6">
        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            {{-- Password 'required' HANYA jika ini form 'create' (!isset($admin)) --}}
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" @if(!isset($admin)) required @endif>

            @isset($admin)
                <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
            @endisset

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <!-- Konfirmasi Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" @if(!isset($admin)) required @endif>
        </div>
    </div>
</div>

<!-- Tombol Aksi (dari Variabel) -->
<div class="d-flex justify-content-between align-items-center mt-4">
    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Batal
    </a>
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> {{ $tombolTeks ?? 'Simpan' }}
    </button>
</div>
