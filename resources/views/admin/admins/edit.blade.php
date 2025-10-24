@extends('admin.layouts.app')

@section('title', 'Edit Admin')

@section('content')
    <h1>Edit Admin: {{ $admin->nama_lengkap }}</h1>

    <form method="POST" action="{{ route('admin.admins.update', $admin) }}">
        @csrf
        @method('PUT')
        @include('admin.admins._form', ['tombolTeks' => 'Update Admin'])
    </form>
@endsection
