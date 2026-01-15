<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mitra; // Pastikan Model di-import

class MitraController extends Controller
{
    public function index()
    {
        $mitra = Mitra::all();
        return response()->json($mitra, 200);
    }
}
