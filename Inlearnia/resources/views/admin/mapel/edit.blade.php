@extends('layouts.app')

@section('content')
    <x-sidebar />
<h1>Edit Mapel</h1>

<form action="{{ route('admin.mapel.update', $subject->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <p>
        Nama Mapel: <br>
        <input type="text" name="name" value="{{ old('name', $subject->name) }}">
        @error('name')
            <br><small style="color:red">{{ $message }}</small>
        @enderror
    </p>

    <p>
        Kurikulum: <br>
        <select name="curriculum">
            <option value="k13" {{ old('curriculum', $subject->curriculum) == 'k13' ? 'selected' : '' }}>Kurikulum 2013</option>
            <option value="merdeka" {{ old('curriculum', $subject->curriculum) == 'merdeka' ? 'selected' : '' }}>Kurikulum Merdeka</option>
        </select>
        @error('curriculum')
            <br><small style="color:red">{{ $message }}</small>
        @enderror
    </p>

    <p>
        Gambar baru (opsional): <br>
        <input type="file" name="image">
        @error('image')
            <br><small style="color:red">{{ $message }}</small>
        @enderror
    </p>

    <p>
        Gambar sekarang: <br>
        @if($subject->image)
            <img src="{{ asset('storage/'.$subject->image) }}" width="100">
        @else
            -
        @endif
    </p>

    <button type="submit">Update</button>
</form>

<br>
<a href="{{ route('admin.mapel.index') }}">Kembali</a>
@endsection
