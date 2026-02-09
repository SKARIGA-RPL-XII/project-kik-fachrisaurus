@extends('layouts.app')

@section('content')
    <x-sidebar />
{{-- Container untuk mensejajarkan Breadcrumb dan Tanggal --}}
    <div class="flex justify-between items-center mb-10">
        
        {{-- Sisi Kiri: Breadcrumb --}}
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'List Pengguna', 'url' => null]
            ]" />
        </div>

        {{-- Sisi Kanan: Tanggal --}}
        <x-calendar />

    </div>

<h1>List Pengguna</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

<p>
    <a href="{{ route('admin.users.create') }}">+ Tambah User</a>
</p>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>Foto</th>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>No Telp</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td>
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" width="60">
                    @else
                        -
                    @endif
                </td>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>{{ $user->phone ?? '-' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}">Edit</a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus user ini?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Belum ada user.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection