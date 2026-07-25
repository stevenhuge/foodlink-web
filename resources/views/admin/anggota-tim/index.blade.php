@extends('admin.layouts.app')

@section('title', 'Manajemen Anggota Tim')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Anggota Tim (Home)</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header py-3 d-flex justify-content-end">
            <a href="{{ route('admin.anggota-tim.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm me-1"></i> Tambah Anggota Tim
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="60">Foto</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotaTim as $anggota)
                            <tr>
                                <td>
                                    @if($anggota->foto_url)
                                        <img src="{{ $anggota->foto_url }}" alt="Foto {{ $anggota->nama }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><strong class="text-dark">{{ $anggota->nama }}</strong></td>
                                <td>{{ $anggota->jabatan }}</td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.anggota-tim.edit', $anggota->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAnggotaModal-{{ $anggota->id }}" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- [MODAL] Hapus -->
                            <div class="modal fade" id="deleteAnggotaModal-{{ $anggota->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.anggota-tim.destroy', $anggota->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center py-4">
                                                <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                                                <p class="mb-1">Anda yakin ingin menghapus data anggota tim ini?</p>
                                                <h4 class="text-dark mb-3">{{ $anggota->nama }}</h4>
                                                <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada anggota tim yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
