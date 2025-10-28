@extends('admin.layouts.app')
@section('title', 'Manajemen Mitra')
@section('content')
    <h1>Manajemen Mitra</h1>
    {{-- ... Notifikasi ... --}}
    @if ($errors->has('alasan_blokir_option_id'))
         <div class="error-msg">{{ $errors->first('alasan_blokir_option_id') }}</div>
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
                <tr style=" /* ... style baris ... */ ">
                    <td>{{ $m->nama_mitra }}<br><small>{{ $m->email_bisnis }}</small></td>
                    <td>{{ $m->kategoriUsaha->nama_kategori ?? '-' }}</td>
                    <td> /* ... status verifikasi ... */ </td>
                    <td>
                         <span style="font-weight: bold; color: {{ $m->status_akun == 'Diblokir' ? 'red' : 'green' }};">
                            {{ $m->status_akun }}
                        </span>
                        {{-- Tampilkan alasan jika diblokir (dari relasi) --}}
                        @if($m->status_akun == 'Diblokir' && $m->alasanBlokir)
                            <small style="display: block; cursor: help;" title="Alasan: {{ $m->alasanBlokir->alasan_text }}">(Lihat alasan)</small>
                        @endif
                    </td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>
                    <td style="white-space: nowrap;">
                        {{-- ... Tombol Verifikasi/Tolak, Detail, Edit ... --}}

                        {{-- === TOMBOL & FORM BLOKIR / AKTIFKAN === --}}
                        @if ($m->status_akun == 'Aktif')
                            {{-- Tombol untuk menampilkan form blokir --}}
                            <button onclick="toggleBlockForm('{{ $m->mitra_id }}')" style="background: orange; ..."> Blokir </button>
                            {{-- Form Blokir (Tersembunyi Awalnya) --}}
                            <form id="block-form-{{ $m->mitra_id }}" action="{{ route('admin.mitra.block', $m->mitra_id) }}" method="POST" style="display: none; margin-top: 5px; border: 1px solid orange; padding: 5px;" onsubmit="return confirm('Anda yakin ingin memblokir akun {{ $m->nama_mitra }}?');">
                                @csrf @method('PATCH')
                                <label for="alasan-{{ $m->mitra_id }}" style="font-size: 0.9em;">Pilih Alasan:</label><br>
                                <select name="alasan_blokir_option_id" id="alasan-{{ $m->mitra_id }}" required style="width: 150px; font-size: 0.9em;">
                                    <option value="">-- Pilih --</option>
                                    {{-- Loop pilihan alasan dari Controller --}}
                                    @foreach ($alasanBlokirOptions as $alasan)
                                    <option value="{{ $alasan->alasan_id }}">{{ $alasan->alasan_text }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" style="background: orange; ...">Konfirmasi Blokir</button>
                                <button type="button" onclick="toggleBlockForm('{{ $m->mitra_id }}')" style="background: grey; ...">Batal</button>
                            </form>
                        @else {{-- Jika status Diblokir --}}
                            {{-- Tombol Aktifkan --}}
                            <form action="{{ route('admin.mitra.unblock', $m->mitra_id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin ingin mengaktifkan kembali akun {{ $m->nama_mitra }}?');">
                                @csrf @method('PATCH')
                                <button type="submit" style="background: seagreen; ...">Aktifkan</button>
                            </form>
                        @endif
                        {{-- ============================================= --}}

                        {{-- ... Tombol Hapus (SuperAdmin) ... --}}
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align: center;">Belum ada mitra yang mendaftar.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Script JavaScript untuk toggle form blokir --}}
    <script>
        function toggleBlockForm(mitraId) {
            const form = document.getElementById(`block-form-${mitraId}`);
            if (form) { form.style.display = form.style.display === 'none' ? 'block' : 'none'; }
        }
    </script>
@endsection
