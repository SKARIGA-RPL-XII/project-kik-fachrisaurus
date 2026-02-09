@extends('layouts.app')

@section('content')
    <x-sidebar />
<h1>Tambah User</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">

    @csrf

    <div>
        <label>Gambar Profil</label><br>
        <input type="file" name="profile_photo" accept="image/*">
    </div>

    <br>

    <div>
        <label>Nama Lengkap</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required>
    </div>

    <br>

    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <br>

    <div>
        <label>No Telp</label><br>
        <input type="text" name="phone" value="{{ old('phone') }}">
    </div>

    <br>

    <div>
        <label>Role</label><br>
        <select name="role" required>
            <option value="">-- pilih role --</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Pengajar</option>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Siswa</option>
        </select>
    </div>

    <br>

    <div>
        <label>Password</label><br>
        <input type="password" name="password" required>
    </div>

    <br>

    <div>
        <label>Konfirmasi Password</label><br>
        <input type="password" name="password_confirmation" required>
    </div>

    <br>

    <button type="submit">Simpan</button>
</form>

<br>

<a href="{{ route('admin.users.index') }}">← Kembali</a>
@endsection
