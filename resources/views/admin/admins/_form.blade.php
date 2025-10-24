@if ($errors->any())
    <div style="color: red;">
        <strong>Oops! Ada kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="nama_lengkap">Nama Lengkap</label><br>
    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $admin->nama_lengkap ?? '') }}" required>
</div>
<div style="margin-top: 10px;">
    <label for="username">Username</label><br>
    <input type="text" name="username" id="username" value="{{ old('username', $admin->username ?? '') }}" required>
</div>
<div style="margin-top: 10px;">
    <label for="role">Role</label><br>
    <select name="role" id="role" required>
        <option value="Admin" @selected(old('role', $admin->role ?? '') == 'Admin')>Admin</option>
        <option value="SuperAdmin" @selected(old('role', $admin->role ?? '') == 'SuperAdmin')>SuperAdmin</option>
    </select>
</div>
<hr>
<div style="margin-top: 10px;">
    <label for="password">Password</label><br>
    <input type="password" name="password" id="password" @if(empty($admin)) required @endif>
    @if(!empty($admin)) <small><i>Kosongkan jika tidak ingin mengubah password.</i></small> @endif
</div>
<div style="margin-top: 10px;">
    <label for="password_confirmation">Konfirmasi Password</label><br>
    <input type="password" name="password_confirmation" id="password_confirmation">
</div>

<div style="margin-top: 20px;">
    <button type="submit">{{ $tombolTeks ?? 'Simpan' }}</button>
</div>
