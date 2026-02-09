@extends('layouts.app')

@section('content')
    <x-sidebar />
<h1>Edit User</h1>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <br>

    <div>
        <label>Gambar Profil (opsional)</label><br>
        <input type="file" name="profile_photo" accept="image/*">
    </div>

    @if($user->profile_photo)
        <p>Foto sekarang:</p>
        <img src="{{ asset('storage/' . $user->profile_photo) }}" width="120">
    @endif

    <br>
    
    <div>
        <label>Nama Lengkap</label><br>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <br>

    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>

    <br>

    <div>
        <label>No Telp</label><br>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}">
    </div>

    <br>

    <div>
        <label>Role</label><br>
        <select name="role" required>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Pengajar</option>
            <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Siswa</option>
        </select>
    </div>

    <br>

    <div>
        <label>Password Baru (opsional)</label><br>
        <input type="password" name="password">
    </div>

    <br>

    <div>
        <label>Konfirmasi Password Baru</label><br>
        <input type="password" name="password_confirmation">
    </div>

    <br>

    <button type="submit">Update</button>
</form>

<br>

<a href="{{ route('admin.users.index') }}">← Kembali</a>
@endsection
