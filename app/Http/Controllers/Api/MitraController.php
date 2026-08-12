<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mitra; // Pastikan Model di-import

class MitraController extends Controller
{
    public function index()
    {
        $mitras = Mitra::all();
        $formatted = $mitras->map(function($mitra) {
            $logo_url = $mitra->logo_mitra;
            if ($logo_url && str_starts_with($logo_url, 'data:image')) {
                // Return generic avatar or a public route if we have one. 
                // Using the web app's asset logic or a public endpoint.
                $logo_url = url('/mitra/'. $mitra->mitra_id . '/logo');
            } elseif ($logo_url) {
                $logo_url = \Illuminate\Support\Facades\Storage::url($logo_url);
                if (!str_starts_with($logo_url, 'http')) {
                    $logo_url = url($logo_url);
                }
            }

            return [
                'mitra_id' => $mitra->mitra_id,
                'nama_mitra' => $mitra->nama_mitra,
                'alamat' => $mitra->alamat,
                'telepon' => $mitra->telepon,
                'status_verifikasi' => $mitra->status_verifikasi,
                'logo_mitra' => $logo_url,
                'created_at' => $mitra->created_at,
            ];
        });

        return response()->json($formatted, 200);
    }
}
