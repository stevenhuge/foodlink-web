{{-- resources/views/admin/admins/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
    <h1>Manajemen Admin</h1>

    <a href="{{ route('admin.admins.create') }}" style="background: green; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px;">
        + Tambah Admin Baru
    </a>

    <table>
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
                <tr>
                    <td>{{ $admin->nama_lengkap }}</td>
                    <td>{{ $admin->username }}</td>
                    <td>{{ $admin->role }}</td>
                    <td>
                        <a href="{{ route('admin.admins.edit', $admin->admin_id) }}">Edit</a>
                        {{-- Form Hapus akan kita tambahkan nanti --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada admin lain.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
