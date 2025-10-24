@extends('admin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
    <h1>Manajemen Admin</h1>
    <p>Hanya SuperAdmin yang dapat melihat halaman ini.</p>

    <a href="{{ route('admin.admins.create') }}" style="background: #007bff; color: white; padding: 10px; text-decoration: none;">
        + Tambah Admin Baru
    </a>

    <table border="1" style="width: 100%; margin-top: 20px; border-collapse: collapse;" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
                <tr>
                    <td>{{ $admin->admin_id }}</td>
                    <td>{{ $admin->nama_lengkap }}</td>
                    <td>{{ $admin->username }}</td>
                    <td>{{ $admin->role }}</td>
                    <td>
                        <a href="{{ route('admin.admins.edit', $admin) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus admin ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; border: none; background: none; cursor: pointer;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data admin.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
