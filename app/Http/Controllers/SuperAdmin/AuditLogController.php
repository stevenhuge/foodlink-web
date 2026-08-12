<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    /**
     * Tampilkan riwayat audit.
     */
    public function index(Request $request)
    {
        $query = AuditLog::query();

        // Filter berdasarkan method jika ada
        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        // Filter berdasarkan tipe user
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }

        // Filter pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('route_url', 'like', "%{$search}%");
            });
        }

        // Urutkan dari yang terbaru
        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.audit_logs.index', compact('logs'));
    }

    /**
     * Tampilkan detail (opsional jika butuh popup modal payload besar)
     */
    public function show($id)
    {
        $log = AuditLog::findOrFail($id);
        return response()->json($log);
    }
}
