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
        <x-stat-card label="Kelas Diikuti" :count="$totalKelas" suffix="Kelas"
            bgColor="bg-[#E6F6F5]" iconBg="bg-[#00A79D]" icon="grid" />
        <x-stat-card label="Tugas Dikumpulkan" :count="$totalTugasDikumpulkan" suffix="Tugas"
            bgColor="bg-[#FFF4E8]" iconBg="bg-[#FF9933]" icon="student" />
        <x-stat-card label="Belum Dikerjakan" :count="$totalTugasBelumDikumpulkan" suffix="Tugas"
            bgColor="bg-[#F1E9FF]" iconBg="bg-[#8E59FF]" icon="teacher" />
    </div>

    {{-- Grid Utama --}}
    <div class="mt-10 grid grid-cols-12 gap-[25px]">

        {{-- Kelas Saya --}}
        <div class="col-span-7 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[16px] font-bold text-[#092C4C]">Kelas Saya</h2>
                <a href="{{ route('student.kelas.index') }}"
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
                                <p class="text-[11px] text-slate-400">
                                    {{ $class->subject->name ?? '-' }} ·
                                    {{ $class->teacher->name ?? 'Belum ada pengajar' }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('student.kelas.show', $class->id) }}"
                            class="w-7 h-7 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-400 hover:bg-[#00A79D] hover:text-white hover:border-[#00A79D] transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @empty
                    <p class="text-[12px] text-slate-400 text-center py-8">Belum terdaftar di kelas manapun.</p>
                @endforelse
            </div>
        </div>

        {{-- Tugas Belum Dikerjakan --}}
        <div class="col-span-5 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[16px] font-bold text-[#092C4C]">Tugas Belum Dikerjakan</h2>
                @if($totalTugasBelumDikumpulkan > 0)
                    <span class="bg-red-100 text-red-500 text-[11px] font-semibold px-3 py-1 rounded-full">
                        {{ $totalTugasBelumDikumpulkan }} tugas
                    </span>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                @forelse($latestMeetings as $meeting)
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-[8px] bg-purple-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-[13px] font-semibold text-[#092C4C] truncate">
                                {{ $meeting->title }}
                            </p>
                            <p class="text-[11px] text-slate-400 truncate">
                                {{ $meeting->classRoom->name ?? '-' }} ·
                                {{ $meeting->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <a href="{{ route('student.meetings.show', $meeting->id) }}"
                            class="text-[11px] bg-purple-50 text-purple-500 hover:bg-purple-500 hover:text-white font-medium px-3 py-1 rounded-full transition shrink-0">
                            Kerjakan
                        </a>
                    </div>
                @empty
                    <p class="text-[12px] text-slate-400 text-center py-10">
                        Semua tugas sudah dikerjakan 🎉
                    </p>
                @endforelse
            </div>
        </div>

    </div>
@endsection