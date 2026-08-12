<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditTrailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya catat metode yang mengubah data
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            
            $userType = 'Guest/System';
            $userId = null;
            $userName = null;

            if (Auth::guard('admin')->check()) {
                $user = Auth::guard('admin')->user();
                $userType = 'Admin';
                $userId = $user->admin_id;
                $userName = $user->nama_lengkap . ' (' . $user->role . ')';
            } elseif (Auth::guard('mitra')->check()) {
                $user = Auth::guard('mitra')->user();
                $userType = 'Mitra';
                $userId = $user->mitra_id;
                $userName = $user->nama_mitra;
            } elseif (Auth::guard('web')->check()) {
                // Asumsi guard 'web' untuk user Android/umum jika session-based, 
                // atau 'sanctum' / 'api' jika token-based. Kita cek keduanya.
                $user = Auth::guard('web')->user();
                $userType = 'User';
                $userId = $user->id; // Sesuaikan dengan primary key user
                $userName = $user->nama_lengkap ?? $user->name ?? 'User';
            } elseif (Auth::guard('sanctum')->check()) {
                $user = Auth::guard('sanctum')->user();
                $userType = 'User (API)';
                $userId = $user->id; // Sesuaikan dengan primary key user
                $userName = $user->nama_lengkap ?? $user->name ?? 'User';
            }

            // Ambil data payload kecuali field sensitif seperti password
            $payload = $request->except(['password', 'password_confirmation', 'current_password', 'new_password']);

            // Filter juga payload besar jika perlu, atau base64 gambar
            foreach ($payload as $key => $value) {
                if (is_string($value) && str_starts_with($value, 'data:image')) {
                    $payload[$key] = '[BASE64_IMAGE_DATA]';
                }
            }

            AuditLog::create([
                'user_type' => $userType,
                'user_id' => $userId,
                'user_name' => $userName,
                'action' => $this->getActionName($request),
                'method' => $request->method(),
                'route_url' => $request->fullUrl(),
                'payload' => $payload,
                'ip_address' => $request->ip(),
            ]);
        }

        return $response;
    }

    /**
     * Helper untuk menentukan nama aksi berdasarkan rute.
     */
    private function getActionName(Request $request)
    {
        $routeName = $request->route() ? $request->route()->getName() : null;
        
        if ($routeName) {
            return 'Aksi: ' . $routeName;
        }

        return 'Mengakses URL';
    }
}
