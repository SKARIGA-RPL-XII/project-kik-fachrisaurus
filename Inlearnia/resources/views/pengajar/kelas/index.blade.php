@extends('layouts.app')

@section('content')
    <x-sidebar />
    <div class="flex justify-between items-center mb-10">
        
        {{-- Sisi Kiri: Breadcrumb --}}
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'List Kelas', 'url' => null]
            ]" />
        </div>

        {{-- Sisi Kanan: Tanggal --}}
        <x-calendar />

    </div>
<h1>List Kelas</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<a href="{{ route('admin.kelas.create') }}">+ Tambah Kelas</a>

<hr>

<div style="display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px;">
    @foreach($classes as $class)
        <div style="border: 1px solid #ccc; padding: 12px; width: 250px;">
            
            {{-- GAMBAR KELAS --}}
            <div style="margin-bottom: 10px;">
                @if($class->logo)
                    <img src="{{ asset('storage/' . $class->logo) }}"
                         alt="Logo Kelas"
                         style="width: 100%; height: 140px; object-fit: cover;">
                @else
                    <div style="width: 100%; height: 140px; background: #eee; display:flex; align-items:center; justify-content:center;">
                        <small>Tidak ada gambar</small>
                    </div>
                @endif
            </div>

            {{-- NAMA KELAS --}}
            <h3 style="margin: 0 0 5px 0;">{{ $class->name }}</h3>

            {{-- MAPEL --}}
            <p style="margin: 0 0 5px 0;">
                <b>Mapel:</b> {{ $class->subject?->name ?? '-' }}
            </p>

            {{-- PENGAJAR --}}
            <p style="margin: 0 0 5px 0;">
                <b>Pengajar:</b> {{ $class->teacher?->name ?? '-' }}
            </p>

            {{-- JUMLAH --}}
            <p style="margin: 0 0 5px 0;">
                <b>Jumlah siswa:</b> {{ $class->students_count ?? 0 }}
            </p>

            <p style="margin: 0 0 5px 0;">
                <b>Jumlah pertemuan:</b> {{ $class->meetings_count ?? 0 }}
            </p>

            <p style="margin: 0 0 10px 0;">
                <b>Jumlah pengumuman:</b> {{ $class->announcements_count ?? 0 }}
            </p>

            {{-- AKSI --}}
            <div style="display:flex; gap: 10px;">
                <a href="{{ route('admin.kelas.edit', $class->id) }}">Edit</a>

                <form action="{{ route('admin.kelas.destroy', $class->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin hapus kelas?')">Hapus</button>
                </form>
            </div>

        </div>
    @endforeach
</div>
@endsection
