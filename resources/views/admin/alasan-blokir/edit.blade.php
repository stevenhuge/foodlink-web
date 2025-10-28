@extends('admin.layouts.app')
@section('title', 'Edit Alasan Blokir')
@section('content')
    <h1>Edit Alasan Blokir</h1>

    @if ($errors->any())
        <div class="error-msg">
            <strong>Oops! Ada kesalahan:</strong>
            <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
        </div>
    @endif

    <form action="{{ route('admin.alasan-blokir.update', $alasanBlokirOption->alasan_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="alasan_text">Teks Alasan</label><br>
            <input type="text" id="alasan_text" name="alasan_text" value="{{ old('alasan_text', $alasanBlokirOption->alasan_text) }}" required style="width: 400px;">
        </div>
        <div style="margin-top: 20px;">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.alasan-blokir.index') }}" style="margin-left: 10px;">Batal</a>
        </div>
    </form>
@endsection
