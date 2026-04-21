@extends('layouts.app')

@section('content')
    <x-sidebar />

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .custom-ql-viewer .ql-editor {
            padding: 0 !important;
            height: auto !important;
            min-height: auto !important;
        }

        .custom-ql-viewer .ql-editor p {
            margin-bottom: 1rem !important;
        }

        .custom-ql-viewer .ql-editor>*:last-child {
            margin-bottom: 0 !important;
        }

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

        .prose-viewer>*:first-child {
            margin-top: 0 !important;
        }

        .prose-viewer>*:last-child {
            margin-bottom: 0 !important;
        }
    </style>

    {{-- Breadcrumb --}}
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Kelas Saya', 'url' => route('student.kelas.index')],
                [
                    'label' => $meeting->classRoom->name,
                    'url' => route('student.kelas.show', ['kelas' => $meeting->class_id, 'tab' => 'pertemuan']),
                ],
                ['label' => 'Detail ' . ucfirst($meeting->type), 'url' => null],
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- Layout Utama --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Header Pertemuan --}}
            <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200 relative overflow-hidden">
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
                    <div class="prose max-w-none text-slate-600 text-sm leading-relaxed prose-viewer">
                        {!! $meeting->description !!}
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-slate-400 italic text-sm">Tidak ada instruksi khusus yang ditambahkan.</p>
                    </div>
                @endif


            </div>


            {{-- Lampiran & File dari Guru --}}
            @php
                $rawLinks = $meeting->links;
                if (is_string($rawLinks)) {
                    $rawLinks = json_decode($rawLinks, true);
                    if (is_string($rawLinks)) {
                        $rawLinks = json_decode($rawLinks, true);
                    }
                }
                $links = is_array($rawLinks) ? $rawLinks : [];

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

                    @if (count($links) > 0)
                        <div class="mb-6">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Tautan Keluar</p>
                            <div class="flex flex-col gap-2.5">
                                @foreach ($links as $link)
                                    <a href="{{ $link }}" target="_blank"
                                        class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:bg-teal-50 transition-colors group/link w-full">
                                        <div
                                            class="w-8 h-8 rounded bg-white border border-slate-200 text-[#00A79D] flex items-center justify-center flex-shrink-0 group-hover/link:border-teal-200">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
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
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                    </path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p
                                                    class="text-sm font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]">
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

            {{-- Deskripsi / Instruksi --}}
            <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">



                

                <div
                    class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center
                        bg-gradient-to-r from-[#00A79D]/10 to-[#0076A8]/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-[8px] bg-[#00A79D] text-white flex items-center justify-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
                                <path d="M8 12h8M8 8h5M8 16h6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#092C4C]">Ringkasan Materi AI</h3>
                            <p class="text-xs text-slate-400">{{ $meeting->title }}</p>
                        </div>
                    </div>
                    
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">

                    {{-- State: idle --}}
                    <div id="summary-idle" class="text-center py-10">
                        <div
                            class="w-16 h-16 rounded-full bg-teal-50 text-[#00A79D] flex items-center justify-center mx-auto mb-4">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3M6.343 6.343l-.707-.707M6.343 17.657l-.707.707M15.536 8.464a5 5 0 1 1-7.072 7.072" />
                            </svg>
                        </div>
                        <p class="text-slate-600 font-semibold mb-1">Belum ada ringkasan</p>
                        <p class="text-sm text-slate-400 mb-5">AI akan membaca semua materi di halaman ini<br>dan
                            membuatkan ringkasan untukmu.</p>
                        <button onclick="generateSummary()"
                            class="bg-[#00A79D] hover:bg-[#008f87] text-white px-6 py-2.5 rounded-[8px] text-sm font-semibold transition-colors shadow-md shadow-teal-100">
                            ✨ Generate Ringkasan
                        </button>
                    </div>

                    {{-- State: loading --}}
                    <div id="summary-loading" class="hidden text-center py-10">
                        <div
                            class="w-12 h-12 border-4 border-teal-100 border-t-[#00A79D] rounded-full animate-spin mx-auto mb-4">
                        </div>
                        <p class="text-slate-600 font-medium">AI sedang membaca materi...</p>
                        <p class="text-xs text-slate-400 mt-1">Ini mungkin memakan waktu 10–30 detik</p>
                    </div>

                    {{-- State: result --}}
                    <div id="summary-result" class="hidden">
                        <div id="summary-cached-badge" class="hidden mb-3">
                            <span
                                class="text-xs bg-teal-50 text-teal-600 border border-teal-100 px-2.5 py-1 rounded-full font-medium">
                                ✓ Ringkasan tersimpan
                            </span>
                            <span id="summary-date" class="text-xs text-slate-400 ml-2"></span>
                        </div>
                        <div id="summary-text"
                            class="prose-viewer text-sm text-slate-600 leading-relaxed bg-slate-50 rounded-[10px] p-4 border border-slate-100">
                        </div>
                    </div>

                    {{-- State: error --}}
                    <div id="summary-error" class="hidden text-center py-8">
                        <div
                            class="w-12 h-12 rounded-full bg-red-50 text-red-400 flex items-center justify-center mx-auto mb-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <p class="text-slate-600 font-medium mb-1">Terjadi Kesalahan</p>
                        <p id="summary-error-msg" class="text-xs text-red-400 mb-4"></p>
                        <button onclick="generateSummary()"
                            class="text-sm text-[#00A79D] hover:underline font-medium">Coba Lagi</button>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center rounded-b-[15px]">
                    <p class="text-[10px] text-slate-400"></p>
                    <div class="flex gap-3">
                        <button id="btn-regenerate" onclick="generateSummary(true)"
                            class="hidden text-xs text-slate-500 hover:text-[#092C4C] border border-slate-200 px-3 py-1.5 rounded-[6px] transition-colors">
                            🔄 Generate Ulang
                        </button>
                    </div>
                </div>
            </div>
        </div>


        {{-- KOLOM KANAN --}}
        <div class="space-y-6">
            @if ($meeting->type == 'tugas')

                {{-- Info Penugasan (collapsible, default tertutup) --}}
                <div class="bg-white rounded-[15px] shadow-sm border border-slate-200 overflow-hidden">
                    <button onclick="toggleCollapse('info-penugasan', this)"
                        class="w-full flex items-center justify-between p-6 text-left hover:bg-slate-50 transition-colors">
                        <h3 class="font-bold text-[#092C4C] flex items-center gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Info Penugasan
                        </h3>
                        <svg id="chevron-info-penugasan" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5"
                            class="text-slate-400 transition-transform duration-200">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div id="info-penugasan" class="hidden px-6 pb-6 space-y-4 border-t border-slate-100 pt-4">
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
                                    <p>Tugas ini tidak menerima pengumpulan keterlambatan.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Pengumpulan Tugas --}}
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="font-bold text-[#092C4C] mb-4">Pengumpulan Tugas Anda</h3>
                    @php $isLate = $meeting->deadline && now()->gt($meeting->deadline); @endphp

                    @if ($submission)
                        <div class="bg-teal-50 rounded-[10px] p-4 text-center mb-4">
                            <span class="block text-xl font-bold text-[#00A79D]">Terkumpul</span>
                            <span
                                class="text-xs font-medium uppercase mt-1 block {{ $submission->submitted_at > $meeting->deadline && $meeting->deadline ? 'text-red-500' : 'text-slate-500' }}">
                                {{ $submission->submitted_at > $meeting->deadline && $meeting->deadline ? 'Terlambat' : 'Tepat Waktu' }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-400 text-center mb-4">
                            Dikumpulkan {{ $submission->submitted_at->format('d M Y, H:i') }}
                        </p>

                        <div class="space-y-3">
                            <button onclick="toggleModal('modalSubmission')"
                                class="w-full bg-[#092C4C] hover:bg-[#061b30] text-white py-2.5 rounded-[8px] text-sm font-medium transition-all shadow-md shadow-slate-200">
                                Lihat / Edit Jawaban
                            </button>
                            <form action="{{ route('student.meetings.cancel', $meeting->id) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengumpulan ini? File yang sudah diunggah akan dihapus.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-white border border-red-200 text-red-500 hover:bg-red-50 py-2.5 rounded-[8px] text-sm font-medium transition-all">
                                    Batalkan Pengumpulan
                                </button>
                            </form>
                        </div>
                    @else
                        @if ($meeting->disable_late_submission && $isLate)
                            <div class="bg-red-50 rounded-[10px] p-4 text-center mb-4 border border-red-100">
                                <span class="block text-xl font-bold text-red-600">Ditutup</span>
                                <span class="text-xs font-medium text-red-500 uppercase mt-1 block">Melewati Tenggat</span>
                            </div>
                        @else
                            <div class="bg-slate-50 rounded-[10px] p-4 text-center mb-4 border border-slate-100">
                                <span class="block text-xl font-bold text-slate-500">Belum Ada</span>
                                <span class="text-xs font-medium text-slate-400 uppercase mt-1 block">Tugas belum
                                    dikirim</span>
                            </div>
                            <button onclick="toggleModal('modalSubmission')"
                                class="w-full bg-[#00A79D] hover:bg-[#008f87] text-white py-2.5 rounded-[8px] text-sm font-medium transition-all shadow-md shadow-teal-100">
                                Kerjakan Tugas Sekarang
                            </button>
                        @endif
                    @endif
                </div>

                {{-- Hasil Penilaian --}}
                @if ($submission && $submission->score !== null)
                    <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                        <h3 class="font-bold text-[#092C4C] mb-4 flex items-center gap-2">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M9 11l3 3L22 4" />
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                            </svg>
                            Hasil Penilaian
                        </h3>

                        <div class="bg-teal-50 rounded-[10px] p-4 text-center mb-4 border border-teal-100">
                            <p class="text-xs font-semibold text-teal-600 uppercase tracking-wide mb-1">Nilai Anda</p>
                            <div class="flex items-baseline justify-center gap-1">
                                <span class="text-4xl font-bold text-[#00A79D]">{{ $submission->score }}</span>
                                @if ($meeting->max_score)
                                    <span class="text-sm font-medium text-teal-500">/ {{ $meeting->max_score }}</span>
                                @endif
                            </div>
                            @if ($meeting->max_score)
                                @php $pct = min(100, round(($submission->score / $meeting->max_score) * 100)); @endphp
                                <div class="mt-3 bg-teal-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-full bg-[#00A79D] rounded-full" style="width: {{ $pct }}%">
                                    </div>
                                </div>
                                <p class="text-[10px] text-teal-500 mt-1">{{ $pct }}% dari nilai maksimal</p>
                            @endif
                        </div>

                        @if ($submission->feedback)
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Catatan dari
                                    Guru</p>
                                <div class="bg-slate-50 rounded-[8px] p-3 border border-slate-100">
                                    <p class="text-sm text-slate-600 leading-relaxed">
                                        {{ trim($submission->feedback) }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic text-center">Tidak ada catatan tambahan dari guru.</p>
                        @endif
                    </div>
                @elseif ($submission && $submission->score === null)
                    <div class="bg-white rounded-[15px] p-5 shadow-sm border border-slate-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center flex-shrink-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#092C4C]">Menunggu Penilaian</p>
                                <p class="text-xs text-slate-400 mt-0.5">Guru belum memberikan nilai untuk tugas ini.</p>
                            </div>
                        </div>
                    </div>
                @endif

            @endif
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL VIEW / EDIT TUGAS                                      --}}
    {{-- ============================================================ --}}
    <div id="modalSubmission" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm"
            onclick="toggleModal('modalSubmission')"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-[15px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">

                {{-- HEADER --}}
                <div class="bg-white px-6 pb-4 pt-5 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-[#092C4C]" id="modal-title">
                            {{ $submission ? 'Detail Jawaban' : 'Kumpulkan Tugas' }}
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $meeting->title }}</p>
                    </div>
                    <button type="button" onclick="toggleModal('modalSubmission')"
                        class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- ── VIEW MODE (hanya saat ada submission) ── --}}
                @if ($submission)
                    <div id="view-mode" class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">

                        {{-- Jawaban teks --}}
                        @if ($submission->content_text)
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Jawaban</p>
                                <div
                                    class="bg-slate-50 rounded-[8px] p-4 border border-slate-100 text-sm text-slate-600 leading-relaxed prose-viewer">
                                    {!! $submission->content_text !!}
                                </div>
                            </div>
                        @endif

                        {{-- Links --}}
                        @php $subLinks = is_array($submission->links) ? $submission->links : []; @endphp
                        @if (count($subLinks) > 0)
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Tautan</p>
                                <div class="space-y-2">
                                    @foreach ($subLinks as $subLink)
                                        <a href="{{ $subLink }}" target="_blank"
                                            class="flex items-center gap-2 bg-slate-50 p-2.5 rounded-[8px] border border-slate-200 hover:border-[#00A79D] hover:bg-teal-50 transition-colors group">
                                            <div
                                                class="w-7 h-7 rounded bg-white border border-slate-200 text-[#00A79D] flex items-center justify-center flex-shrink-0">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" />
                                                    <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" />
                                                </svg>
                                            </div>
                                            <span
                                                class="text-xs text-slate-600 truncate group-hover:text-[#00A79D]">{{ $subLink }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Files --}}
                        @php $subFiles = is_array($submission->files) ? $submission->files : []; @endphp
                        @if (count($subFiles) > 0)
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">File Lampiran
                                </p>
                                <div class="space-y-2">
                                    @foreach ($subFiles as $subFile)
                                        @if (is_array($subFile) && isset($subFile['path']))
                                            <a href="{{ asset('storage/' . $subFile['path']) }}" target="_blank"
                                                class="flex items-center gap-3 bg-white border border-slate-200 rounded-[8px] p-2.5 hover:border-[#00A79D] hover:shadow-sm transition-all group">
                                                <div
                                                    class="w-8 h-8 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2">
                                                        <path
                                                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                        <polyline points="14 2 14 8 20 8" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-xs font-semibold text-slate-700 truncate group-hover:text-[#00A79D]">
                                                        {{ $subFile['original_name'] ?? 'File' }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-400">
                                                        {{ isset($subFile['size']) ? number_format($subFile['size'] / 1024, 1) . ' KB' : '' }}
                                                    </p>
                                                </div>
                                                <span class="text-[10px] text-[#00A79D] flex-shrink-0">Buka ↗</span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Kosong --}}
                        @if (!$submission->content_text && count($subLinks) === 0 && count($subFiles) === 0)
                            <div class="text-center py-6">
                                <p class="text-slate-400 italic text-sm">Tidak ada jawaban yang tersimpan.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer View Mode --}}
                    <div id="view-mode-footer"
                        class="bg-slate-50 px-6 py-4 rounded-b-[15px] border-t border-slate-100 flex flex-row-reverse gap-3">
                        @php $canEdit = !($meeting->disable_late_submission && $isLate); @endphp
                        @if ($canEdit)
                            <button type="button" onclick="switchToEditMode()"
                                class="bg-[#092C4C] hover:bg-[#061b30] text-white px-5 py-2 rounded-[8px] text-sm font-medium transition-colors flex items-center gap-2">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                                Edit Jawaban
                            </button>
                        @endif
                        <button type="button" onclick="toggleModal('modalSubmission')"
                            class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 px-5 py-2 rounded-[8px] text-sm font-medium transition-colors">
                            Tutup
                        </button>
                    </div>
                @endif

                {{-- ── EDIT / CREATE MODE ── --}}
                <form action="{{ route('student.meetings.submit', $meeting->id) }}" method="POST"
                    enctype="multipart/form-data" id="submissionForm">
                    @csrf

                    <div id="edit-mode"
                        class="{{ $submission ? 'hidden' : '' }} px-6 py-5 space-y-6 max-h-[70vh] overflow-y-auto">

                        {{-- 1. Jawaban --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#092C4C] mb-2">
                                Jawaban / Deskripsi
                                <span class="text-xs font-normal text-slate-400 ml-1">(opsional jika melampirkan
                                    file)</span>
                            </label>
                            <div
                                class="border border-slate-300 rounded-[8px] overflow-hidden focus-within:border-[#00A79D] transition-all">
                                <div id="submission-editor-container" class="bg-white min-h-[150px] text-sm"></div>
                            </div>
                            <input type="hidden" name="content_text" id="submission-content">
                        </div>

                        {{-- 2. Tautan --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-semibold text-[#092C4C]">
                                    Tautan <span class="text-xs font-normal text-slate-400">(opsional)</span>
                                </label>
                                <button type="button" onclick="addSubmissionLinkField()"
                                    class="text-xs font-semibold text-[#00A79D] hover:text-[#008f87] flex items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                    Tambah Tautan
                                </button>
                            </div>
                            <div id="submission-link-container" class="space-y-2">
                                @if ($submission && is_array($submission->links))
                                    @foreach ($submission->links as $existingLink)
                                        <div class="flex gap-2 items-center">
                                            <input type="url" name="links[]" value="{{ $existingLink }}"
                                                class="w-full rounded-[8px] border border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 text-sm py-2 px-3"
                                                placeholder="https://...">
                                            <button type="button" onclick="this.parentElement.remove()"
                                                class="text-red-400 hover:text-red-600 flex-shrink-0">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <line x1="18" y1="6" x2="6" y2="18" />
                                                    <line x1="6" y1="6" x2="18" y2="18" />
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            @if (!$submission || !is_array($submission->links) || count($submission->links) === 0)
                                <p class="text-xs text-slate-400 mt-2">Klik "Tambah Tautan" untuk menambahkan link
                                    referensi.</p>
                            @endif
                        </div>

                        {{-- 3. File --}}
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-3">
                                <label class="block text-sm font-semibold text-[#092C4C]">
                                    File <span class="text-xs font-normal text-slate-400">(opsional)</span>
                                </label>
                                <button type="button" onclick="addSubmissionFileField()"
                                    class="text-xs font-semibold text-[#00A79D] hover:text-[#008f87] flex items-center gap-1">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                    Tambah File
                                </button>
                            </div>

                            @if ($submission && is_array($submission->files) && count($submission->files) > 0)
                                <div class="mb-3">
                                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">File
                                        Sebelumnya</p>
                                    <div class="space-y-2">
                                        @foreach ($submission->files as $subFile)
                                            @if (is_array($subFile) && isset($subFile['path']))
                                                <div
                                                    class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-[8px] p-2">
                                                    <div
                                                        class="w-8 h-8 rounded bg-red-50 text-red-400 flex items-center justify-center flex-shrink-0">
                                                        <svg width="16" height="16" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path
                                                                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                            <polyline points="14 2 14 8 20 8" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-semibold text-slate-700 truncate">
                                                            {{ $subFile['original_name'] ?? 'File' }}</p>
                                                        <p class="text-[10px] text-slate-400">
                                                            {{ isset($subFile['size']) ? number_format($subFile['size'] / 1024, 1) . ' KB' : '' }}
                                                        </p>
                                                    </div>
                                                    <a href="{{ asset('storage/' . $subFile['path']) }}" target="_blank"
                                                        class="text-[10px] text-[#00A79D] hover:underline flex-shrink-0">Lihat</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <p class="text-[11px] text-amber-600 mt-2 bg-amber-50 rounded px-2 py-1">
                                        ⚠ Upload file baru akan <strong>menggantikan</strong> file di atas.
                                    </p>
                                </div>
                            @endif

                            <div id="submission-file-container" class="space-y-2">
                                <div class="flex gap-2 items-center">
                                    <input type="file" name="files[]"
                                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 border border-slate-300 rounded-[8px] p-1.5">
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 mt-2">Format: PDF, DOCX, JPG, PNG &bull; Maks. 5MB per
                                file</p>
                        </div>

                    </div>

                    {{-- Footer Edit / Create Mode --}}
                    <div id="edit-mode-footer"
                        class="{{ $submission ? 'hidden' : '' }} bg-slate-50 px-6 py-4 rounded-b-[15px] border-t border-slate-100">

                        @if ($submission && $meeting->disable_late_submission && $isLate)
                            <div
                                class="flex items-start gap-2 bg-red-50 border border-red-100 text-red-600 text-xs font-medium px-3 py-2.5 rounded-[8px] mb-3">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p>Tenggat waktu telah lewat. Jawaban ini hanya dapat dilihat dan tidak bisa diubah.</p>
                            </div>
                        @endif

                        <div class="flex flex-row-reverse gap-3">
                            <button type="submit" @if ($submission && $meeting->disable_late_submission && $isLate) disabled @endif
                                class="bg-[#00A79D] hover:bg-[#008f87] text-white px-5 py-2 rounded-[8px] text-sm font-medium shadow-md shadow-teal-100 transition-colors
                            {{ $submission && $meeting->disable_late_submission && $isLate ? 'opacity-40 cursor-not-allowed pointer-events-none' : '' }}">
                                {{ $submission ? 'Simpan Perubahan' : 'Kirim Tugas' }}
                            </button>
                            @if ($submission)
                                {{-- Tombol Batal — kembali ke view mode --}}
                                <button type="button" onclick="switchToViewMode()"
                                    class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 px-5 py-2 rounded-[8px] text-sm font-medium transition-colors">
                                    Batal
                                </button>
                            @else
                                <button type="button" onclick="toggleModal('modalSubmission')"
                                    class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 px-5 py-2 rounded-[8px] text-sm font-medium transition-colors">
                                    Tutup
                                </button>
                            @endif
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL RINGKASAN AI                                           --}}
    {{-- ============================================================ --}}
    <div id="modalSummary" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" onclick="closeSummaryModal()"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-[15px] shadow-xl w-full max-w-2xl overflow-hidden">

                {{-- Header --}}
                <div
                    class="px-6 pt-5 pb-4 border-b border-slate-100 flex justify-between items-center
                        bg-gradient-to-r from-[#00A79D]/10 to-[#0076A8]/10">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-[8px] bg-[#00A79D] text-white flex items-center justify-center">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2z" />
                                <path d="M8 12h8M8 8h5M8 16h6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-[#092C4C]">Ringkasan Materi AI</h3>
                            <p class="text-xs text-slate-400">{{ $meeting->title }}</p>
                        </div>
                    </div>
                
                </div>

                {{-- Body --}}
                <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">

                    {{-- State: idle --}}
                    <div id="summary-idle" class="text-center py-10">
                        <div
                            class="w-16 h-16 rounded-full bg-teal-50 text-[#00A79D] flex items-center justify-center mx-auto mb-4">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3M6.343 6.343l-.707-.707M6.343 17.657l-.707.707M15.536 8.464a5 5 0 1 1-7.072 7.072" />
                            </svg>
                        </div>
                        <p class="text-slate-600 font-semibold mb-1">Belum ada ringkasan</p>
                        <p class="text-sm text-slate-400 mb-5">AI akan membaca semua materi di halaman ini<br>dan
                            membuatkan ringkasan untukmu.</p>
                        <button onclick="generateSummary()"
                            class="bg-[#00A79D] hover:bg-[#008f87] text-white px-6 py-2.5 rounded-[8px] text-sm font-semibold transition-colors shadow-md shadow-teal-100">
                            ✨ Generate Ringkasan
                        </button>
                    </div>

                    {{-- State: loading --}}
                    <div id="summary-loading" class="hidden text-center py-10">
                        <div
                            class="w-12 h-12 border-4 border-teal-100 border-t-[#00A79D] rounded-full animate-spin mx-auto mb-4">
                        </div>
                        <p class="text-slate-600 font-medium">AI sedang membaca materi...</p>
                        <p class="text-xs text-slate-400 mt-1">Ini mungkin memakan waktu 10–30 detik</p>
                    </div>

                    {{-- State: result --}}
                    <div id="summary-result" class="hidden">
                        <div id="summary-cached-badge" class="hidden mb-3">
                            <span
                                class="text-xs bg-teal-50 text-teal-600 border border-teal-100 px-2.5 py-1 rounded-full font-medium">
                                ✓ Ringkasan tersimpan
                            </span>
                            <span id="summary-date" class="text-xs text-slate-400 ml-2"></span>
                        </div>
                        <div id="summary-text"
                            class="prose-viewer text-sm text-slate-600 leading-relaxed bg-slate-50 rounded-[10px] p-4 border border-slate-100">
                        </div>
                    </div>

                    {{-- State: error --}}
                    <div id="summary-error" class="hidden text-center py-8">
                        <div
                            class="w-12 h-12 rounded-full bg-red-50 text-red-400 flex items-center justify-center mx-auto mb-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <p class="text-slate-600 font-medium mb-1">Terjadi Kesalahan</p>
                        <p id="summary-error-msg" class="text-xs text-red-400 mb-4"></p>
                        <button onclick="generateSummary()"
                            class="text-sm text-[#00A79D] hover:underline font-medium">Coba Lagi</button>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center rounded-b-[15px]">
                    <p class="text-[10px] text-slate-400"></p>
                    <div class="flex gap-3">
                        <button id="btn-regenerate" onclick="generateSummary(true)"
                            class="hidden text-xs text-slate-500 hover:text-[#092C4C] border border-slate-200 px-3 py-1.5 rounded-[6px] transition-colors">
                            🔄 Generate Ulang
                        </button>
                        <button onclick="closeSummaryModal()"
                            class="text-sm bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 px-4 py-1.5 rounded-[8px] font-medium transition-colors">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- SCRIPTS                                                       --}}
    {{-- ============================================================ --}}
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // ── Quill Editor ──
        const submissionQuill = new Quill('#submission-editor-container', {
            theme: 'snow',
            placeholder: 'Tulis jawaban Anda di sini...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Isi editor dengan konten submission yang sudah ada (jika edit)
        @if ($submission && $submission->content_text)
            submissionQuill.root.innerHTML = {!! json_encode($submission->content_text) !!};
        @endif

        // Salin isi Quill ke hidden input sebelum form di-submit
        document.getElementById('submissionForm').addEventListener('submit', function() {
            document.getElementById('submission-content').value = submissionQuill.root.innerHTML;
        });

        // ── Tambah field tautan ──
        function addSubmissionLinkField() {
            const container = document.getElementById('submission-link-container');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2', 'items-center');
            div.innerHTML = `
                <input type="url" name="links[]"
                    class="w-full rounded-[8px] border border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 text-sm py-2 px-3"
                    placeholder="https://...">
                <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-400 hover:text-red-600 flex-shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;
            container.appendChild(div);
        }

        // ── Tambah field file ──
        function addSubmissionFileField() {
            const container = document.getElementById('submission-file-container');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2', 'items-center');
            div.innerHTML = `
                <input type="file" name="files[]"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 border border-slate-300 rounded-[8px] p-1.5">
                <button type="button" onclick="this.parentElement.remove()"
                    class="text-red-400 hover:text-red-600 flex-shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;
            container.appendChild(div);
        }

        function toggleModal(id) {
            const modal = document.getElementById(id);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                // Reset ke view mode saat modal ditutup
                @if ($submission)
                    switchToViewMode();
                @endif
            }
        }

        function switchToEditMode() {
            document.getElementById('view-mode').classList.add('hidden');
            document.getElementById('view-mode-footer').classList.add('hidden');
            document.getElementById('edit-mode').classList.remove('hidden');
            document.getElementById('edit-mode-footer').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Edit Jawaban';
        }

        function switchToViewMode() {
            document.getElementById('edit-mode').classList.add('hidden');
            document.getElementById('edit-mode-footer').classList.add('hidden');
            document.getElementById('view-mode').classList.remove('hidden');
            document.getElementById('view-mode-footer').classList.remove('hidden');
            document.getElementById('modal-title').textContent = 'Detail Jawaban';
        }

        function toggleCollapse(id, btn) {
            const el = document.getElementById(id);
            const chevron = document.getElementById('chevron-' + id);
            const isHidden = el.classList.contains('hidden');
            el.classList.toggle('hidden', !isHidden);
            chevron.style.transform = isHidden ? 'rotate(180deg)' : '';
        }

        // ── Summary Modal ──
        const summaryRoute = "{{ route('student.meetings.summary', $meeting->id) }}";

        function openSummaryModal() {
            document.getElementById('modalSummary').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Cek apakah sudah ada summary tersimpan
            @if (isset($submission) && $submission?->ai_summary)
                showSummaryResult(
                    {!! json_encode($submission->ai_summary) !!},
                    "{{ $submission->ai_summary_generated_at?->format('d M Y, H:i') }}",
                    true
                );
            @else
                showState('idle');
            @endif
        }

        function closeSummaryModal() {
            document.getElementById('modalSummary').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function showState(state) {
            ['idle', 'loading', 'result', 'error'].forEach(s => {
                document.getElementById('summary-' + s).classList.add('hidden');
            });
            document.getElementById('summary-' + state).classList.remove('hidden');
        }

        function showSummaryResult(text, date, cached) {
            document.getElementById('summary-text').innerHTML = marked.parse(text);
            document.getElementById('summary-date').textContent = 'Dibuat ' + date;

            if (cached) {
                document.getElementById('summary-cached-badge').classList.remove('hidden');
                document.getElementById('btn-regenerate').classList.remove('hidden');
            } else {
                document.getElementById('summary-cached-badge').classList.remove('hidden');
                document.getElementById('btn-regenerate').classList.remove('hidden');
            }

            showState('result');
        }

        async function generateSummary(forceRegenerate = false) {
            showState('loading');
            document.getElementById('btn-regenerate').classList.add('hidden');

            try {
                const res = await fetch(summaryRoute + (forceRegenerate ? '?force=1' : ''), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                });

                const data = await res.json();

                if (!res.ok) {
                    document.getElementById('summary-error-msg').textContent = data.error ?? 'Unknown error';
                    showState('error');
                    return;
                }

                showSummaryResult(data.summary, data.generated_at, data.cached);

            } catch (err) {
                document.getElementById('summary-error-msg').textContent = 'Koneksi gagal. Periksa internet kamu.';
                showState('error');
            }
        }
    </script>
@endsection
