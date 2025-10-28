{{-- resources/views/admin/kategori-usaha/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori Usaha')

@section('content')
    <h1>Manajemen Kategori Usaha</h1>

    <a href="{{ route('admin.kategori-usaha.create') }}" style="background: green; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px;">
        + Tambah Kategori Baru
    </a>

    {{-- Tampilkan pesan error (misal gagal hapus) --}}
    @if (session('error'))
        <div class="error-msg" style="margin-top: 15px;">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($kategoris as $kategori)
                <tr>
                    <td>{{ $kategori->kategori_usaha_id }}</td>
                    <td>{{ $kategori->nama_kategori }}</td>
                    <td>
                        <a href="{{ route('admin.kategori-usaha.edit', $kategori->kategori_usaha_id) }}" style="color: blue; text-decoration: none;">Edit</a> |

                        {{-- === TAMBAHKAN FORM HAPUS INI === --}}
                        <form action="{{ route('admin.kategori-usaha.destroy', $kategori->kategori_usaha_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus kategori \'{{ $kategori->nama_kategori }}\'?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; padding: 0; font: inherit;">Hapus</button>
                        </form>
                        {{-- ============================ --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Belum ada kategori usaha.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
