@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Profil Saya', 'url' => route('admin.profile.index')],
                ['label' => 'Edit Sekolah', 'url' => null],
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- TITLE --}}
    <div class="mb-8">
        <x-ui.title text="Edit Data Sekolah" />
        <p class="text-slate-400 text-sm mt-1">Perbarui informasi sekolah secara lengkap</p>
    </div>

    <form action="{{ route('admin.profile.school.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ================= LEFT: LOGO ================= --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-7 h-fit">

                <h3 class="text-sm font-semibold text-slate-700 mb-6">Logo Sekolah</h3>

                <div class="flex flex-col items-center text-center">

                    {{-- Logo Preview --}}
                    <div
                        class="w-32 h-32 rounded-full overflow-hidden border-[5px] border-slate-100 shadow-sm mb-5 ring-2 ring-slate-50">

                        @if ($school?->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full bg-gradient-to-br from-[#0F4C5C] to-[#0a3547] flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($school?->name ?? 'S', 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Upload --}}
                    <label
                        class="cursor-pointer bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-600 text-xs px-4 py-2.5 rounded-xl transition font-medium shadow-sm">
                        Upload Logo
                        <input type="file" name="logo" class="hidden"
                            onchange="this.parentElement.innerText = this.files[0]?.name || 'Upload Logo'">
                    </label>

                    <p class="text-[11px] text-slate-400 mt-3">
                        Format JPG/PNG • Maks 2MB
                    </p>
                </div>
            </div>

            {{-- ================= RIGHT: FORM ================= --}}
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

                @php
                    $inputClass = "w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#0F4C5C] focus:border-[#0F4C5C] outline-none transition";
                    $labelClass = "text-[11px] text-slate-400 uppercase tracking-wider mb-1 block";
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama --}}
                    <div>
                        <label class="{{ $labelClass }}">Nama Sekolah</label>
                        <input type="text" name="name" value="{{ $school?->name }}" class="{{ $inputClass }}">
                    </div>

                    {{-- NPSN --}}
                    <div>
                        <label class="{{ $labelClass }}">NPSN</label>
                        <input type="text" name="npsn" value="{{ $school?->npsn }}" class="{{ $inputClass }}">
                    </div>

                    {{-- Kepala Sekolah --}}
                    <div>
                        <label class="{{ $labelClass }}">Kepala Sekolah</label>
                        <input type="text" name="principal_name" value="{{ $school?->principal_name }}"
                            class="{{ $inputClass }}">
                    </div>

                    {{-- Telp --}}
                    <div>
                        <label class="{{ $labelClass }}">No. Telp</label>
                        <input type="text" name="phone" value="{{ $school?->phone }}" class="{{ $inputClass }}">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="{{ $labelClass }}">Email</label>
                        <input type="email" name="email" value="{{ $school?->email }}" class="{{ $inputClass }}">
                    </div>

                    {{-- Akreditasi --}}
                    <div>
                        <label class="{{ $labelClass }}">Akreditasi</label>
                        <select name="accreditation" class="{{ $inputClass }} bg-white">
                            <option value="">-- Pilih --</option>
                            @foreach (['A', 'B', 'C'] as $acc)
                                <option value="{{ $acc }}"
                                    {{ $school?->accreditation == $acc ? 'selected' : '' }}>
                                    {{ $acc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jenjang --}}
                    <div>
                        <label class="{{ $labelClass }}">Jenjang</label>
                        <select name="level" class="{{ $inputClass }} bg-white">
                            @foreach (['TK', 'SD', 'SMP', 'SMA', 'SMK'] as $lvl)
                                <option value="{{ $lvl }}"
                                    {{ $school?->level == $lvl ? 'selected' : '' }}>
                                    {{ $lvl }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="{{ $labelClass }}">Status</label>
                        <select name="status" class="{{ $inputClass }} bg-white">
                            <option value="Negeri" {{ $school?->status == 'Negeri' ? 'selected' : '' }}>Negeri</option>
                            <option value="Swasta" {{ $school?->status == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                        </select>
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label class="{{ $labelClass }}">Alamat</label>
                        <textarea name="address" rows="3"
                            class="{{ $inputClass }} resize-none">{{ $school?->address }}</textarea>
                    </div>

                </div>

                {{-- ACTION --}}
                <div class="flex justify-end mt-10 gap-3 border-t pt-6">

                    <a href="{{ route('admin.profile.index') }}"
                        class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-500 hover:bg-slate-50 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-[#00A99D] to-[#008f84] hover:opacity-90 text-white text-sm rounded-xl transition shadow-md shadow-[#0F4C5C]/20">
                        Simpan Perubahan
                    </button>

                </div>

            </div>
        </div>
    </form>
@endsection