{{-- resources/views/admin/alasan-blokir/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Manajemen Alasan Blokir')

@section('content')
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Alasan Blokir</h2>
    </div>

    <!-- Card Utama untuk Tabel -->
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Card Header - untuk tombol Tambah Baru -->
        <div class="card-header py-3 d-flex justify-content-end">
            <a href="{{ route('admin.alasan-blokir.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm me-1"></i> Tambah Alasan Baru
            </a>
        </div>

        <div class="card-body">
            <p class="text-muted">Kelola daftar alasan standar untuk memblokir akun mitra.</p>

            <!-- Wrapper Tabel Responsif -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 100px;">ID</th>
                            <th scope="col">Teks Alasan</th>
                            <th scope="col" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alasanList as $alasan)
                            <tr>
                                <td>{{ $alasan->alasan_id }}</td>
                                <td><strong class="text-dark">{{ $alasan->alasan_text }}</strong></td>
                                <td class="text-nowrap">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.alasan-blokir.edit', $alasan->alasan_id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Tombol Hapus (Pemicu Modal) -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAlasanModal-{{ $alasan->alasan_id }}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- [MODAL] Hapus Alasan -->
                            <div class="modal fade" id="deleteAlasanModal-{{ $alasan->alasan_id }}" tabindex="-1" aria-labelledby="deleteAlasanModalLabel-{{ $alasan->alasan_id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <!-- Form Hapus di dalam Modal -->
                                        <form action="{{ route('admin.alasan-blokir.destroy', $alasan->alasan_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteAlasanModalLabel-{{ $alasan->alasan_id }}">
                                                    <i class="fas fa-exclamation-triangle text-danger me-2"></i> Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body text-center py-4">
                                                <!-- Ikon Visual ("Image") -->
                                                <i class="fas fa-file-alt fa-3x text-danger mb-3"></i>
                                                <p class="mb-1">Anda yakin ingin menghapus alasan:</p>
                                                <h4 class="text-dark mb-3">"{{ $alasan->alasan_text }}"</h4>
                                                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                            </div>

                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="fas fa-times me-1"></i> Batal
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt me-1"></i> Ya, Hapus
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- [AKHIR MODAL] -->

                        @empty
                            <!-- Tampilan Jika Tabel Kosong -->
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada alasan blokir yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- End .table-responsive -->
        </div> <!-- End .card-body -->
    </div> <!-- End .card -->
@endsection
