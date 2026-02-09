@extends('layouts.app')

@section('content')
    <x-sidebar />
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
            ['label' => 'List Mapel', 'url' => route('admin.mapel.index')],
            ['label' => 'Buat Mapel', 'url' => null]
        ]" />
        </div>

        <x-calendar />
    </div>
<h1>Tambah Mapel</h1>

<form action="{{ route('admin.mapel.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <p>
        Nama Mapel: <br>
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <br><small style="color:red">{{ $message }}</small>
        @enderror
    </p>

    <p>
        Kurikulum: <br>
        <select name="curriculum">
            <option value="">-- Pilih Kurikulum --</option>
            <option value="k13" {{ old('curriculum') == 'k13' ? 'selected' : '' }}>Kurikulum 2013</option>
            <option value="merdeka" {{ old('curriculum') == 'merdeka' ? 'selected' : '' }}>Kurikulum Merdeka</option>
        </select>
        @error('curriculum')
            <br><small style="color:red">{{ $message }}</small>
        @enderror
    </p>

    <p>
        Gambar Mapel (opsional): <br>
        <input type="file" name="image">
        @error('image')
            <br><small style="color:red">{{ $message }}</small>
        @enderror
    </p>

    <button type="submit">Simpan</button>
</form>

<br>
<a href="{{ route('admin.mapel.index') }}">Kembali</a>
@endsection
