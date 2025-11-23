@extends('admin.layouts.app')
{{-- Pastikan ini menunjuk ke layout admin Anda yang benar --}}

@section('title', 'Manajemen User')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen User Pelanggan</h2>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">

            @if ($errors->has('alasan_blokir_id'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first('alasan_blokir_id') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle caption-top">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Nama User</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col">Email</th>
                            <th scope="col">Poin Reward</th>
                            <th scope="col">Status Akun</th>
                            <th scope="col">Tgl. Daftar</th>
                            <th scope="col" style="min-width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <strong class="text-dark">{{ $user->nama_lengkap }}</strong><br>
                                    <small class="text-muted">{{ $user->nomor_telepon ?? '-' }}</small>
                                </td>
                                <td>{{ $user->jenis_kelamin }}</td>
                                <td>{{ $user->email }}</td>

                                <td>{{ $user->poin_reward ?? 0 }}</td>

                                <td>
                                    <span class="badge {{ $user->status_akun == 'Diblokir' ? 'bg-danger' : 'bg-success' }}">
                                        {{ $user->status_akun ?? 'Aktif' }}
                                    </span>
                                    @if($user->status_akun == 'Diblokir' && $user->alasan_blokir)
                                        <small class="d-block text-danger mt-1" style="cursor: help;" title="Alasan: {{ $user->alasan_blokir }}">
                                            <i class="fas fa-info-circle me-1"></i>{{ $user->alasan_blokir }}
                                        </Ssmall>
                                    @endif
                                </td>

                                <td>{{ $user->created_at->format('d M Y') }}</td>

                                <td class="text-nowrap">

                                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if ($user->status_akun == 'Aktif' || $user->status_akun == 'aktif' || $user->status_akun == null)
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#blockUserModal-{{ $user->user_id }}" title="Blokir Akun">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('admin.users.unblock', $user->user_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin mengaktifkan kembali akun {{ $user->nama_lengkap }}?');">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Akun">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="blockUserModal-{{ $user->user_id }}" tabindex="-1" aria-labelledby="blockUserModalLabel-{{ $user->user_id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="blockUserModalLabel-{{ $user->user_id }}">Blokir Akun: {{ $user->nama_lengkap }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.users.block', $user->user_id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <p>Anda yakin ingin memblokir akun <strong>{{ $user->nama_lengkap }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label for="alasan-{{ $user->user_id }}" class="form-label">Pilih Alasan Blokir:</label>

                                                    <select name="alasan_blokir_id" id="alasan-{{ $user->user_id }}" class="form-select" required>
                                                        <option value="" disabled selected>-- Pilih Alasan --</option>

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
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> </div> </div> @endsection
