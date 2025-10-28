@extends('admin.layouts.app')
@section('title', 'Manajemen Alasan Blokir')
@section('content')
    <h1>Manajemen Alasan Blokir Mitra</h1>
    <p>Kelola daftar alasan standar untuk memblokir akun mitra.</p>

    <a href="{{ route('admin.alasan-blokir.create') }}" style="background: green; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 15px;">
        + Tambah Alasan Baru
    </a>

    @if (session('success')) <div class="success-msg">{{ session('success') }}</div> @endif
    @if (session('error')) <div class="error-msg">{{ session('error') }}</div> @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Teks Alasan</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($alasanList as $alasan)
                <tr>
                    <td>{{ $alasan->alasan_id }}</td>
                    <td>{{ $alasan->alasan_text }}</td>
                    <td>
                        <a href="{{ route('admin.alasan-blokir.edit', $alasan->alasan_id) }}" style="color: blue;">Edit</a> |
                        <form action="{{ route('admin.alasan-blokir.destroy', $alasan->alasan_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus alasan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; padding: 0; font: inherit;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align: center;">Belum ada alasan blokir yang dibuat.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
