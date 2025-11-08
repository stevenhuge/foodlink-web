@extends('admin.layouts.app')
{{-- Pastikan ini menunjuk ke layout admin Anda yang benar --}}

@section('title', 'Manajemen Mitra')

@section('content')

    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Mitra</h2>
        {{-- <a href="#" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm me-1"></i> Tambah Mitra Baru
        </a> --}}
    </div>

    <!-- Card Utama untuk Tabel -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">

            <!-- Tampilkan error validasi spesifik (jika ada) -->
            @if ($errors->has('alasan_blokir_option_id'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first('alasan_blokir_option_id') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Wrapper Tabel Responsif -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle caption-top">
                    {{-- <caption>Total Mitra: {{ $mitra->count() }}</caption> --}}
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nama Mitra</th>
                            <th scope="col">Kategori Usaha</th>
                            <th scope="col">Status Verifikasi</th>
                            <th scope="col">Status Akun</th>
                            <th scope="col">Tgl. Daftar</th>
                            <th scope="col" style="min-width: 210px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mitra as $m)
                            <tr>
                                <!-- Nama Mitra -->
                                <td>
                                    <strong class="text-dark">{{ $m->nama_mitra }}</strong><br>
                                    <small class="text-muted">{{ $m->email_bisnis }}</small>
                                </td>

                                <!-- Kategori -->
                                <td>{{ $m->kategoriUsaha->nama_kategori ?? '-' }}</td>

                                <!-- Status Verifikasi (dengan Badges) -->
                                <td>
                                    @if($m->status_verifikasi == 'Pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($m->status_verifikasi == 'Disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($m->status_verifikasi == 'Ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $m->status_verifikasi }}</span>
                                    @endif
                                </td>

                                <!-- Status Akun (dengan Badges) -->
                                <td>
                                    <span class="badge {{ $m->status_akun == 'Diblokir' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $m->status_akun }}
                                    </span>
                                    @if($m->status_akun == 'Diblokir' && $m->alasanBlokir)
                                        <small class="d-block text-danger mt-1" style="cursor: help;" title="Alasan: {{ $m->alasanBlokir->alasan_text }}">
                                            <i class="fas fa-info-circle me-1"></i>{{ $m->alasanBlokir->alasan_text }}
                                        </small>
                                    @endif
                                </td>

                                <!-- Tanggal Daftar -->
                                <td>{{ $m->created_at->format('d M Y') }}</td>

                                <!-- Tombol Aksi -->
                                <td class="text-nowrap">

                                    <!-- Aksi Verifikasi (jika pending) -->
                                    @if ($m->status_verifikasi == 'Pending' && $m->status_akun == 'Aktif')
                                        <form method="POST" action="{{ route('admin.mitra.verify', $m->mitra_id) }}" class="d-inline" onsubmit="return confirm('Setujui verifikasi mitra {{ $m->nama_mitra }}?');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Setujui Verifikasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.mitra.reject', $m->mitra_id) }}" class="d-inline" onsubmit="return confirm('Tolak verifikasi mitra {{ $m->nama_mitra }}?');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Tolak Verifikasi">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Aksi Detail & Edit -->
                                    <a href="{{ route('admin.mitra.show', $m->mitra_id) }}" class="btn btn-info btn-sm text-white" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.mitra.edit', $m->mitra_id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Aksi Blokir / Aktifkan -->
                                    @if ($m->status_akun == 'Aktif')
                                        <!-- Tombol Pemicu Modal -->
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#blockMitraModal-{{ $m->mitra_id }}" title="Blokir Akun">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <!-- Form Aktifkan -->
                                        <form action="{{ route('admin.mitra.unblock', $m->mitra_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mengaktifkan kembali akun {{ $m->nama_mitra }}?');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Akun">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Hapus (jika diperlukan) -->
                                    {{-- ... --}}
                                </td>
                            </tr>

                            <!-- [MODAL] Blokir Mitra (dibuat unik per mitra) -->
                            @if ($m->status_akun == 'Aktif')
                            <div class="modal fade" id="blockMitraModal-{{ $m->mitra_id }}" tabindex="-1" aria-labelledby="blockMitraModalLabel-{{ $m->mitra_id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="blockMitraModalLabel-{{ $m->mitra_id }}">Blokir Akun: {{ $m->nama_mitra }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <!-- Form di dalam Modal -->
                                        <form action="{{ route('admin.mitra.block', $m->mitra_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>Anda yakin ingin memblokir akun <strong>{{ $m->nama_mitra }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label for="alasan-{{ $m->mitra_id }}" class="form-label">Pilih Alasan Blokir:</label>
                                                    <select name="alasan_blokir_option_id" id="alasan-{{ $m->mitra_id }}" class="form-select" required>
                                                        <option value="" disabled selected>-- Pilih Alasan --</option>
                                                        {{-- Loop pilihan alasan dari Controller --}}
                                                        @foreach ($alasanBlokirOptions as $alasan)
                                                            <option value="{{ $alasan->alasan_id }}">{{ $alasan->alasan_text }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Blokir Akun</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- [AKHIR MODAL] -->

                        @empty
                            <!-- Tampilan Jika Tabel Kosong -->
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada mitra yang mendaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- End .table-responsive -->

        </div> <!-- End .card-body -->
    </div> <!-- End .card -->

    <!-- Script JS tidak lagi diperlukan karena kita menggunakan Modal Bootstrap -->
    {{-- <script> ... </script> --}}

@endsection
