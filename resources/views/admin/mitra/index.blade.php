@extends('admin.layouts.app')

@section('title', 'Manajemen Mitra')

@section('content')
    <h1>Manajemen Mitra</h1>
    <p>Daftar semua mitra yang terdaftar.</p>

    @if (session('success'))
        <div style="padding: 10px; margin-bottom: 15px; background: #d4edda; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    <table border="1" style="width: 100%; margin-top: 20px; border-collapse: collapse;" cellpadding="5">
        <thead>
            <tr>
                <th>Nama Mitra</th>
                <th>Email Bisnis</th>
                <th>Status</th>
                <th>Tgl. Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mitra as $m)
                <tr style="@if($m->status_verifikasi == 'Pending') background: #fffbe6; @endif">
                    <td>{{ $m->nama_mitra }}</td>
                    <td>{{ $m->email_bisnis }}</td>
                    <td>
                        <span style="font-weight: bold; color:
                            @if($m->status_verifikasi == 'Verified') green;
                            @elseif($m->status_verifikasi == 'Rejected') red;
                            @else orange; @endif
                        ">
                            {{ $m->status_verifikasi }}
                        </span>
                    </td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>

                    <td>
                        @if ($m->status_verifikasi == 'Pending')
                            <form method="POST" action="{{ route('admin.mitra.verify', $m->mitra_id) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type.submit" style="color: green; background: #d4edda; border: 1px solid green; cursor: pointer;">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.mitra.reject', $m->mitra_id) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="color: red; background: #f8d7da; border: 1px solid red; cursor: pointer;">Tolak</button>
                            </form>
                        @endif

                        <a href="{{ route('admin.mitra.show', $m->mitra_id) }}" style="background: grey; color: white; padding: 5px 8px; text-decoration: none;">
                            Detail
                        </a>

                        @if(auth()->guard('admin')->user()->role === 'SuperAdmin')
                            <a href="{{ route('admin.mitra.edit', $m->mitra_id) }}" style="background: blue; color: white; padding: 5px 8px; text-decoration: none;">
                                Edit
                            </a>

                            <form action="{{ route('admin.mitra.destroy', $m->mitra_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus mitra {{ $m->nama_mitra }}? Tindakan ini tidak bisa dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: darkred; color: white; border: none; padding: 5px 8px; cursor: pointer;">Hapus</button>
                            </form>
                        @endif
                    </td>
                    </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada mitra yang mendaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
