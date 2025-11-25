@extends('admin.layouts.app')

@section('title', 'Daftar Sanggahan Blokir')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Sanggahan Akun Mitra</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Mitra</th>
                        <th>Email</th>
                        <th>Nomor PIC</th>
                        <th>Tanggal</th>
                        <th>Alasan Sanggahan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sanggahanList as $item)
                    <tr>
                        <td>{{ $item->mitra->nama_mitra }}</td>
                        <td>{{ $item->mitra->email_bisnis }}</td>
                        <td>{{ $item->mitra->nomor_telepon }}</td>
                        <td>{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            {{ Str::limit($item->alasan_sanggah, 50) }}

                            @if(strlen($item->alasan_sanggah) > 50)
                                <a href="#" class="text-primary text-decoration-none small fw-bold ms-1" data-bs-toggle="modal" data-bs-target="#modalAlasan{{ $item->sanggahan_id }}">
                                    [Lihat Selengkapnya]
                                </a>

                                <div class="modal fade" id="modalAlasan{{ $item->sanggahan_id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fs-6 fw-bold">Alasan Sanggahan Lengkap</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="p-3 bg-light rounded border">
                                                    <p class="mb-0 text-break" style="white-space: pre-wrap;">{{ $item->alasan_sanggah }}</p>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-1">
                                                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            {{-- Tombol Modal Lihat Bukti --}}
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $item->sanggahan_id }}">
                                Lihat {{ count($item->bukti_files ?? []) }} File
                            </button>
                        </td>
                        <td>
                            @if($item->status == 'Pending') <span class="badge bg-warning">Pending</span>
                            @elseif($item->status == 'Disetujui') <span class="badge bg-success">Disetujui (Akun Aktif)</span>
                            @else <span class="badge bg-danger">Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'Pending')
                                <form action="{{ route('admin.penyanggahan.update', $item->sanggahan_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm" onclick="return confirm('Terima sanggahan dan aktifkan kembali akun?')">Terima</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm" onclick="return confirm('Tolak sanggahan? Mitra tetap diblokir.')">Tolak</button>
                                </form>
                            @endif
                        </td>
                    </tr>

                    {{-- Modal Bukti --}}
                    {{-- Modal Bukti --}}
                    <div class="modal fade" id="modalBukti{{ $item->sanggahan_id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold text-dark">
                                        <i class="fas fa-folder-open me-2 text-primary"></i>
                                        Bukti Sanggahan: {{ $item->mitra->nama_mitra }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body bg-white">

                                    <div class="alert alert-secondary mb-4 border-0 shadow-sm">
                                        <h6 class="alert-heading fw-bold"><i class="fas fa-quote-left me-2"></i>Alasan Lengkap:</h6>
                                        <p class="mb-0" style="white-space: pre-wrap;">{{ $item->alasan_sanggah }}</p>
                                    </div>

                                    <h6 class="fw-bold mb-3 border-bottom pb-2">Lampiran Bukti ({{ count($item->bukti_files ?? []) }})</h6>

                                    <div class="row g-3">
                                        @if($item->bukti_files)
                                            @foreach($item->bukti_files as $file)
                                                @php
                                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                    $fileUrl = asset('storage/' . $file);
                                                @endphp

                                                <div class="col-md-4 col-sm-6">
                                                    <div class="card h-100 border shadow-sm hover-shadow">

                                                        {{-- TAMPILAN GAMBAR --}}
                                                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                                            <div class="ratio ratio-16x9 bg-light border-bottom">
                                                                <img src="{{ $fileUrl }}" class="object-fit-cover rounded-top" alt="Bukti Gambar">
                                                            </div>
                                                            <div class="card-body p-2 text-center">
                                                                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 stretched-link">
                                                                    <i class="fas fa-eye me-1"></i> Lihat Gambar Full
                                                                </a>
                                                            </div>

                                                        {{-- TAMPILAN PDF --}}
                                                        @elseif($ext == 'pdf')
                                                            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center p-4 bg-light rounded-top">
                                                                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                                <span class="small text-muted text-uppercase fw-bold">Dokumen PDF</span>
                                                            </div>
                                                            <div class="card-footer bg-white border-top-0 p-2">
                                                                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-danger w-100">
                                                                    <i class="fas fa-external-link-alt me-1"></i> Buka PDF
                                                                </a>
                                                            </div>

                                                        {{-- TAMPILAN DOKUMEN LAIN (DOCX, ZIP, dll) --}}
                                                        @else
                                                            <div class="card-body text-center d-flex flex-column justify-content-center align-items-center p-4 bg-light rounded-top">
                                                                <i class="fas fa-file-alt fa-3x text-secondary mb-2"></i>
                                                                <span class="small text-muted text-uppercase fw-bold">File {{ $ext }}</span>
                                                            </div>
                                                            <div class="card-footer bg-white border-top-0 p-2">
                                                                <a href="{{ $fileUrl }}" download class="btn btn-sm btn-secondary w-100">
                                                                    <i class="fas fa-download me-1"></i> Download File
                                                                </a>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12 text-center py-4 text-muted">
                                                <i class="fas fa-folder-open fa-2x mb-2"></i>
                                                <p>Tidak ada file bukti yang dilampirkan.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer bg-light">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
