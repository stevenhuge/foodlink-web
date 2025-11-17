@extends('admin.layouts.app')
{{-- Sesuaikan dengan layout Admin Anda --}}

@section('title', 'Kelola Rekening Bank (SuperAdmin)')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Kelola Rekening Bank (SuperAdmin)</h2>
        <a href="{{ route('admin.pemasukan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Kembali ke Pemasukan
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tambah Rekening Baru</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.rekening-bank.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_bank" class="form-label">Nama Bank</label>
                            <input type="text" name="nama_bank" id="nama_bank" class="form-control"
                                   value="{{ old('nama_bank') }}" placeholder="Contoh: BCA, BNI, Mandiri" required>
                        </div>
                        <div class="mb-3">
                            <label for="nomor_rekening" class="form-label">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" class="form-control"
                                   value="{{ old('nomor_rekening') }}" placeholder="Contoh: 1234567890" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_pemilik" class="form-label">Nama Pemilik Rekening</label>
                            <input type="text" name="nama_pemilik" id="nama_pemilik" class="form-control"
                                   value="{{ old('nama_pemilik') }}" placeholder="Sesuai nama di buku tabungan" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-1"></i> Tambah Rekening
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Rekening Bank Tersimpan</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Bank</th>
                                    <th>Nomor Rekening</th>
                                    <th>Nama Pemilik</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekeningBank as $rekening)
                                    <tr>
                                        <td><strong>{{ $rekening->nama_bank }}</strong></td>
                                        <td>{{ $rekening->nomor_rekening }}</td>
                                        <td>{{ $rekening->nama_pemilik }}</td>
                                        <td class="text-nowrap">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#editModal-{{ $rekening->rekening_id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.rekening-bank.destroy', $rekening->rekening_id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Anda yakin ingin menghapus rekening {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Anda belum menambahkan rekening bank.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($rekeningBank as $rekening)
    <div class="modal fade" id="editModal-{{ $rekening->rekening_id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $rekening->rekening_id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-{{ $rekening->rekening_id }}">Edit Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.rekening-bank.update', $rekening->rekening_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_bank_{{ $rekening->rekening_id }}" class="form-label">Nama Bank</label>
                            <input type="text" name="nama_bank" id="edit_nama_bank_{{ $rekening->rekening_id }}" class="form-control"
                                   value="{{ old('nama_bank', $rekening->nama_bank) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nomor_rekening_{{ $rekening->rekening_id }}" class="form-label">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" id="edit_nomor_rekening_{{ $rekening->rekening_id }}" class="form-control"
                                   value="{{ old('nomor_rekening', $rekening->nomor_rekening) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_pemilik_{{ $rekening->rekening_id }}" class="form-label">Nama Pemilik Rekening</label>
                            <input type="text" name="nama_pemilik" id="edit_nama_pemilik_{{ $rekening->rekening_id }}" class="form-control"
                                   value="{{ old('nama_pemilik', $rekening->nama_pemilik) }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

@endsection
