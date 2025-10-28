@extends('admin.layouts.app')

@section('title', 'Manajemen Mitra')

@section('content')
    <h1>Manajemen Mitra</h1>
    <p>Daftar semua mitra yang terdaftar.</p>

    {{-- Notifikasi Sukses/Error --}}
    @if (session('success'))
        <div class="success-msg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error-msg">{{ session('error') }}</div>
    @endif
    {{-- Tampilkan error validasi alasan blokir --}}
    @if ($errors->has('alasan_blokir'))
         <div class="error-msg">{{ $errors->first('alasan_blokir') }}</div>
    @endif


    <table>
        <thead>
            <tr>
                <th>Nama Mitra</th>
                <th>Kategori Usaha</th>
                <th>Status Verifikasi</th>
                <th>Status Akun</th>
                <th>Tgl. Daftar</th>
                <th style="min-width: 250px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mitra as $m)
                <tr style="
                    @if($m->status_verifikasi == 'Pending') background: #fffbe6; @endif
                    @if($m->status_akun == 'Diblokir') background: #e2e3e5; color: #6c757d; @endif
                ">
                    <td>{{ $m->nama_mitra }}<br><small>{{ $m->email_bisnis }}</small></td>
                    <td>{{ $m->kategoriUsaha->nama_kategori ?? '-' }}</td>
                    <td>
                        <span style="font-weight: bold; color: {{ $m->status_akun == 'Diblokir' ? '#6c757d' : ($m->status_verifikasi == 'Verified' ? 'green' : ($m->status_verifikasi == 'Rejected' ? 'red' : 'orange')) }};">
                            {{ $m->status_verifikasi }}
                        </span>
                    </td>
                    <td>
                         <span style="font-weight: bold; color: {{ $m->status_akun == 'Diblokir' ? 'red' : 'green' }};">
                            {{ $m->status_akun }}
                        </span>
                        {{-- Tampilkan alasan jika diblokir --}}
                        @if($m->status_akun == 'Diblokir' && $m->alasan_blokir)
                            <small style="display: block; cursor: help;" title="Alasan: {{ $m->alasan_blokir }}">(Lihat alasan)</small>
                        @endif
                    </td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>
                    <td style="white-space: nowrap;">
                        {{-- Tombol Verifikasi/Tolak --}}
                        @if ($m->status_verifikasi == 'Pending' && $m->status_akun == 'Aktif')
                            <form method="POST" action="{{ route('admin.mitra.verify', $m->mitra_id) }}" style="display: inline;"> @csrf @method('PATCH') <button type="submit" style="color: green; background: #d4edda; border: 1px solid green; cursor: pointer; padding: 3px 6px; font-size: 0.9em;">Setujui</button> </form>
                            <form method="POST" action="{{ route('admin.mitra.reject', $m->mitra_id) }}" style="display: inline;"> @csrf @method('PATCH') <button type="submit" style="color: red; background: #f8d7da; border: 1px solid red; cursor: pointer; padding: 3px 6px; font-size: 0.9em;">Tolak</button> </form>
                        @endif

                        {{-- Tombol Detail & Edit --}}
                        <a href="{{ route('admin.mitra.show', $m->mitra_id) }}" style="background: grey; color: white; padding: 3px 8px; text-decoration: none; font-size: 0.9em; border-radius: 3px;"> Detail </a>
                        <a href="{{ route('admin.mitra.edit', $m->mitra_id) }}" style="background: blue; color: white; padding: 3px 8px; text-decoration: none; font-size: 0.9em; border-radius: 3px;"> Edit </a>

                        {{-- === TOMBOL BLOKIR / AKTIFKAN DENGAN ALASAN === --}}
                        @if ($m->status_akun == 'Aktif')
                            <button onclick="confirmBlock('{{ route('admin.mitra.block', $m->mitra_id) }}', '{{ $m->nama_mitra }}')"
                                    style="background: orange; color: white; border: none; padding: 3px 8px; cursor: pointer; font-size: 0.9em; border-radius: 3px;">
                                Blokir
                            </button>
                        @else {{-- Jika status Diblokir --}}
                            <form action="{{ route('admin.mitra.unblock', $m->mitra_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin mengaktifkan kembali akun {{ $m->nama_mitra }}?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: seagreen; color: white; border: none; padding: 3px 8px; cursor: pointer; font-size: 0.9em; border-radius: 3px;">Aktifkan</button>
                            </form>
                        @endif
                        {{-- ============================================= --}}

                        {{-- Tombol Hapus (Hanya SuperAdmin) --}}
                        @if(auth()->guard('admin')->user()->role === 'SuperAdmin')
                            <form action="{{ route('admin.mitra.destroy', $m->mitra_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin menghapus mitra {{ $m->nama_mitra }}? Tindakan ini tidak bisa dibatalkan.');"> @csrf @method('DELETE') <button type="submit" style="background: darkred; color: white; border: none; padding: 3px 8px; cursor: pointer; font-size: 0.9em; border-radius: 3px;">Hapus</button> </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center;">Belum ada mitra yang mendaftar.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Script JavaScript untuk Prompt Blokir (Letakkan di akhir @section('content')) --}}
    <script>
        function confirmBlock(actionUrl, mitraName) {
            let reason = prompt(`Masukkan alasan memblokir akun ${mitraName} (minimal 10 karakter):`);
            if (reason === null) { return false; }
            reason = reason.trim();
            if (reason.length < 10) { alert('Alasan terlalu pendek. Minimal 10 karakter.'); return false; }
            if (reason.length > 500) { alert('Alasan terlalu panjang. Maksimal 500 karakter.'); return false; }

            let form = document.createElement('form');
            form.method = 'POST'; form.action = actionUrl; form.style.display = 'none';
            let csrfInput = document.createElement('input'); csrfInput.type = 'hidden'; csrfInput.name = '_token'; csrfInput.value = '{{ csrf_token() }}'; form.appendChild(csrfInput);
            let methodInput = document.createElement('input'); methodInput.type = 'hidden'; methodInput.name = '_method'; methodInput.value = 'PATCH'; form.appendChild(methodInput);
            let reasonInput = document.createElement('input'); reasonInput.type = 'hidden'; reasonInput.name = 'alasan_blokir'; reasonInput.value = reason; form.appendChild(reasonInput);
            document.body.appendChild(form); form.submit();
        }
    </script>
@endsection
