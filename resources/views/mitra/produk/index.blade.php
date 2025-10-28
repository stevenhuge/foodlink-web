@extends('mitra.layouts.app')
@section('title', 'Manajemen Produk')
@section('content')
    <h1>Manajemen Produk</h1>
    <a href="{{ route('mitra.produk.create') }}" style="background: green; color: white; padding: 10px; text-decoration: none;">
        + Tambah Produk Baru (Akan disimpan sebagai Draft)
    </a>

    @if (session('success'))
        <div style="padding: 10px; margin-top: 15px; background: #d4edda; color: #155724;">
            {{ session('success') }}
        </div>
    @endif

    <table border="1" style="width: 100%; margin-top: 20px; border-collapse: collapse;" cellpadding="5">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok Tersisa</th>
                <th>Status</th> <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produks as $produk)
                <tr style="@if($produk->status_produk == 'Ditarik') background: #f8f9fa; color: #6c757d; @endif">
                    <td>
                        @if($produk->foto_produk)
                            <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            (No-img)
                        @endif
                    </td>
                    <td>
                        {{ $produk->nama_produk }}<br>
                        <small>({{ $produk->tipe_penawaran }}) - ({{ $produk->kategori->nama_kategori ?? 'N/A' }})</small>
                    </td>
                    <td>
                        @if($produk->tipe_penawaran == 'Donasi')
                            Gratis
                        @else
                            <b style="color: red;">Rp {{ number_format($produk->harga_diskon) }}</b>
                        @endif
                    </td>
                    <td>{{ $produk->stok_tersisa }} / {{ $produk->stok_awal }}</td>

                    <td>
                        <span style="font-weight: bold; color:
                            @if($produk->status_produk == 'Tersedia') green;
                            @elseif($produk->status_produk == 'Habis') red;
                            @else #6c757d; @endif
                        ">
                            {{ $produk->status_produk }}
                            @if($produk->status_produk == 'Ditarik') (Draft) @endif
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('mitra.produk.edit', $produk->produk_id) }}" style="color: blue;">Edit</a>
                        <form action="{{ route('mitra.produk.destroy', $produk->produk_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; padding: 0;">Hapus</button>
                        </form>

                        <hr style="margin: 4px 0;">

                        @if($produk->status_produk == 'Tersedia')
                            <form action="{{ route('mitra.produk.unpublish', $produk->produk_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="color: orange; background: none; border: none; cursor: pointer; padding: 0;">Unpublish (Jadikan Draft)</button>
                            </form>
                        @elseif($produk->status_produk == 'Ditarik')
                            <form action="{{ route('mitra.produk.publish', $produk->produk_id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="color: green; background: none; border: none; cursor: pointer; padding: 0;">Publish (Jual Sekarang)</button>
                            </form>
                        @else
                            <small>(Status 'Habis')</small>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Anda belum memiliki produk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
