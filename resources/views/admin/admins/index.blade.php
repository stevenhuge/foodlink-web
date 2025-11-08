{{-- resources/views/admin/admins/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Admin</h2>
    </div>

    <!-- Card Utama untuk Tabel -->
    <div class="card border-0 shadow-sm rounded-3">

        <!-- Card Header - untuk tombol Tambah Baru -->
        <div class="card-header py-3 d-flex justify-content-end">
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm me-1"></i> Tambah Admin Baru
            </a>
        </div>

        <div class="card-body">
            <!-- Wrapper Tabel Responsif -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Username</th>
                            <th scope="col">Role</th>
                            <th scope="col" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr>
                                <td><strong class="text-dark">{{ $admin->nama_lengkap }}</strong></td>
                                <td>{{ $admin->username }}</td>
                                <td>
                                    <!-- Gunakan Badges untuk Role -->
                                    @if($admin->role == 'SuperAdmin')
                                        <span class="badge bg-danger rounded-pill">SuperAdmin</span>
                                    @elseif($admin->role == 'Admin')
                                        <span class="badge bg-primary rounded-pill">Admin</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">{{ $admin->role }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.admins.edit', $admin->admin_id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Tombol Hapus (Pemicu Modal) -->
                                    {{-- Hanya tampilkan jika BUKAN admin yang sedang login --}}
                                    @if(auth()->guard('admin')->id() != $admin->admin_id)
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAdminModal-{{ $admin->admin_id }}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- [MODAL] Hapus Admin -->
                            @if(auth()->guard('admin')->id() != $admin->admin_id)
                            <div class="modal fade" id="deleteAdminModal-{{ $admin->admin_id }}" tabindex="-1" aria-labelledby="deleteAdminModalLabel-{{ $admin->admin_id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <!-- Form Hapus di dalam Modal -->
                                        <form action="{{ route('admin.admins.destroy', $admin->admin_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteAdminModalLabel-{{ $admin->admin_id }}">
                                                    <i class="fas fa-exclamation-triangle text-danger me-2"></i> Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body text-center py-4">
                                                <!-- Ikon "Bagus" (Warning) -->
                                                <i class="fas fa-user-times fa-3x text-danger mb-3"></i>
                                                <p class="mb-1">Anda yakin ingin menghapus admin:</p>
                                                <h4 class="text-dark mb-3">{{ $admin->nama_lengkap }} ({{ $admin->username }})</h4>
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
                            @endif
                            <!-- [AKHIR MODAL] -->

                        @empty
                            <!-- Tampilan Jika Tabel Kosong -->
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada admin lain.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> <!-- End .table-responsive -->
        </div> <!-- End .card-body -->
    </div> <!-- End .card -->
@endsection
