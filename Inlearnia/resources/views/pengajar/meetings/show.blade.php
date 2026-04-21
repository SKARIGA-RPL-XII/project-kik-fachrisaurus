@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- 1. Tambahkan CSS Quill agar gaya teks dikenali --}}
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        /* 2. Reset padding dan margin bawaan Quill agar tidak merusak layout box kita */
        .custom-ql-viewer .ql-editor {
            padding: 0 !important;
            height: auto !important;
            min-height: auto !important;
        }

        /* Memastikan enter (jarak antar paragraf) tetap terlihat */
        .custom-ql-viewer .ql-editor p {
            margin-bottom: 1rem !important;
        }

        /* Menghilangkan margin pada elemen terakhir agar box tidak terlihat kopong di bawah */
        .custom-ql-viewer .ql-editor>*:last-child {
            margin-bottom: 0 !important;
        }
    </style>

    {{-- Breadcrumb Navigasi --}}
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
                [
                    'label' => $meeting->classRoom->name,
                    'url' => route('teacher.kelas.show', ['kelas' => $meeting->class_id, 'tab' => 'pertemuan']),
                ],
                ['label' => 'Detail Pertemuan', 'url' => null],
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- Layout Utama: Grid 2 Kolom --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI: Konten Utama --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Header Pertemuan --}}
            <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200 relative overflow-hidden">
                {{-- Aksen Garis Atas sesuai tipe --}}
                <div
                    class="absolute top-0 left-0 w-full h-1 {{ $meeting->type == 'tugas' ? 'bg-orange-500' : 'bg-blue-500' }}">
                </div>

                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-12 h-12 rounded-[10px] flex items-center justify-center flex-shrink-0 {{ $meeting->type == 'tugas' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                        @if ($meeting->type == 'tugas')
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                                <path d="M9 14l2 2 4-4"></path>
                            </svg>
                        @else
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <span
                            class="text-[10px] font-bold px-2.5 py-0.5 rounded border uppercase tracking-wider mb-1 inline-block {{ $meeting->type == 'tugas' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                            {{ ucfirst($meeting->type) }}
                        </span>
                        <p class="text-xs text-slate-400 font-medium">{{ $meeting->created_at->format('l, d F Y - H:i') }}
                        </p>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-[#092C4C] mb-2">{{ $meeting->title }}</h1>
                @if ($meeting->topic)
                    <p class="text-sm font-medium text-[#00A79D]">Topik: {{ $meeting->topic }}</p>
                @endif
            </div>

            {{-- Deskripsi / Instruksi --}}
            <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-[#092C4C] mb-4 pb-4 border-b border-slate-100">Instruksi / Deskripsi</h3>

                @if ($meeting->description)
                    <style>
                        /* Paksa gaya teks editor muncul di dalam class prose */
                        .prose-viewer h1,
                        .prose-viewer h2,
                        .prose-viewer h3 {
                            font-weight: 800;
                            color: #092C4C;
                            margin-top: 1.5em;
                            margin-bottom: 0.5em;
                        }

                        .prose-viewer h1 {
                            font-size: 1.8em;
                        }

                        .prose-viewer h2 {
                            font-size: 1.5em;
                        }

                        .prose-viewer h3 {
                            font-size: 1.3em;
                        }

                        .prose-viewer ul {
                            list-style-type: disc;
                            padding-left: 1.5em;
                            margin-bottom: 1em;
                        }

                        .prose-viewer ol {
                            list-style-type: decimal;
                            padding-left: 1.5em;
                            margin-bottom: 1em;
                        }

                        .prose-viewer li {
                            margin-bottom: 0.25em;
                        }

                        /* Lenyapkan gap di elemen paling atas dan bawah */
                        .prose-viewer>*:first-child {
                            margin-top: 0 !important;
                        }

                        .prose-viewer>*:last-child {
                            margin-bottom: 0 !important;
                        }
                    </style>

                    {{-- Kita pakai prose agar gap normal, tapi ditambah prose-viewer untuk style manual --}}
                    <div class="prose max-w-none text-slate-600 text-sm leading-relaxed prose-viewer">
                        {!! $meeting->description !!}
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-slate-400 italic text-sm">Tidak ada instruksi khusus yang ditambahkan.</p>
                    </div>
                @endif
            </div>

            {{-- Lampiran & File --}}
            @php
                // Parsing Links
                $rawLinks = $meeting->links;
                if (is_string($rawLinks)) {
                    $rawLinks = json_decode($rawLinks, true);
                    if (is_string($rawLinks)) {
                        $rawLinks = json_decode($rawLinks, true);
                    }
                }
                // PERBAIKAN: Ganti $rawFiles menjadi $rawLinks
                $links = is_array($rawLinks) ? $rawLinks : [];

                // Parsing Files
                $rawFiles = $meeting->files;
                if (is_string($rawFiles)) {
                    $rawFiles = json_decode($rawFiles, true);
                    if (is_string($rawFiles)) {
                        $rawFiles = json_decode($rawFiles, true);
                    }
                }
                $files = is_array($rawFiles) ? $rawFiles : [];
            @endphp

            @if (count($links) > 0 || count($files) > 0)
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="text-lg font-bold text-[#092C4C] mb-4 pb-4 border-b border-slate-100">Materi Pendukung</h3>

                    {{-- Links --}}
                    @if (count($links) > 0)
                        <div class="mb-6">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Tautan Keluar</p>
                            <div class="flex flex-col gap-2.5">
                                @foreach ($links as $link)
                                    <a href="{{ $link }}" target="_blank"
                                        class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:bg-teal-50 transition-colors group/link w-full">
                                        <div
                                            class="w-8 h-8 rounded bg-white border border-slate-200 text-[#00A79D] flex items-center justify-center flex-shrink-0 group-hover/link:border-teal-200">
                                            {{-- PERBAIKAN: Tambahkan viewBox="0 0 24 24" --}}
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"></path>
                                                <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"></path>
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-slate-600 truncate group-hover/link:text-[#00A79D]">{{ $link }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Files --}}
                    @if (count($files) > 0)
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Dokumen Terlampir</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach ($files as $file)
                                    @if (is_array($file) && isset($file['path']))
                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                            class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group/file">
                                            <div
                                                class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                    </path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]"
                                                    title="{{ $file['original_name'] ?? 'File Lampiran' }}">
                                                    {{ $file['original_name'] ?? 'File Lampiran' }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5">
                                                    {{ isset($file['size']) ? number_format($file['size'] / 1024, 1) . ' KB' : 'Unknown Size' }}
                                                </p>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- KOLOM KANAN: Informasi Meta & Statistik --}}
        <div class="space-y-6">

            {{-- Panel Khusus Tugas (Deadline dll) --}}
            @if ($meeting->type == 'tugas')
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-[#092C4C] mb-4 flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Info Penugasan
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Tenggat Waktu</p>
                            @if ($meeting->deadline)
                                <p
                                    class="text-sm font-bold {{ \Carbon\Carbon::parse($meeting->deadline)->isPast() ? 'text-red-500' : 'text-[#092C4C]' }}">
                                    {{ \Carbon\Carbon::parse($meeting->deadline)->format('l, d F Y - H:i') }}
                                </p>
                            @else
                                <p class="text-sm font-medium text-slate-600">Tidak ada batas waktu</p>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-100">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Poin Maksimal</p>
                            <p class="text-sm font-bold text-[#092C4C]">{{ $meeting->max_score ?? 'Tugas tidak dinilai' }}
                            </p>
                        </div>

                        @if ($meeting->disable_late_submission)
                            <div class="pt-4 border-t border-slate-100">
                                <div
                                    class="bg-red-50 text-red-600 text-xs font-medium px-3 py-2 rounded-[8px] flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <p>Siswa tidak dapat mengumpulkan jawaban jika melewati batas tenggat waktu.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-[#092C4C] mb-4">Ringkasan Pengumpulan</h3>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div class="bg-teal-50 rounded-[10px] p-4 text-center">
                            <span class="block text-2xl font-bold text-[#00A79D]">{{ $stats['graded'] }}</span>
                            <span class="text-xs font-medium text-slate-500 uppercase">Dinilai</span>
                        </div>
                        <div class="bg-blue-50 rounded-[10px] p-4 text-center">
                            <span class="block text-2xl font-bold text-blue-500">{{ $stats['total'] }}</span>
                            <span class="text-xs font-medium text-slate-500 uppercase">Terkumpul</span>
                        </div>
                    </div>

                    {{-- Progress bar dinilai vs terkumpul --}}
                    @if ($stats['total'] > 0)
                        @php $pct = round(($stats['graded'] / $stats['total']) * 100); @endphp
                        <div class="mb-5">
                            <div class="flex justify-between text-[10px] text-slate-400 mb-1">
                                <span>Progress penilaian</span>
                                <span>{{ $pct }}%</span>
                            </div>
                            <div class="bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="h-full bg-[#00A79D] rounded-full transition-all"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('teacher.meetings.submissions.index', $meeting) }}"
                        class="block w-full text-center bg-[#092C4C] hover:bg-[#061b30] text-white py-2.5 rounded-[8px] text-sm font-medium transition-colors">
                        Lihat Jawaban Siswa
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- Script SweetAlert untuk tombol Delete di halaman show --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Pertemuan?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) this.closest('form').submit();
                    });
                });
            });
        });
    </script>
@endsection
