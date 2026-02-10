@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
            ['label' => 'List Mapel', 'url' => null]
        ]" />
        </div>

        <x-calendar />
    </div>
    <h1>List Mapel</h1>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('admin.mapel.create') }}">+ Tambah Mapel</a>

    <br><br>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Mapel</th>
                <th>Kurikulum</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjects as $subject)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $subject->name }}</td>
                    <td>{{ strtoupper($subject->curriculum) }}</td>
                    <td>
                        @if($subject->image)
                            <img src="{{ asset('storage/' . $subject->image) }}" width="80">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.mapel.edit', $subject->id) }}">Edit</a>

                        <form action="{{ route('admin.mapel.destroy', $subject->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus mapel ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" align="center">Belum ada mapel</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection