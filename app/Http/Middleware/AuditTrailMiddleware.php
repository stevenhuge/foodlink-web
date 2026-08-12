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
            
            // Periksa apakah user login sebagai admin atau superadmin
            if (Auth::guard('admin')->check()) {
                $admin = Auth::guard('admin')->user();
                
                // Ambil data payload kecuali field sensitif seperti password
                $payload = $request->except(['password', 'password_confirmation', 'current_password', 'new_password']);

                // Filter juga payload besar jika perlu, atau base64 gambar
                foreach ($payload as $key => $value) {
                    if (is_string($value) && str_starts_with($value, 'data:image')) {
                        $payload[$key] = '[BASE64_IMAGE_DATA]';
                    }
                }

                AuditLog::create([
                    'admin_id' => $admin->admin_id,
                    'admin_name' => $admin->nama_lengkap . ' (' . $admin->role . ')',
                    'action' => $this->getActionName($request),
                    'method' => $request->method(),
                    'route_url' => $request->fullUrl(),
                    'payload' => $payload,
                    'ip_address' => $request->ip(),
                ]);
            }
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
