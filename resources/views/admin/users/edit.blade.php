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
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="form-group">
        <label for="poin_reward">Poin Reward</label>
        <input type="number" name="poin_reward" id="poin_reward" class="form-control" value="{{ old('poin_reward', $user->poin_reward) }}" required>
    </div>

    <p><small>Catatan: Password tidak dapat diubah dari halaman ini.</small></p>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
</form>

@endsection
