@extends('admin.layouts.app')

@section('title', 'Log Aktivitas (Audit Trail)')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h3 mb-1 text-gray-800 fw-bold">Log Aktivitas Sistem</h2>
        <p class="text-muted mb-0">Pantau semua aktivitas perubahan data oleh Admin dan SuperAdmin.</p>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-history me-2"></i>Riwayat Aktivitas</h6>
        
        <!-- Filter dan Pencarian -->
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="d-flex gap-2">
            <select name="user_type" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua User</option>
                <option value="Admin" {{ request('user_type') == 'Admin' ? 'selected' : '' }}>Admin / SuperAdmin</option>
                <option value="Mitra" {{ request('user_type') == 'Mitra' ? 'selected' : '' }}>Mitra</option>
                <option value="User" {{ request('user_type') == 'User' ? 'selected' : '' }}>User (Pembeli)</option>
                <option value="Guest/System" {{ request('user_type') == 'Guest/System' ? 'selected' : '' }}>Guest / Sistem</option>
            </select>
            <select name="method" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Method</option>
                <option value="POST" {{ request('method') == 'POST' ? 'selected' : '' }}>POST (Tambah)</option>
                <option value="PUT" {{ request('method') == 'PUT' ? 'selected' : '' }}>PUT/PATCH (Edit)</option>
                <option value="DELETE" {{ request('method') == 'DELETE' ? 'selected' : '' }}>DELETE (Hapus)</option>
            </select>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau URL..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Waktu</th>
                        <th>User (Admin)</th>
                        <th>Aksi & URL</th>
                        <th>Method</th>
                        <th class="pe-4 text-center">Data Berubah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-medium text-dark">{{ $log->created_at->format('d M Y') }}</span><br>
                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $log->user_name ?? 'Guest' }}</div>
                            <span class="badge bg-secondary mb-1" style="font-size: 0.7rem;">{{ $log->user_type }}</span><br>
                            <small class="text-muted">IP: {{ $log->ip_address }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary mb-1">{{ $log->action }}</span><br>
                            <small class="text-primary text-break" style="font-family: monospace;">{{ $log->route_url }}</small>
                        </td>
                        <td>
                            @php
                                $badgeColor = match($log->method) {
                                    'POST' => 'success',
                                    'PUT', 'PATCH' => 'warning',
                                    'DELETE' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }}">{{ $log->method }}</span>
                        </td>
                        <td class="pe-4 text-center">
                            @if($log->payload)
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#payloadModal{{ $log->id }}">
                                    <i class="fas fa-code me-1"></i> Lihat Data
                                </button>
                                
                                <!-- Modal -->
                                <div class="modal fade text-start" id="payloadModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Payload</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body bg-light">
                                                <pre class="mb-0 text-dark" style="white-space: pre-wrap; word-wrap: break-word;"><code>{{ json_encode($log->payload, JSON_PRETTY_PRINT) }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat aktivitas yang tercatat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($logs->hasPages())
    <div class="card-footer bg-white border-top-0 p-4">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
