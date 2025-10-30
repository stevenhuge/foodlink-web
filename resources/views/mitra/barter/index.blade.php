@extends('mitra.layouts.app')
@section('title', 'Marketplace Barter')
@section('content')
    <h1>Marketplace Barter</h1>
    <p>Lihat produk dari mitra lain yang tersedia untuk dibarter.</p>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif

    {{-- Tabel Produk Marketplace --}}
    <table> {{-- Hapus inline style agar ikut layout --}}
        <thead>
            <tr>
                <th>Foto</th>
                <th>Nama Produk</th>
                <th>Mitra Pemilik</th> {{-- Pastikan header ini ada --}}
                <th>Harga Perkiraan</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produks as $produk)
                <tr>
                    {{-- Foto Produk --}}
                    <td>
                        @if($produk->foto_produk)
                            <img src="{{ Storage::url($produk->foto_produk) }}" alt="{{ $produk->nama_produk }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                        @else
                            <div style="width: 60px; height: 60px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 4px; font-size: 0.8em; color: #666;">No Img</div>
                        @endif
                    </td>
                    {{-- Nama Produk --}}
                    <td>{{ $produk->nama_produk }}</td>

                    {{-- === PERBAIKAN DI SINI === --}}
                    {{-- Tampilkan nama mitra pemilik produk DARI relasi $produk->mitra --}}
                    <td>{{ $produk->mitra->nama_mitra ?? 'N/A' }}</td>
                    {{-- ======================== --}}

                    {{-- Harga & Stok --}}
                    <td>Rp {{ number_format($produk->harga_diskon) }}</td>
                    <td>{{ $produk->stok_tersisa }}</td>

                    {{-- Tombol Aksi (dengan Logika Cooldown) --}}
                    <td>
                        @if($pendingOffers->contains($produk->produk_id))
                            <button style="background: grey; color: white; padding: 5px 8px; border: none; cursor: not-allowed; border-radius: 3px; font-size: 0.9em;" disabled>
                                Diajukan (Cooldown)
                            </button>
                        @else
                            <a href="{{ route('mitra.barter.create', $produk->produk_id) }}" style="background: blue; color: white; padding: 5px 8px; text-decoration: none; border-radius: 3px; font-size: 0.9em;">
                                Ajukan Barter
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                {{-- Pesan "Kosong" --}}
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada produk dari mitra lain yang tersedia untuk barter saat ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
