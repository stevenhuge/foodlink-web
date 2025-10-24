@extends('admin.layouts.app')

@section('title', 'Tambah Admin Baru')

@section('content')
    <h1>Tambah Admin Baru</h1>

    <form method="POST" action="{{ route('admin.admins.store') }}">
        @csrf
        @include('admin.admins._form', ['tombolTeks' => 'Buat Admin'])
    </form>
@endsection
