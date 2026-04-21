@extends('layouts.app')

@section('content')
    <x-sidebar />

    <header class="flex justify-between items-center mb-7">
        <x-header-profile />
        <x-calendar />
    </header>

    <x-breadcrumb :items="[]" />

    {{-- Stat Cards --}}
    <div class="mt-8 flex w-full gap-[25px]">
        <x-stat-card label="Kelas Saya" :count="$totalKelasSaya" suffix="Kelas"
            bgColor="bg-[#E6F6F5]" iconBg="bg-[#00A79D]" icon="grid" />
        <x-stat-card label="Total Siswa Diajar" :count="$totalSiswaDiajar" suffix="Siswa"
            bgColor="bg-[#FFF4E8]" iconBg="bg-[#FF9933]" icon="student" />
        <x-stat-card label="Perlu Dinilai" :count="$totalTugasBelumDinilai" suffix="Submission"
            bgColor="bg-[#F1E9FF]" iconBg="bg-[#8E59FF]" icon="teacher" />
    </div>

    {{-- Grid Utama --}}
    <div class="mt-10 grid grid-cols-12 gap-[25px]">

        {{-- Kelas Saya --}}
        <div class="col-span-7 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[16px] font-bold text-[#092C4C]">Kelas yang Saya Ajar</h2>
                <a href="{{ route('teacher.kelas.index') }}"
                    class="text-[12px] font-semibold text-[#00A79D] hover:underline">Lihat Semua</a>
            </div>

            <div class="flex flex-col gap-3">
                @forelse($myClasses as $class)
                    <div class="flex items-center justify-between p-3 rounded-[10px] bg-slate-50 hover:bg-slate-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-[8px] bg-[#00A79D] flex items-center justify-center text-white text-[11px] font-bold">
                                {{ strtoupper(substr($class->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-[13px] font-semibold text-[#092C4C]">{{ $class->name }}</p>
                                <p class="text-[11px] text-slate-400">{{ $class->subject->name ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-[11px] bg-orange-100 text-orange-600 font-medium px-3 py-1 rounded-full">
                                {{ $class->students_count }} siswa
                            </span>
                            <a href="{{ route('teacher.kelas.show', $class->id) }}"
                                class="w-7 h-7 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:bg-[#00A79D] hover:text-white hover:border-[#00A79D] transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-[12px] text-slate-400 text-center py-8">Belum ada kelas.</p>
                @endforelse
            </div>
        </div>

        {{-- Submission Belum Dinilai --}}
        <div class="col-span-5 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[16px] font-bold text-[#092C4C]">Perlu Dinilai</h2>
                @if($totalTugasBelumDinilai > 0)
                    <span class="bg-red-100 text-red-500 text-[11px] font-semibold px-3 py-1 rounded-full">
                        {{ $totalTugasBelumDinilai }} submission
                    </span>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                @forelse($latestSubmissions as $submission)
                    <div class="flex items-center gap-3 group">
                        <div class="w-9 h-9 rounded-full overflow-hidden border border-slate-100 shrink-0">
                            <img src="{{ $submission->student->profile_photo
                                ? asset('storage/' . $submission->student->profile_photo)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($submission->student->name) . '&background=0F4C5C&color=fff' }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-[13px] font-semibold text-[#092C4C] truncate">
                                {{ $submission->student->name }}
                            </p>
                            <p class="text-[11px] text-slate-400 truncate">
                                {{ $submission->assignment->title ?? '-' }} ·
                                {{ $submission->assignment->classRoom->name ?? '-' }}
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 shrink-0">
                            {{ $submission->created_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <p class="text-[12px] text-slate-400 text-center py-10">
                        Semua submission sudah dinilai 🎉
                    </p>
                @endforelse
            </div>

            @if($latestSubmissions->isNotEmpty())
                <div class="mt-6 pt-4 border-t border-slate-50">
                    <a href="{{ route('pengajar.submissions.index') }}"
                        class="flex items-center justify-center py-2.5 rounded-xl bg-slate-50 text-[#00A79D] text-[12px] font-bold hover:bg-[#00A79D] hover:text-white transition-all">
                        Lihat Semua Submission
                    </a>
                </div>
            @endif
        </div>

    </div>
@endsection