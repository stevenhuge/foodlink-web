<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    // Terapkan middleware di constructor agar hanya SuperAdmin
    public function __construct()
    {
        // Hanya SuperAdmin yang bisa mengakses SEMUA method di controller ini
        $this->middleware(function ($request, $next) {
            if (Gate::denies('is-superadmin')) {
                abort(403, 'Akses Ditolak. Hanya SuperAdmin.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $admins = Admin::all();
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['Admin', 'SuperAdmin'])],
        ]);

        Admin::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username' => $request->username,
            'password_hash' => Hash::make($request->password), // Hash password
            'role' => $request->role,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('admins')->ignore($admin->admin_id, 'admin_id')],
            'role' => ['required', Rule::in(['Admin', 'SuperAdmin'])],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
        ]);

        $admin->nama_lengkap = $request->nama_lengkap;
        $admin->username = $request->username;
        $admin->role = $request->role;

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $admin->password_hash = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        // Tambahkan proteksi agar tidak bisa hapus diri sendiri (opsional tapi aman)
        if ($admin->admin_id == auth()->guard('admin')->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
}
