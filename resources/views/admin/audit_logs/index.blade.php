@extends('admin.layouts.app')

@section('title', 'Log Aktivitas (Audit Trail)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-1 text-gray-800 fw-bold">Log Aktivitas Sistem</h2>
        <p class="text-muted mb-0">Pantau semua aktivitas perubahan data oleh Admin dan SuperAdmin.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="row align-items-center">
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="m-0 font-weight-bold text-primary d-flex align-items-center">
                    <div class="bg-light text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-history"></i>
                    </div>
                    Riwayat Aktivitas
                </h6>
            </div>
            <div class="col-md-8">
                <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="d-flex flex-wrap flex-md-nowrap justify-content-md-end gap-2">
                    <select name="user_type" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                        <option value="">Semua User</option>
                        <option value="Admin" {{ request('user_type') == 'Admin' ? 'selected' : '' }}>Admin / SuperAdmin</option>
                        <option value="Mitra" {{ request('user_type') == 'Mitra' ? 'selected' : '' }}>Mitra</option>
                        <option value="User" {{ request('user_type') == 'User' ? 'selected' : '' }}>User (Pembeli)</option>
                        <option value="Guest/System" {{ request('user_type') == 'Guest/System' ? 'selected' : '' }}>Guest / Sistem</option>
                    </select>
                    <select name="method" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                        <option value="">Semua Method</option>
                        <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST (Tambah)</option>
                        <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT/PATCH (Edit)</option>
                        <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE (Hapus)</option>
                    </select>
                    <div class="input-group input-group-sm" style="max-width: 250px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau URL..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 custom-table">
                <thead class="table-light text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 py-3 border-0">Waktu</th>
                        <th class="py-3 border-0">User (Admin)</th>
                        <th class="py-3 border-0">Aksi & URL</th>
                        <th class="py-3 border-0">Method</th>
                        <th class="pe-4 py-3 text-center border-0">Data Berubah</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-dark">{{ $log->created_at->format('d M Y') }}</span>
                                <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $log->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-secondary text-white fw-bold d-flex align-items-center justify-content-center rounded-circle me-3" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                    {{ strtoupper(substr($log->user_name ?? 'G', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark mb-1">{{ $log->user_name ?? 'Guest' }}</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem;">{{ $log->user_type }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-network-wired me-1"></i>{{ $log->ip_address }}</small>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="badge bg-light text-info border border-info px-2 py-1">{{ $log->action }}</span>
                            </div>
                            <code class="text-primary bg-light px-2 py-1 rounded d-inline-block text-truncate" style="max-width: 250px;" title="{{ $log->route_url }}">{{ $log->route_url }}</code>
                        </td>
                        <td>
                            @php
                                $methodTheme = match($log->method) {
                                    'POST' => ['color' => 'success', 'icon' => 'fa-plus-circle'],
                                    'PUT', 'PATCH' => ['color' => 'warning', 'icon' => 'fa-edit'],
                                    'DELETE' => ['color' => 'danger', 'icon' => 'fa-trash-alt'],
                                    'GET' => ['color' => 'primary', 'icon' => 'fa-eye'],
                                    default => ['color' => 'secondary', 'icon' => 'fa-cog']
                                };
                            @endphp
                            <span class="badge bg-light text-{{ $methodTheme['color'] }} border border-{{ $methodTheme['color'] }} px-2 py-1 rounded-pill">
                                <i class="fas {{ $methodTheme['icon'] }} me-1"></i>{{ $log->method }}
                            </span>
                        </td>
                        <td class="pe-4 text-center">
                            @if($log->payload)
                                <button type="button" class="btn btn-sm btn-light text-primary border shadow-sm rounded-pill px-3 fw-medium btn-hover-primary" data-bs-toggle="modal" data-bs-target="#payloadModal{{ $log->id }}">
                                    <i class="fas fa-code me-1"></i> Lihat Data
                                </button>
                            @else
                                <span class="text-muted small fst-italic">Tidak ada data</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                <h5>Belum ada riwayat aktivitas</h5>
                                <p class="small">Aktivitas sistem akan muncul di sini saat ada perubahan data.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($logs->hasPages())
    <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-center custom-pagination">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Container -->
@push('modals')
@foreach($logs as $log)
    @if($log->payload)
    <div class="modal fade" id="payloadModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fas fa-file-code text-primary me-2"></i>Detail Payload Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="bg-dark text-light p-4 rounded-bottom" style="font-family: 'Fira Code', 'Courier New', Courier, monospace; overflow-x: auto;">
                        <pre class="mb-0 text-success" style="white-space: pre-wrap; word-wrap: break-word;"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endforeach
@endpush

@push('styles')
<style>
    .avatar-circle {
        text-transform: uppercase;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .custom-table tbody tr {
        transition: all 0.2s ease;
    }
    .custom-table tbody tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .btn-hover-primary {
        transition: all 0.2s ease;
    }
    .btn-hover-primary:hover {
        background-color: #0d6efd !important;
        color: white !important;
        border-color: #0d6efd !important;
    }
    /* Pagination Styling Fixes */
    .custom-pagination nav {
        margin-bottom: 0;
    }
    .custom-pagination .pagination {
        margin-bottom: 0;
        gap: 0.25rem;
    }
    .custom-pagination .page-link {
        border-radius: 6px;
        color: #495057;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .custom-pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        box-shadow: 0 2px 4px rgba(13, 110, 253, 0.25);
    }
    .custom-pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #0a58ca;
    }
    .custom-pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    /* SVG icons in tailwind pagination fallback styling just in case */
    .custom-pagination svg {
        width: 1.25rem;
        height: 1.25rem;
    }
</style>
@endpush

@endsection
