@extends('mitra.layouts.app')
@section('title', 'Inbox Barter')
@section('content')
    <h1>Inbox Barter</h1>
    <p>Kelola tawaran barter yang Anda kirim dan terima.</p>

    {{-- Tampilkan notifikasi jika ada --}}
    @if (session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif

    <!-- ============================================= -->
    <!-- Bagian 1: Tawaran Diterima (Masuk) -->
    <!-- ============================================= -->
    <h2>Tawaran Diterima (Masuk)</h2>
    <table border="1" style="width: 100%; border-collapse: collapse;" cellpadding="5">
        <thead>
            <tr>
                <th>Dari Mitra</th>
                <th>Barang Ditawarkan</th> {{-- Kolom digabung --}}
                <th>Meminta Produk Anda</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tawaranDiterima as $tawaran)
            <tr>
                <td>{{ $tawaran->pengajuMitra->nama_mitra ?? 'N/A' }}</td>

                {{-- === Logika Tampilan Tawaran (Opsi 1 vs 2) === --}}
                <td>
                    @if($tawaran->produk_ditawarkan_id)
                        <strong>(Produk)</strong> {{ $tawaran->produkDitawarkan->nama_produk ?? 'N/A' }}
                        {{-- === TAMBAHKAN JUMLAH DI SINI === --}}
                        <br><small>Jumlah: {{ $tawaran->jumlah_ditawarkan ?? 1 }}</small> {{-- Tampilkan jumlah --}}
                        {{-- ============================== --}}
                        @if($tawaran->produkDitawarkan && $tawaran->produkDitawarkan->foto_produk)
                            <img src="{{ Storage::url($tawaran->produkDitawarkan->foto_produk) }}" alt="Tawaran Produk" style="width: 60px; height: 60px; object-fit: cover; display: block; margin-top: 5px;">
                        @endif
                    @else
                        <strong>(Manual)</strong> {{ $tawaran->nama_barang_manual }}
                        @if($tawaran->foto_barang_manual)
                            <img src="{{ Storage::url($tawaran->foto_barang_manual) }}" alt="Tawaran Manual" style="width: 60px; height: 60px; object-fit: cover; display: block; margin-top: 5px;">
                        @endif
                    @endif
                </td>
                {{-- ========================================== --}}

                <td>{{ $tawaran->produkDiminta->nama_produk ?? 'N/A' }}</td>
                <td>{{ $tawaran->status_barter }}</td>
                <td>
                    @if($tawaran->status_barter == 'Diajukan')
                        <form action="{{ route('mitra.barter.accept', $tawaran->barter_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: green; color: white; padding: 3px 6px; border: none; cursor: pointer;">Terima</button>
                        </form>
                        <form action="{{ route('mitra.barter.reject', $tawaran->barter_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: red; color: white; padding: 3px 6px; border: none; cursor: pointer;">Tolak</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center;">Tidak ada tawaran barter yang masuk.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- ============================================= -->
    <!-- Bagian 2: Tawaran Terkirim (Keluar) -->
    <!-- ============================================= -->
    <h2 style="margin-top: 30px;">Tawaran Terkirim (Keluar)</h2>
    <table border="1" style="width: 100%; border-collapse: collapse;" cellpadding="5">
        <thead>
            <tr>
                <th>Ke Mitra</th>
                <th>Meminta Produk</th>
                <th>Barang Anda Tawarkan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tawaranTerkirim as $tawaran)
            <tr>
                <td>{{ $tawaran->penerimaMitra->nama_mitra ?? 'N/A' }}</td>
                <td>{{ $tawaran->produkDiminta->nama_produk ?? 'N/A' }}</td>

                {{-- === Logika Tampilan Tawaran (Opsi 1 vs 2) === --}}
                <td>
                    @if($tawaran->produk_ditawarkan_id)
                        <strong>(Produk)</strong> {{ $tawaran->produkDitawarkan->nama_produk ?? 'N/A' }}
                        {{-- === TAMBAHKAN JUMLAH DI SINI === --}}
                        ({{ $tawaran->jumlah_ditawarkan ?? 1 }} pcs) {{-- Tampilkan jumlah --}}
                        {{-- ============================== --}}
                    @else
                        <strong>(Manual)</strong> {{ $tawaran->nama_barang_manual }}
                    @endif
                </td>
                {{-- ========================================== --}}

                <td>{{ $tawaran->status_barter }}</td>
                <td>
                    @if($tawaran->status_barter == 'Diajukan')
                        <form action="{{ route('mitra.barter.cancel', $tawaran->barter_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" style="background: grey; color: white; padding: 3px 6px; border: none; cursor: pointer;">Batalkan</button>
                        </form>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align: center;">Anda belum mengirim tawaran barter.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
