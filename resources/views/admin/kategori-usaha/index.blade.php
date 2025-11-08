{{-- resources/views/admin/kategori-usaha/index.blade.php --}}
@extends('admin.layouts.app')
{{-- Pastikan ini menunjuk ke layout admin Anda yang benar --}}

@section('title', 'Manajemen Kategori Usaha')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h3 mb-0 text-gray-800">Manajemen Kategori Usaha</h2>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">

        <div class="card-header py-3 d-flex justify-content-end">
            <a href="{{ route('admin.kategori-usaha.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus fa-sm me-1"></i> Tambah Kategori Baru
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 100px;">ID</th>
                            <th scope="col">Nama Kategori</th>
                            <th scope="col" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kategoris as $kategori)
                            <tr>
                                <td>{{ $kategori->kategori_usaha_id }}</td>
                                <td><strong class="text-dark">{{ $kategori->nama_kategori }}</strong></td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.kategori-usaha.edit', $kategori->kategori_usaha_id) }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{--
                                        PERUBAHAN BAGIAN 1: Form dan Tombol Hapus
                                        - Form Hapus: Diberi ID unik dan `onsubmit` dihapus.
                                        - Tombol Hapus:
                                          - Diubah menjadi `type="button"` (agar tidak langsung submit).
                                          - Diberi atribut `data-bs-toggle="modal"` dan `data-bs-target="#deleteModal"`.
                                          - Diberi `data-nama` dan `data-form-id` untuk diteruskan ke JavaScript.
                                    --}}

                                    <form action="{{ route('admin.kategori-usaha.destroy', $kategori->kategori_usaha_id) }}" method="POST" class="d-inline" id="form-hapus-{{ $kategori->kategori_usaha_id }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button" class="btn btn-danger btn-sm" title="Hapus"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-nama="{{ $kategori->nama_kategori }}"
                                                data-form-id="form-hapus-{{ $kategori->kategori_usaha_id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <i class="fas fa-info-circle me-2"></i> Belum ada kategori usaha.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div> </div> </div> {{--
        PERUBAHAN BAGIAN 2: HTML Modal
        - Ini adalah modal yang akan muncul, diletakkan di luar tabel/card.
        - `modal-dialog-centered` membuatnya di tengah layar.
        - `id="deleteModal"` adalah target dari tombol hapus.
    --}}

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> {{-- Ini membuatnya di tengah --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {{-- ... (kode modal lainnya) ... --}}
<div class="modal-body">
    <div class="text-center mb-3">
        <i class="fas fa-exclamation-triangle fa-4x text-danger"></i>
    </div>

    {{--
      INI BAGIAN YANG DIUBAH:
      Kita buat satu paragraf saja, dan beri <span> atau <strong>
      dengan ID unik untuk menampung nama kategori.
    --}}
    <p class="text-center fs-5">
        Anda yakin ingin menghapus kategori
        <strong id="kategoriNamaModal" class="text-dark"></strong>?
    </p>

    <p class="text-center text-muted small mt-2">
        Tindakan ini tidak dapat dibatalkan dan mungkin mempengaruhi mitra terkait.
    </p>
</div>
{{-- ... (kode modal lainnya) ... --}}
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    {{-- Tombol ini akan men-trigger submit form --}}
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');
        var confirmDeleteButton = document.getElementById('confirmDeleteButton');

        // Ini adalah elemen <strong> yang kita targetkan
        var kategoriNamaElement = document.getElementById('kategoriNamaModal');
        var formToSubmit;

        // Saat modal akan muncul...
        deleteModal.addEventListener('show.bs.modal', function (event) {
            // Ambil data dari tombol yang diklik
            var button = event.relatedTarget;
            var nama = button.getAttribute('data-nama');
            var formId = button.getAttribute('data-form-id');

            // --- INI BAGIAN KUNCINYA ---
            // Masukkan nama dari tombol ke dalam <strong>
            kategoriNamaElement.textContent = "'" + nama + "'";

            // Simpan form yang akan di-submit
            formToSubmit = document.getElementById(formId);
        });

        // Saat tombol "Ya, Hapus" di modal diklik
        confirmDeleteButton.addEventListener('click', function () {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        // (Opsional) Kosongkan nama saat modal ditutup agar rapi
        deleteModal.addEventListener('hidden.bs.modal', function () {
             kategoriNamaElement.textContent = "";
             formToSubmit = null;
        });
    });
</script>
@endpush
