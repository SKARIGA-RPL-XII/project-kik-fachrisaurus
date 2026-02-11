@extends('layouts.app')

@section('content')
    <h1>Detail Kelas</h1>

    <p><strong>Nama:</strong> {{ $kelas->name }}</p>
    <p><strong>Mapel:</strong> {{ $kelas->subject?->name }}</p>
    <p><strong>Pengajar:</strong> {{ $kelas->teacher?->name }}</p>
    <p><strong>Jumlah Siswa:</strong> {{ $kelas->students->count() }}</p>
@endsection
