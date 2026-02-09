@extends('layouts.app')

@section('content')
    <x-sidebar />
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
            ['label' => 'List Kelas', 'url' => route('admin.kelas.index')],
            ['label' => 'Edit Kelas', 'url' => null]
        ]" />
        </div>

        <x-calendar />
    </div>

        <h1>Edit Kelas</h1>

        @if($errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('admin.kelas.update', $kela->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div>
                <label>Nama Kelas</label>
                <input type="text" name="name" value="{{ old('name', $kela->name) }}">
            </div>

            <div>
                <label>Deskripsi</label>
                <textarea name="description">{{ old('description', $kela->description) }}</textarea>
            </div>

            <div>
                <label>Logo Kelas</label>
                <input type="file" name="logo">
                @if($kela->logo)
                    <p>Logo: {{ $kela->logo }}</p>
                @endif
            </div>

            <div>
                <label>Pilih Mapel</label>
                <select name="subject_id">
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected($subject->id == $kela->subject_id)>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Pilih Pengajar</label>
                <select name="teacher_id">
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" @selected($teacher->id == $kela->teacher_id)>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label>Pilih Siswa</label>
                @foreach($students as $student)
                    <div>
                        <input type="checkbox" name="students[]" value="{{ $student->id }}" @checked(in_array($student->id, $selectedStudents))>
                        {{ $student->name }}
                    </div>
                @endforeach
            </div>

            <button type="submit">Update</button>
        </form>
@endsection