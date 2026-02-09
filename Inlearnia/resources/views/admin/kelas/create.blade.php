@extends('layouts.app')

@section('content')
    <x-sidebar />
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
            ['label' => 'List Kelas', 'url' => route('admin.kelas.index')],
            ['label' => 'Buat Kelas', 'url' => null]
        ]" />
        </div>

        <x-calendar />
    </div>
    <h1>Tambah Kelas</h1>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.kelas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label>Nama Kelas</label>
            <input type="text" name="name" value="{{ old('name') }}">
        </div>

        <div>
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>

        <div>
            <label>Logo Kelas</label>
            <input type="file" name="logo">
        </div>

        <div>
            <label>Pilih Mapel (1)</label>
            <select name="subject_id">
                <option value="">-- pilih --</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Pilih Pengajar</label>
            <select name="teacher_id">
                <option value="">-- pilih --</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Pilih Siswa</label>
            @foreach($students as $student)
                <div>
                    <input type="checkbox" name="students[]" value="{{ $student->id }}">
                    {{ $student->name }}
                </div>
            @endforeach
        </div>

        <button type="submit">Simpan</button>
    </form>
@endsection