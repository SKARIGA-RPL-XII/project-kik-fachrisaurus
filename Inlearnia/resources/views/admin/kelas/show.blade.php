@extends('layouts.app')

@section('content')
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .active-tab {
            color: #00A79D !important;
            border-bottom-color: #00A79D !important;
        }
    </style>

    <x-sidebar />

    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'List Kelas', 'url' => route('admin.kelas.index')],
                ['label' => $kelas->name, 'url' => null],
            ]" />
        </div>
        <x-calendar />
    </div>

    {{-- Header Kelas --}}
    <div id="kelas-header"
        class="w-full bg-white rounded-[20px] shadow-lg shadow-slate-200/50 border border-slate-200 overflow-hidden mb-8 group relative z-0 transition-all duration-300">
        <div class="relative w-full h-[220px] md:h-[260px] overflow-hidden">
            <div class="absolute inset-0 bg-slate-800 z-0">
                <img src="{{ $kelas->logo ? asset('storage/' . $kelas->logo) : 'https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg' }}"
                    class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 group-hover:brightness-110">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-t from-[#092C4C] via-[#092C4C]/60 to-transparent z-10 transition-opacity duration-500 ease-in-out">
            </div>
            <div class="absolute bottom-0 left-0 w-full p-6 md:p-8 z-20 flex flex-col justify-end h-full">
                <div class="flex flex-col gap-1 transition-transform duration-500 group-hover:-translate-y-2">
                    <div class="flex items-center gap-3">
                        <p class="text-slate-100 text-sm md:text-base font-medium flex items-center gap-2 drop-shadow-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            {{ $kelas->subject->name ?? 'Mata Pelajaran Umum' }}
                        </p>
                        <span class="bg-[#00A79D] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-md">
                            {{ $kelas->subject->curriculum ?? 'Kurikulum Merdeka' }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight leading-none drop-shadow-md">
                        {{ $kelas->name }}
                    </h1>

                    @if ($kelas->description)
                        <p class="text-slate-200 text-xs md:text-sm mt-2 line-clamp-2 max-w-3xl drop-shadow-sm font-light leading-relaxed group-hover:text-slate-100 transition-colors">
                            {{ $kelas->description }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div
            class="relative z-30 bg-white px-12 py-5 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0 border-t border-slate-100">
            <div class="flex items-center gap-4 w-full md:w-auto border-b md:border-b-0 border-slate-100 pb-4 md:pb-0">
                <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                    <img src="{{ $kelas->teacher?->profile_photo ? asset('storage/' . $kelas->teacher->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($kelas->teacher?->name ?? 'Unknown') . '&background=044153&color=ffffff' }}"
                        class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Pengajar Kelas</p>
                    <p class="text-[#092C4C] font-bold text-base leading-none">
                        {{ $kelas->teacher?->name ?? 'Tidak ada pengajar' }}</p>
                </div>
            </div>

            <div class="flex items-center gap-8 md:gap-12 w-full md:w-auto justify-around md:justify-end">
                <div class="text-center cursor-default">
                    <span class="block text-xl font-bold text-orange-500">{{ $kelas->students_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Siswa</span>
                </div>
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                <div class="text-center cursor-default">
                    <span class="block text-xl font-bold text-[#00A79D]">{{ $kelas->meetings_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Pertemuan</span>
                </div>
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                <div class="text-center cursor-default">
                    <span class="block text-xl font-bold text-purple-600">{{ $kelas->announcements_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Info</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('beranda')" id="tab-btn-beranda"
                class="tab-btn active-tab py-4 px-1 border-b-2 font-medium text-sm">Beranda</button>
            <button onclick="switchTab('pertemuan')" id="tab-btn-pertemuan"
                class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Daftar
                Pertemuan</button>
            <button onclick="switchTab('anggota')" id="tab-btn-anggota"
                class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Anggota
                Kelas</button>
        </nav>
    </div>

    {{-- Tab 1: Beranda --}}
    <div id="tab-content-beranda" class="tab-content block animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                @forelse($feeds as $item)
                    @if ($item->feed_type === 'announcement')
                        <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                            <div class="flex items-start gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden border border-slate-200">
                                    <img src="{{ $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->name) . '&background=092C4C&color=ffffff' }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="font-bold text-[#092C4C] text-sm md:text-base leading-tight">
                                        {{ $item->user->name }}</h3>
                                    <span
                                        class="text-[11px] font-medium text-slate-400">{{ $item->created_at->format('d M Y - H:i') }}</span>
                                </div>
                            </div>

                            <div class="prose max-w-none text-slate-600 text-sm leading-relaxed">
                                {!! $item->content !!}
                            </div>

                            @if (is_array($item->links) && count($item->links) > 0)
                                <div class="border-t border-slate-100 pt-4 mt-4 space-y-2">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tautan
                                        Terlampir</p>
                                    @foreach ($item->links as $link)
                                        <a href="{{ $link }}" target="_blank"
                                            class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg hover:border-[#00A79D] transition-colors group/link w-full sm:w-max min-w-[250px]">
                                            <div
                                                class="w-8 h-8 rounded bg-teal-50 text-[#00A79D] flex items-center justify-center flex-shrink-0">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71">
                                                    </path>
                                                    <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71">
                                                    </path>
                                                </svg></div>
                                            <div class="flex-1 overflow-hidden">
                                                <p
                                                    class="text-xs font-semibold text-slate-700 truncate group-hover/link:text-[#00A79D]">
                                                    {{ parse_url($link, PHP_URL_HOST) ?? 'Tautan Eksternal' }}</p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ $link }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if (is_array($item->files) && count($item->files) > 0)
                                <div class="border-t border-slate-100 pt-4 mt-4">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">File Terlampir
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach ($item->files as $file)
                                            @if (is_array($file) && isset($file['path']))
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                    class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group/file">
                                                    <div
                                                        class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                                        <svg width="20" height="20" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path
                                                                d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                            </path>
                                                            <polyline points="14 2 14 8 20 8"></polyline>
                                                        </svg></div>
                                                    <div class="flex-1 min-w-0">
                                                        <p
                                                            class="text-xs font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]">
                                                            {{ $file['original_name'] ?? 'File' }}</p>
                                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                                            {{ isset($file['size']) ? number_format($file['size'] / 1024, 1) . ' KB' : 'Unknown' }}
                                                        </p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        {{-- Feed Pertemuan (read-only, tanpa link ke detail) --}}
                        <div class="bg-white rounded-[15px] shadow-sm border border-slate-200 relative overflow-hidden">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 {{ $item->type == 'tugas' ? 'bg-orange-400' : 'bg-blue-400' }}">
                            </div>
                            <div class="p-5 flex items-start gap-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 {{ $item->type == 'tugas' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500' }}">
                                    @if ($item->type == 'tugas')
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2">
                                            </path>
                                            <rect x="8" y="2" width="8" height="4" rx="1"
                                                ry="1"></rect>
                                            <path d="M9 14l2 2 4-4"></path>
                                        </svg>
                                    @else
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 pt-1">
                                    <h3 class="font-bold text-[#092C4C] text-base leading-tight mb-1">
                                        {{ $kelas->teacher?->name ?? 'Pengajar' }} memposting {{ $item->type }} baru:
                                        {{ $item->title }}</h3>
                                    <span
                                        class="text-xs font-medium text-slate-400 block mb-3">{{ $item->created_at->format('d M') }}</span>
                                    @if ($item->type == 'tugas' && $item->deadline)
                                        <div
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-red-50 text-red-600 text-xs font-medium">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            Tenggat: {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="text-center py-16 bg-slate-50 rounded-[15px] border border-dashed border-slate-200">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <p class="text-slate-500 font-medium">Belum ada aktivitas di kelas ini.</p>
                        <p class="text-slate-400 text-sm mt-1">Pengumuman dan materi yang dibuat akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Sidebar: Tugas Mendatang --}}
            <div class="hidden lg:block space-y-6">
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-[#00A79D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="font-bold text-[#092C4C]">Mendatang</h3>
                    </div>

                    @php
                        $upcomingTasks = $tabMeetings
                            ->filter(function ($meeting) {
                                return $meeting->type === 'tugas' &&
                                    $meeting->deadline &&
                                    \Carbon\Carbon::parse($meeting->deadline)->isFuture();
                            })
                            ->sortBy('deadline')
                            ->take(4);
                    @endphp

                    @if ($upcomingTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach ($upcomingTasks as $task)
                                <div>
                                    <p class="text-sm font-semibold text-slate-700 line-clamp-1">{{ $task->title }}</p>
                                    <p class="text-[11px] font-medium text-red-500 mt-1 flex items-center gap-1.5">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        @if (\Carbon\Carbon::parse($task->deadline)->isToday())
                                            Hari ini, {{ \Carbon\Carbon::parse($task->deadline)->format('H:i') }}
                                        @elseif(\Carbon\Carbon::parse($task->deadline)->isTomorrow())
                                            Besok, {{ \Carbon\Carbon::parse($task->deadline)->format('H:i') }}
                                        @else
                                            {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <button type="button" onclick="switchTab('pertemuan')"
                                class="text-[#00A79D] text-xs font-semibold hover:underline w-full text-left">Lihat semua
                                di Daftar Pertemuan</button>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-4 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-slate-400">Tidak ada tugas mendatang.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 2: Pertemuan (read-only) --}}
    <div id="tab-content-pertemuan" class="tab-content hidden animate-fade-in">
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
            <form id="searchMeetingForm" class="flex flex-col sm:flex-row gap-3 items-center flex-1 w-full max-w-3xl">
                <div class="w-full sm:w-auto">
                    <x-ui.search-bar placeholder="Cari materi atau tugas..." target="pertemuanResultContainer"
                        formId="searchMeetingForm" />
                </div>
                <div class="w-full sm:w-40 flex-shrink-0">
                    <select name="type_filter"
                        class="w-full h-[40px] border border-gray-200 rounded-[10px] text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00A79D] focus:border-[#00A79D] text-gray-600 bg-white cursor-pointer transition-all">
                        <option value="">Semua Jenis</option>
                        <option value="materi" {{ request('type_filter') == 'materi' ? 'selected' : '' }}>Materi</option>
                        <option value="tugas" {{ request('type_filter') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                    </select>
                </div>
                <div class="w-full sm:w-40 flex-shrink-0">
                    <select name="topic_filter"
                        class="w-full h-[40px] border border-gray-200 rounded-[10px] text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00A79D] focus:border-[#00A79D] text-gray-600 bg-white cursor-pointer transition-all">
                        <option value="">Semua Topik</option>
                        @foreach ($tabMeetings->pluck('topic')->filter()->unique() as $topic)
                            <option value="{{ $topic }}"
                                {{ request('topic_filter') == $topic ? 'selected' : '' }}>{{ $topic }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" name="tab" value="pertemuan">
            </form>
        </div>

        <div id="pertemuanResultContainer" class="space-y-8">
            @forelse($tabMeetings->groupBy(fn($item) => empty($item->topic) ? 'Tanpa Topik' : $item->topic) as $topic => $meetings)
                <div>
                    <h2
                        class="text-sm font-bold text-[#00A79D] uppercase tracking-wider mb-4 pl-2 border-b-2 border-teal-100 pb-2 flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg> {{ $topic }}
                    </h2>
                    <div class="space-y-3">
                        @foreach ($meetings as $meeting)
                            <div
                                class="bg-white rounded-[10px] shadow-sm border border-slate-100 hover:border-[#00A79D]/30 transition-all duration-300 group">
                                <div class="p-4 flex justify-between items-start cursor-pointer rounded-[10px]"
                                    onclick="toggleAccordion('accordion-{{ $meeting->id }}')">
                                    <div class="flex gap-4 w-full">
                                        <div
                                            class="w-10 h-10 rounded-[8px] flex items-center justify-center flex-shrink-0 {{ $meeting->type == 'tugas' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                            @if ($meeting->type == 'tugas')
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2">
                                                    </path>
                                                    <rect x="8" y="2" width="8" height="4" rx="1"
                                                        ry="1"></rect>
                                                    <path d="M9 14l2 2 4-4"></path>
                                                </svg>
                                            @else
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span
                                                    class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase tracking-wider {{ $meeting->type == 'tugas' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">{{ ucfirst($meeting->type) }}</span>
                                                <span
                                                    class="text-[11px] font-medium text-slate-400">{{ $meeting->created_at->format('d M Y') }}</span>
                                            </div>
                                            <h3 class="font-bold text-[#092C4C] text-base leading-tight">
                                                {{ $meeting->title }}</h3>
                                            @if ($meeting->type == 'tugas')
                                                @if ($meeting->deadline)
                                                    <p
                                                        class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1">
                                                        <svg width="12" height="12" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <polyline points="12 6 12 16 14"></polyline>
                                                        </svg> Deadline:
                                                        {{ \Carbon\Carbon::parse($meeting->deadline)->format('d M Y, H:i') }}
                                                    </p>
                                                @else
                                                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1"><svg
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <line x1="15" y1="9" x2="9"
                                                                y2="15"></line>
                                                            <line x1="9" y1="9" x2="15"
                                                                y2="15"></line>
                                                        </svg> Tidak ada batasan waktu</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Chevron toggle --}}
                                    <svg class="w-5 h-5 text-slate-400 mt-1 flex-shrink-0 transition-transform duration-300"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-width="2" stroke-linecap="round" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div id="accordion-{{ $meeting->id }}"
                                    class="accordion-content max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out bg-slate-50/50 border-t border-transparent rounded-b-[10px]">
                                    <div class="p-5 md:p-6 text-sm text-slate-600 border-t border-slate-100">
                                        @if ($meeting->type == 'tugas')
                                            <div class="flex items-center gap-4 mb-4 text-xs font-medium">
                                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded">Max Nilai:
                                                    {{ $meeting->max_score ?? 'Tidak dinilai' }}</span>
                                                @if ($meeting->disable_late_submission)
                                                    <span
                                                        class="bg-red-50 text-red-600 px-3 py-1 rounded flex items-center gap-1"><svg
                                                            width="12" height="12" viewBox="0 0 24 24"
                                                            fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8">
                                                            </path>
                                                            <path d="M21 3v5h-5"></path>
                                                        </svg> Kunci Otomatis</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($meeting->description)
                                            <div class="prose prose-sm max-w-none mb-4 line-clamp-3">
                                                {!! $meeting->description !!}</div>
                                        @else
                                            <p class="mb-4 italic text-slate-400">Tidak ada instruksi tambahan.</p>
                                        @endif

                                        @php
                                            $rawFiles = $meeting->files;
                                            if (is_string($rawFiles)) {
                                                $rawFiles = json_decode($rawFiles, true);
                                                if (is_string($rawFiles)) {
                                                    $rawFiles = json_decode($rawFiles, true);
                                                }
                                            }
                                            $files = is_array($rawFiles) ? $rawFiles : [];
                                        @endphp

                                        @if (count($files) > 0)
                                            <div class="mb-5">
                                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                                                    File Terlampir</p>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                    @foreach ($files as $file)
                                                        @if (is_array($file) && isset($file['path']))
                                                            <a href="{{ asset('storage/' . $file['path']) }}"
                                                                target="_blank"
                                                                class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group/file">
                                                                <div
                                                                    class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                                                    <svg width="20" height="20"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2">
                                                                        <path
                                                                            d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                                        </path>
                                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                                    </svg></div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p
                                                                        class="text-xs font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]">
                                                                        {{ $file['original_name'] ?? 'File Lampiran' }}</p>
                                                                    <p class="text-[10px] text-slate-400 mt-0.5">
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-slate-50 rounded-[10px] border border-dashed border-slate-200">
                    <p class="text-slate-400 text-sm font-medium">Belum ada pertemuan di kelas ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Tab 3: Anggota Kelas --}}
    <div id="tab-content-anggota" class="tab-content hidden animate-fade-in">
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mt-1 mb-6 border-b border-slate-100 pb-4">
                <h3 class="text-[18px] font-bold text-[#092C4C]">Daftar Anggota Kelas</h3>
                <form id="searchAnggotaForm" class="w-full md:w-auto">
                    <x-ui.search-bar placeholder="Cari nama atau email siswa..." target="anggotaResultContainer"
                        formId="searchAnggotaForm" />
                    <input type="hidden" name="tab" value="anggota">
                </form>
            </div>

            <div id="anggotaResultContainer">
                <div class="overflow-hidden rounded-[15px] border border-slate-200">
                    <table class="w-full text-left">
                        <thead class="bg-[#0F4C5C] text-white text-[14px]">
                            <tr>
                                <th class="px-6 py-4 font-medium pl-10">Nama Anggota</th>
                                <th class="px-6 py-4 font-medium">Email</th>
                                <th class="px-6 py-4 font-medium">Peran</th>
                            </tr>
                        </thead>
                        <tbody class="text-[14px] text-[#092C4C]">
                            {{-- GANTI blok baris teacher di tabel anggota --}}
                            @if (!request('page') || request('page') == 1)
                                @if ($kelas->teacher)
                                    <tr class="border-b border-slate-200 bg-blue-50/30 hover:bg-slate-50 transition">
                                        <td class="pl-10 pr-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $kelas->teacher->profile_photo ? asset('storage/' . $kelas->teacher->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($kelas->teacher->name) . '&background=044153&color=ffffff' }}"
                                                    class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                                <div>
                                                    <div class="font-bold text-[#092C4C] flex items-center gap-2">
                                                        {{ $kelas->teacher->name }}
                                                        <svg class="w-4 h-4 text-blue-500" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="text-xs text-slate-400">ID: {{ $kelas->teacher->id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $kelas->teacher->email }}</td>
                                        <td class="px-6 py-4"><x-ui.badge label="Pengajar" color="primary" /></td>
                                    </tr>
                                @endif
                            @endif

                            @forelse($students as $student)
                                <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                    <td class="pl-10 pr-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $student->profile_photo ? asset('storage/' . $student->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=044153&color=ffffff' }}"
                                                class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                            <div>
                                                <div class="font-medium text-[#092C4C]">{{ $student->name }}</div>
                                                <div class="text-xs text-slate-400">ID: {{ $student->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $student->email }}</td>
                                    <td class="px-6 py-4"><x-ui.badge label="Siswa" color="success" /></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-10 text-slate-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            <span class="font-medium mt-2">Belum ada siswa yang bergabung</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-6 text-[13px] text-slate-500">
                    <div>Menampilkan {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} dari
                        {{ $students->total() }} data</div>
                    <div>
                        {{ $students->appends(['tab' => 'anggota', 'search' => request('search')])->onEachSide(1)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const tabStorageKey = 'activeTab_admin_class_{{ $kelas->id }}';

        function switchTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.replace('block', 'hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('text-[#00A79D]', 'border-[#00A79D]', 'active-tab');
                btn.classList.add('text-gray-500', 'border-transparent');
            });

            const selectedContent = document.getElementById(`tab-content-${tabName}`);
            const selectedBtn = document.getElementById(`tab-btn-${tabName}`);
            if (selectedContent) selectedContent.classList.replace('hidden', 'block');
            if (selectedBtn) {
                selectedBtn.classList.remove('text-gray-500', 'border-transparent');
                selectedBtn.classList.add('text-[#00A79D]', 'border-[#00A79D]', 'active-tab');
            }

            const header = document.getElementById('kelas-header');
            if (header) header.style.display = (tabName === 'beranda') ? 'block' : 'none';

            localStorage.setItem(tabStorageKey, tabName);
        }

        function toggleAccordion(contentId) {
            const target = document.getElementById(contentId);
            if (!target) return;

            document.querySelectorAll('.accordion-content').forEach(el => {
                if (el.id !== contentId) {
                    el.classList.add('max-h-0', 'opacity-0');
                    el.classList.remove('max-h-[3000px]', 'opacity-100');
                }
            });

            const isCollapsed = target.classList.contains('max-h-0');
            target.classList.toggle('max-h-0', !isCollapsed);
            target.classList.toggle('opacity-0', !isCollapsed);
            target.classList.toggle('max-h-[3000px]', isCollapsed);
            target.classList.toggle('opacity-100', isCollapsed);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('tab');
            switchTab(tabFromUrl || localStorage.getItem(tabStorageKey) || 'beranda');

            if (tabFromUrl) window.history.replaceState({}, document.title, window.location.href.split('?')[0]);

            @if (session('success'))
                Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                }).fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#0F4C5C'
                });
            @endif
        });
    </script>
@endsection
