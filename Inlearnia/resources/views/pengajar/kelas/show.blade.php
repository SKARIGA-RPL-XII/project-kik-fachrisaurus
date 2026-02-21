@extends('layouts.app')

@section('content')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .active-tab {
            color: #00A79D !important;
            border-bottom-color: #00A79D !important;
        }

        /* Quill Modal & Editor Global Styles */
        .ql-toolbar.ql-snow { border: none !important; border-bottom: 1px solid #cbd5e1 !important; background-color: #f8fafc; padding: 8px 12px !important; }
        .ql-container.ql-snow { border: none !important; font-family: inherit; font-size: 0.875rem; }
        .ql-editor.ql-blank::before { color: #94a3b8; font-style: normal; }
        #announcement-editor-container .ql-editor, #edit-announcement-editor-container .ql-editor { min-height: 120px !important; }

        /* Card Content Auto-Height & Reset Gap */
        .ql-snow .ql-editor.reset-quill-margins { min-height: auto !important; padding: 0 !important; }
        .reset-quill-margins p { margin-bottom: 0.5em !important; padding: 0 !important; }
        .reset-quill-margins p:first-child { margin-top: 0 !important; }
        .reset-quill-margins p:last-child { margin-bottom: 0 !important; }
    </style>

    <x-sidebar />

    <div class="flex justify-between items-center mb-8">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')], ['label' => $kelas->name, 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    {{-- Header Kelas --}}
    <div id="kelas-header" class="w-full bg-white rounded-[20px] shadow-lg shadow-slate-200/50 border border-slate-200 overflow-hidden mb-8 group relative z-0 transition-all duration-300">
        <div class="relative w-full h-[220px] md:h-[260px] overflow-hidden">
            <div class="absolute inset-0 bg-slate-800 z-0">
                <img src="{{ $kelas->logo ? asset('storage/' . $kelas->logo) : 'https://img.freepik.com/free-vector/gradient-abstract-wireframe-background_23-2149009903.jpg' }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110 group-hover:brightness-110">
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#092C4C] via-[#092C4C]/60 to-transparent z-10 transition-opacity duration-500 ease-in-out"></div>
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

        <div class="relative z-30 bg-white px-12 py-5 flex flex-col md:flex-row justify-between items-center gap-6 md:gap-0 border-t border-slate-100">
            <div class="flex items-center gap-4 w-full md:w-auto border-b md:border-b-0 border-slate-100 pb-4 md:pb-0">
                <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0">
                    <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=044153&color=ffffff' }}" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Pengajar Kelas</p>
                    <p class="text-[#092C4C] font-bold text-base leading-none">{{ $kelas->teacher->name ?? auth()->user()->name }}</p>
                </div>
            </div>

            <div class="flex items-center gap-8 md:gap-12 w-full md:w-auto justify-around md:justify-end">
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-orange-500 transition-colors">{{ $kelas->students_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Siswa</span>
                </div>
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-[#00A79D] transition-colors">{{ $kelas->meetings_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Pertemuan</span>
                </div>
                <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
                <div class="text-center group/stat cursor-default">
                    <span class="block text-xl font-bold text-purple-600 transition-colors">{{ $kelas->announcements_count }}</span>
                    <span class="text-[11px] text-slate-400 font-bold uppercase tracking-wide">Info</span>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6 border-b border-gray-200">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('beranda')" id="tab-btn-beranda" class="tab-btn active-tab py-4 px-1 border-b-2 font-medium text-sm">Beranda</button>
            <button onclick="switchTab('pertemuan')" id="tab-btn-pertemuan" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Daftar Pertemuan</button>
            <button onclick="switchTab('anggota')" id="tab-btn-anggota" class="tab-btn py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">Anggota Kelas</button>
        </nav>
    </div>

    {{-- Tab 1: Beranda --}}
    <div id="tab-content-beranda" class="tab-content block animate-fade-in">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-[15px] p-4 shadow-sm border border-slate-200 flex gap-4 items-center cursor-pointer hover:border-[#00A79D]/50 transition-all" onclick="toggleModal('modalPengumuman')">
                    <div class="w-10 h-10 rounded-full flex-shrink-0 bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=044153&color=ffffff' }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center w-full px-4 py-2 rounded-full bg-slate-50 text-slate-500 text-sm font-medium border border-slate-200 hover:bg-slate-100 hover:border-slate-300 transition-all">
                            Umumkan sesuatu ke kelas {{ $kelas->name }}...
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-full flex items-center justify-center" style="background-color: #CFF6F5;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#00A79D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </div>
                </div>

                @forelse($feeds as $item)
                    @if ($item->feed_type === 'announcement')
                        <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex gap-3 items-center">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden border border-slate-200">
                                        <img src="{{ $item->user->profile_photo ? asset('storage/' . $item->user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($item->user->name) . '&background=092C4C&color=ffffff' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-[#092C4C] text-sm md:text-base leading-tight">{{ $item->user->name }}</h3>
                                        <span class="text-[11px] font-medium text-slate-400">{{ $item->created_at->format('d M Y - H:i') }}</span>
                                    </div>
                                </div>

                                @if (auth()->id() === $item->user_id || auth()->id() === $kelas->teacher_id)
                                    <div class="relative" onclick="event.stopPropagation()">
                                        <button onclick="toggleDropdown('menu-pengumuman-{{ $item->id }}')" class="p-1.5 text-slate-400 hover:text-[#092C4C] hover:bg-slate-50 rounded-md transition-colors"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
                                        <div id="menu-pengumuman-{{ $item->id }}" class="hidden absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-slate-100 z-50 overflow-hidden">
                                            <button type="button" onclick="openEditAnnouncement({{ $item->id }})" class="block w-full text-left px-4 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00A79D]">Edit</button>
                                            <form action="{{ route('teacher.announcements.destroy', $item->id) }}" method="POST" class="delete-form">@csrf @method('DELETE')<button type="button" class="btn-delete block w-full text-left px-4 py-2.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors border-t border-slate-50">Hapus</button></form>
                                        </div>
                                    </div>
                                    <textarea id="raw-content-{{ $item->id }}" class="hidden">{{ $item->content }}</textarea>
                                @endif
                            </div>

                            <div class="mb-4">
                                <div class="prose max-w-none text-slate-600 text-sm leading-relaxed prose-viewer">
                                    {!! $item->content !!}
                                </div>
                            </div>

                            @if (is_array($item->links) && count($item->links) > 0)
                                <div class="border-t border-slate-100 pt-4 mt-4 space-y-2">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tautan Terlampir</p>
                                    @foreach ($item->links as $link)
                                        <a href="{{ $link }}" target="_blank" class="flex items-center gap-3 p-3 bg-white border border-slate-200 rounded-lg hover:border-[#00A79D] transition-colors group/link w-full sm:w-max min-w-[250px]">
                                            <div class="w-8 h-8 rounded bg-teal-50 text-[#00A79D] flex items-center justify-center flex-shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"></path></svg></div>
                                            <div class="flex-1 overflow-hidden">
                                                <p class="text-xs font-semibold text-slate-700 truncate group-hover/link:text-[#00A79D]">{{ parse_url($link, PHP_URL_HOST) ?? 'Tautan Eksternal' }}</p>
                                                <p class="text-[10px] text-slate-400 truncate">{{ $link }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if (is_array($item->files) && count($item->files) > 0)
                                <div class="border-t border-slate-100 pt-4 mt-4">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">File Terlampir</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach ($item->files as $file)
                                            @if (is_array($file) && isset($file['path']))
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group/file">
                                                    <div class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]" title="{{ $file['original_name'] ?? 'File' }}">{{ $file['original_name'] ?? 'File' }}</p>
                                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ isset($file['size']) ? number_format($file['size'] / 1024, 1) . ' KB' : 'Unknown' }}</p>
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <a href="{{ route('teacher.meetings.show', $item->id) }}" class="block bg-white rounded-[15px] shadow-sm border border-slate-200 hover:border-[#00A79D] hover:shadow-md transition-all group relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $item->type == 'tugas' ? 'bg-orange-400' : 'bg-blue-400' }}"></div>
                            <div class="p-5 flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $item->type == 'tugas' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500' }}">
                                    @if ($item->type == 'tugas')
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                                    @else
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 pt-1">
                                    <h3 class="font-bold text-[#092C4C] text-base group-hover:text-[#00A79D] transition-colors leading-tight mb-1">{{ $kelas->teacher->name }} memposting {{ $item->type }} baru: {{ $item->title }}</h3>
                                    <span class="text-xs font-medium text-slate-400 block mb-3">{{ $item->created_at->format('d M') }}</span>
                                    @if ($item->type == 'tugas' && $item->deadline)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-red-50 text-red-600 text-xs font-medium">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg> Tenggat: {{ \Carbon\Carbon::parse($item->deadline)->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                @empty
                    <div class="text-center py-16 bg-slate-50 rounded-[15px] border border-dashed border-slate-200">
                        <svg class="w-16 h-16 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="text-slate-500 font-medium">Belum ada aktivitas di kelas ini.</p>
                        <p class="text-slate-400 text-sm mt-1">Pengumuman dan materi yang dibuat akan muncul di sini.</p>
                    </div>
                @endforelse
            </div>

            {{-- KANAN: WIDGET MENDATANG --}}
            <div class="hidden lg:block space-y-6">
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-[#00A79D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-bold text-[#092C4C]">Mendatang</h3>
                    </div>
                    
                    @php
                        // Filter tugas yang punya deadline dan waktunya belum lewat, urutkan dari yang terdekat, ambil 4 teratas
                        $upcomingTasks = $tabMeetings->filter(function($meeting) {
                            return $meeting->type === 'tugas' && $meeting->deadline && \Carbon\Carbon::parse($meeting->deadline)->isFuture();
                        })->sortBy('deadline')->take(4);
                    @endphp

                    @if($upcomingTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingTasks as $task)
                                <div class="group">
                                    <a href="{{ route('teacher.meetings.show', $task->id) }}" class="block">
                                        <p class="text-sm font-semibold text-slate-700 group-hover:text-[#00A79D] transition-colors line-clamp-1">{{ $task->title }}</p>
                                        <p class="text-[11px] font-medium text-red-500 mt-1 flex items-center gap-1.5">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            @if(\Carbon\Carbon::parse($task->deadline)->isToday())
                                                Hari ini, {{ \Carbon\Carbon::parse($task->deadline)->format('H:i') }}
                                            @elseif(\Carbon\Carbon::parse($task->deadline)->isTomorrow())
                                                Besok, {{ \Carbon\Carbon::parse($task->deadline)->format('H:i') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($task->deadline)->format('d M Y, H:i') }}
                                            @endif
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-5 pt-4 border-t border-slate-100">
                            <button type="button" onclick="switchTab('pertemuan')" class="text-[#00A79D] text-xs font-semibold hover:underline w-full text-left">Lihat semua di Daftar Pertemuan</button>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-4 text-center">
                            <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mb-2">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-sm text-slate-400">Hore, tidak ada tugas yang perlu segera diperiksa!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tab 2: Pertemuan --}}
    <div id="tab-content-pertemuan" class="tab-content hidden animate-fade-in">
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
            <div class="flex-1 w-full max-w-3xl">
                <form id="searchMeetingForm" class="flex flex-col sm:flex-row gap-3 items-center">
                    <div class="w-full sm:w-auto">
                        <x-ui.search-bar placeholder="Cari materi atau tugas..." target="pertemuanResultContainer" formId="searchMeetingForm" />
                    </div>
                    <div class="w-full sm:w-40 flex-shrink-0">
                        <select name="type_filter" class="w-full h-[40px] border border-gray-200 rounded-[10px] text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00A79D] focus:border-[#00A79D] text-gray-600 bg-white cursor-pointer transition-all">
                            <option value="">Semua Jenis</option>
                            <option value="materi" {{ request('type_filter') == 'materi' ? 'selected' : '' }}>Materi</option>
                            <option value="tugas" {{ request('type_filter') == 'tugas' ? 'selected' : '' }}>Tugas</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-40 flex-shrink-0">
                        <select name="topic_filter" class="w-full h-[40px] border border-gray-200 rounded-[10px] text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-[#00A79D] focus:border-[#00A79D] text-gray-600 bg-white cursor-pointer transition-all">
                            <option value="">Semua Topik</option>
                            @foreach ($tabMeetings->pluck('topic')->filter()->unique() as $topic)
                                <option value="{{ $topic }}" {{ request('topic_filter') == $topic ? 'selected' : '' }}>{{ $topic }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="tab" value="pertemuan">
                </form>
            </div>

            <div class="relative w-full xl:w-auto flex-shrink-0">
                <button onclick="toggleDropdown('dropdownBuatPertemuan')" class="w-full xl:w-auto bg-[#00A79D] hover:bg-[#008f87] text-white px-5 py-[9px] rounded-[10px] text-sm font-medium flex items-center justify-center gap-2 transition-all shadow-sm shadow-teal-100">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Buat Baru
                </button>
                <div id="dropdownBuatPertemuan" class="hidden absolute right-0 mt-2 w-full xl:w-48 bg-white rounded-lg shadow-xl border border-slate-100 z-50 overflow-hidden">
                    <a href="{{ route('teacher.meetings.create', ['kelas_id' => $kelas->id, 'type' => 'tugas']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#00A79D] flex items-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> Buat Tugas</a>
                    <a href="{{ route('teacher.meetings.create', ['kelas_id' => $kelas->id, 'type' => 'materi']) }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#00A79D] flex items-center gap-2 border-t border-slate-50"><svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18s-3.332.477-4.5 1.253"/></svg> Buat Materi</a>
                </div>
            </div>
        </div>

        <div id="pertemuanResultContainer" class="space-y-8">
            @forelse($tabMeetings->groupBy(fn($item) => empty($item->topic) ? 'Tanpa Topik' : $item->topic) as $topic => $meetings)
                <div>
                    <h2 class="text-sm font-bold text-[#00A79D] uppercase tracking-wider mb-4 pl-2 border-b-2 border-teal-100 pb-2 flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg> {{ $topic }}
                    </h2>
                    <div class="space-y-3">
                        @foreach ($meetings as $meeting)
                            <div class="bg-white rounded-[10px] shadow-sm border border-slate-100 hover:border-[#00A79D]/30 transition-all duration-300 group">
                                <div class="p-4 flex justify-between items-start cursor-pointer rounded-[10px]" onclick="toggleAccordion('accordion-{{ $meeting->id }}')">
                                    <div class="flex gap-4 w-full">
                                        <div class="w-10 h-10 rounded-[8px] flex items-center justify-center flex-shrink-0 {{ $meeting->type == 'tugas' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                                            @if ($meeting->type == 'tugas')
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect><path d="M9 14l2 2 4-4"></path></svg>
                                            @else
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded border uppercase tracking-wider {{ $meeting->type == 'tugas' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">{{ ucfirst($meeting->type) }}</span>
                                                <span class="text-[11px] font-medium text-slate-400">{{ $meeting->created_at->format('d M Y') }}</span>
                                            </div>
                                            <h3 class="font-bold text-[#092C4C] text-base leading-tight">{{ $meeting->title }}</h3>
                                            @if ($meeting->type == 'tugas')
                                                @if ($meeting->deadline)
                                                    <p class="text-xs text-red-500 mt-1.5 font-medium flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 16 14"></polyline></svg> Deadline: {{ \Carbon\Carbon::parse($meeting->deadline)->format('d M Y, H:i') }}</p>
                                                @else
                                                    <p class="text-xs text-slate-400 mt-1.5 flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg> Tidak ada batasan waktu</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 pl-2">
                                        <div class="relative z-10" onclick="event.stopPropagation()">
                                            <button onclick="toggleDropdown('menu-{{ $meeting->id }}')" class="p-1.5 text-slate-400 hover:text-[#092C4C] hover:bg-slate-50 rounded-md transition-colors"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg></button>
                                            <div id="menu-{{ $meeting->id }}" class="hidden absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-slate-100 z-50 overflow-hidden">
                                                <a href="{{ route('teacher.meetings.edit', $meeting->id) }}" class="block px-4 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-50 hover:text-[#00A79D]">Edit</a>
                                                <form action="{{ route('teacher.meetings.destroy', $meeting->id) }}" method="POST" class="delete-form">@csrf @method('DELETE')<button type="button" class="btn-delete block w-full text-left px-4 py-2.5 text-xs font-medium text-red-500 hover:bg-red-50 transition-colors border-t border-slate-50">Hapus</button></form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="accordion-{{ $meeting->id }}" class="accordion-content max-h-0 opacity-0 overflow-hidden transition-all duration-300 ease-in-out bg-slate-50/50 border-t border-transparent rounded-b-[10px]">
                                    <div class="p-5 md:p-6 text-sm text-slate-600 border-t border-slate-100">
                                        @if ($meeting->type == 'tugas')
                                            <div class="flex items-center gap-4 mb-4 text-xs font-medium">
                                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded">Max Nilai: {{ $meeting->max_score ?? 'Tidak dinilai' }}</span>
                                                @if ($meeting->disable_late_submission)<span class="bg-red-50 text-red-600 px-3 py-1 rounded flex items-center gap-1"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8"></path><path d="M21 3v5h-5"></path></svg> Kunci Otomatis</span>@endif
                                            </div>
                                        @endif
                                        @if ($meeting->description) <div class="prose prose-sm max-w-none mb-4 line-clamp-3">{!! $meeting->description !!}</div> @else <p class="mb-4 italic text-slate-400">Tidak ada instruksi tambahan.</p> @endif
                                        @php
                                            $rawFiles = $meeting->files;
                                            if (is_string($rawFiles)) { $rawFiles = json_decode($rawFiles, true); if (is_string($rawFiles)) { $rawFiles = json_decode($rawFiles, true); } }
                                            $files = is_array($rawFiles) ? $rawFiles : [];
                                        @endphp
                                        @if (count($files) > 0)
                                            <div class="mb-5"><p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">File Terlampir</p><div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                                @foreach ($files as $file)
                                                    @if (is_array($file) && isset($file['path']))
                                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group/file">
                                                            <div class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg></div>
                                                            <div class="flex-1 min-w-0"><p class="text-xs font-semibold text-slate-700 truncate group-hover/file:text-[#00A79D]">{{ $file['original_name'] ?? 'File Lampiran' }}</p><p class="text-[10px] text-slate-400 mt-0.5">{{ isset($file['size']) ? number_format($file['size'] / 1024, 1) . ' KB' : 'Unknown Size' }}</p></div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div></div>
                                        @endif
                                        <div class="mt-4 pt-4 border-t border-slate-200"><a href="{{ route('teacher.meetings.show', $meeting->id) }}" class="inline-flex items-center text-sm font-semibold text-[#00A79D] hover:text-[#008f87] hover:underline transition-colors">Lihat Selengkapnya<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg></a></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-slate-50 rounded-[10px] border border-dashed border-slate-200"><p class="text-slate-400 text-sm font-medium">Tidak ada data ditemukan.</p></div>
            @endforelse
        </div>
    </div>

    {{-- Tab 3: Anggota Kelas --}}
    <div id="tab-content-anggota" class="tab-content hidden animate-fade-in">
        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-6">
            <div class="flex justify-between items-center mt-1 mb-6 border-b border-slate-100 pb-4">
                <div><h3 class="text-[18px] font-bold text-[#092C4C]">Daftar Anggota Kelas</h3></div>
                <form id="searchAnggotaForm" class="w-full md:w-auto">
                    <div class="w-full md:w-auto"><x-ui.search-bar placeholder="Cari nama atau email siswa..." target="anggotaResultContainer" formId="searchAnggotaForm" /></div>
                    <input type="hidden" name="tab" value="anggota">
                </form>
            </div>
            <div id="anggotaResultContainer">
                <div class="overflow-hidden rounded-[15px] border border-slate-200">
                    <table class="w-full text-left">
                        <thead class="bg-[#0F4C5C] text-white text-[14px]">
                            <tr><th class="px-6 py-4 font-medium pl-10">Nama Anggota</th><th class="px-6 py-4 font-medium">Email</th><th class="px-6 py-4 font-medium">Peran</th><th class="px-6 py-4 font-medium text-center">Aksi</th></tr>
                        </thead>
                        <tbody class="text-[14px] text-[#092C4C]">
                            @if (!request('page') || request('page') == 1)
                                <tr class="border-b border-slate-200 bg-blue-50/30 hover:bg-slate-50 transition">
                                    <td class="pl-10 pr-6 py-4"><div class="flex items-center gap-4">
                                        <img src="{{ $kelas->teacher->profile_photo ? asset('storage/' . $kelas->teacher->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($kelas->teacher->name) . '&background=044153&color=ffffff' }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                        <div><div class="font-bold text-[#092C4C] flex items-center gap-2">{{ $kelas->teacher->name }} <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></div><div class="text-xs text-slate-400">ID: {{ $kelas->teacher->id }}</div></div></div></td>
                                    <td class="px-6 py-4 text-slate-600">{{ $kelas->teacher->email }}</td><td class="px-6 py-4"><x-ui.badge label="Pengajar" color="primary" /></td><td class="px-6 py-4 text-center"><span class="text-xs text-slate-400 italic">Pengajar Kelas</span></td>
                                </tr>
                            @endif
                            @forelse($students as $student)
                                <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                    <td class="pl-10 pr-6 py-4"><div class="flex items-center gap-4">
                                        <img src="{{ $student->profile_photo ? asset('storage/' . $student->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=044153&color=ffffff' }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                        <div><div class="font-medium text-[#092C4C]">{{ $student->name }}</div><div class="text-xs text-slate-400">ID: {{ $student->id }}</div></div></div></td>
                                    <td class="px-6 py-4 text-slate-600">{{ $student->email }}</td><td class="px-6 py-4"><x-ui.badge label="Siswa" color="success" /></td>
                                    <td class="px-6 py-4"><div class="flex justify-center"><form action="{{ route('teacher.kelas.removeStudent', ['kelas' => $kelas->id, 'student' => $student->id]) }}" method="POST" class="remove-student-form">@csrf @method('DELETE')<button type="button" class="btn-remove-student text-red-500 hover:text-white font-medium text-xs bg-red-50 hover:bg-red-500 px-4 py-2 rounded-lg transition-colors flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg> Keluarkan</button></form></div></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-10 text-slate-400"><div class="flex flex-col items-center gap-2"><svg class="w-10 h-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg><span class="font-medium mt-2">Belum ada siswa yang bergabung</span></div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between items-center mt-6 text-[13px] text-slate-500">
                    <div>Menampilkan {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} data</div>
                    <div>{{ $students->appends(['tab' => 'anggota', 'search' => request('search')])->onEachSide(1)->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Buat Pengumuman --}}
    <div id="modalPengumuman" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="toggleModal('modalPengumuman')"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[15px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="bg-white px-6 pb-4 pt-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#092C4C]">Buat Pengumuman</h3>
                    <button type="button" onclick="toggleModal('modalPengumuman')" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <form action="{{ route('teacher.announcements.store') }}" method="POST" enctype="multipart/form-data" id="announcementForm">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $kelas->id }}">
                    <div class="px-6 py-5 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#092C4C] mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                            <div class="border border-slate-300 rounded-[8px] overflow-hidden focus-within:border-[#00A79D] transition-all"><div id="announcement-editor-container" class="bg-white min-h-[120px] text-sm"></div></div>
                            <input type="hidden" name="content" id="announcement-content">
                            @error('content')<span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-[#092C4C]">Tautan Keluar (Opsional)</label>
                                <button type="button" onclick="addModalLinkField()" class="text-[#00A79D] text-xs font-semibold flex items-center gap-1 hover:bg-teal-50 px-2 py-1 rounded-md transition-colors"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Tambah Link</button>
                            </div>
                            <div id="modal-link-container" class="space-y-3"><div class="flex gap-2"><input type="url" name="links[]" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3" placeholder="https://..."></div></div>
                        </div>
                        <div class="pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-semibold text-[#092C4C]">File / Gambar (Opsional)</label>
                                <button type="button" onclick="addModalFileField()" class="text-[#00A79D] text-xs font-semibold flex items-center gap-1 hover:bg-teal-50 px-2 py-1 rounded-md transition-colors"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Tambah File</button>
                            </div>
                            <div id="modal-file-container" class="space-y-3"><div class="flex gap-2 items-center"><input type="file" name="files[]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 transition-all cursor-pointer border border-slate-300 rounded-[8px] p-1.5"></div></div>
                            <p class="text-[11px] text-slate-400 mt-2">Format: PDF, DOCX, JPG, PNG (Max: 5MB)</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-[15px] border-t border-slate-100">
                        <button type="submit" class="bg-[#00A79D] hover:bg-[#008f87] text-white px-5 py-2 rounded-[8px] text-sm font-medium transition-all shadow-md shadow-teal-100">Posting Pengumuman</button>
                        <button type="button" onclick="toggleModal('modalPengumuman')" class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 px-5 py-2 rounded-[8px] text-sm font-medium transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Pengumuman --}}
    <div id="modalEditPengumuman" class="hidden fixed inset-0 z-[60] overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm" onclick="toggleModal('modalEditPengumuman')"></div>
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-[15px] bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                <div class="bg-white px-6 pb-4 pt-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-[#092C4C]">Edit Pengumuman</h3>
                    <button type="button" onclick="toggleModal('modalEditPengumuman')" class="text-gray-400 hover:text-red-500 transition-colors p-1 rounded-md hover:bg-red-50"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12" /></svg></button>
                </div>
                <form id="editAnnouncementForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-6 py-5 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#092C4C] mb-2">Isi Pengumuman <span class="text-red-500">*</span></label>
                            <div class="border border-slate-300 rounded-[8px] overflow-hidden focus-within:border-[#00A79D] transition-all"><div id="edit-announcement-editor-container" class="bg-white min-h-[120px] text-sm"></div></div>
                            <input type="hidden" name="content" id="edit-announcement-content">
                        </div>
                        <div class="bg-orange-50 text-orange-600 p-3 rounded-lg text-xs flex gap-2 items-start"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><p>Fitur edit saat ini hanya untuk mengubah <strong>Teks Pengumuman</strong>. Jika ingin mengubah file atau link lampiran, silakan hapus pengumuman ini dan buat yang baru.</p></div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-[15px] border-t border-slate-100">
                        <button type="submit" class="bg-[#00A79D] hover:bg-[#008f87] text-white px-5 py-2 rounded-[8px] text-sm font-medium transition-all shadow-md shadow-teal-100">Simpan Perubahan</button>
                        <button type="button" onclick="toggleModal('modalEditPengumuman')" class="bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 px-5 py-2 rounded-[8px] text-sm font-medium transition-all">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        const tabStorageKey = 'activeTab_class_{{ $kelas->id }}';
        let announcementQuill = null;
        let editAnnouncementQuill = null;

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

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) {
                modal.classList.toggle('hidden');
                if (modalID === 'modalPengumuman' && !modal.classList.contains('hidden') && !announcementQuill) {
                    announcementQuill = new Quill('#announcement-editor-container', {
                        theme: 'snow', placeholder: 'Ketik pengumuman di sini...',
                        modules: { toolbar: [['bold', 'italic', 'underline'], [{'list':'ordered'}, {'list':'bullet'}], ['clean']] }
                    });
                    document.getElementById('announcementForm').addEventListener('submit', () => {
                        const html = announcementQuill.root.innerHTML;
                        document.getElementById('announcement-content').value = html === '<p><br></p>' ? '' : html;
                    });
                }
            }
        }

        function openEditAnnouncement(id) {
            const form = document.getElementById('editAnnouncementForm');
            form.action = `{{ url('teacher/announcements') }}/${id}`;
            toggleModal('modalEditPengumuman');
            if (!editAnnouncementQuill) {
                editAnnouncementQuill = new Quill('#edit-announcement-editor-container', {
                    theme: 'snow',
                    modules: { toolbar: [['bold', 'italic', 'underline'], [{'list':'ordered'}, {'list':'bullet'}], ['clean']] }
                });
                form.addEventListener('submit', () => {
                    const html = editAnnouncementQuill.root.innerHTML;
                    document.getElementById('edit-announcement-content').value = html === '<p><br></p>' ? '' : html;
                });
            }
            editAnnouncementQuill.clipboard.dangerouslyPasteHTML(document.getElementById(`raw-content-${id}`).value);
        }

        function toggleDropdown(dropdownID) {
            document.querySelectorAll('[id^="dropdown"], [id^="menu-"]').forEach(el => {
                if (el.id !== dropdownID) el.classList.add('hidden');
            });
            const dropdown = document.getElementById(dropdownID);
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        function toggleAccordion(contentId) {
            const targetContent = document.getElementById(contentId);
            if (!targetContent) return;
            document.querySelectorAll('.accordion-content').forEach(content => {
                if (content.id !== contentId) {
                    content.classList.add('max-h-0', 'opacity-0');
                    content.classList.remove('max-h-[3000px]', 'opacity-100');
                }
            });
            const isCollapsed = targetContent.classList.contains('max-h-0');
            if (isCollapsed) {
                targetContent.classList.remove('max-h-0', 'opacity-0');
                targetContent.classList.add('max-h-[3000px]', 'opacity-100');
            } else {
                targetContent.classList.add('max-h-0', 'opacity-0');
                targetContent.classList.remove('max-h-[3000px]', 'opacity-100');
            }
        }

        function addModalLinkField() {
            const container = document.getElementById('modal-link-container');
            const wrapper = document.createElement('div');
            wrapper.className = 'flex gap-2 animate-fade-in';
            wrapper.innerHTML = `<input type="url" name="links[]" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3" placeholder="https://..."><button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>`;
            container.appendChild(wrapper);
        }

        function addModalFileField() {
            const container = document.getElementById('modal-file-container');
            const wrapper = document.createElement('div');
            wrapper.className = 'flex gap-2 items-center animate-fade-in';
            wrapper.innerHTML = `<input type="file" name="files[]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 transition-all cursor-pointer border border-slate-300 rounded-[8px] p-1.5"><button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>`;
            container.appendChild(wrapper);
        }

        function confirmAction(e, title, text) {
            e.preventDefault();
            Swal.fire({ title, text, icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Lanjutkan!', cancelButtonText: 'Batal' })
            .then((result) => { if (result.isConfirmed) e.target.closest('form').submit(); });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tabFromUrl = urlParams.get('tab');
            switchTab(tabFromUrl || localStorage.getItem(tabStorageKey) || 'beranda');

            if (tabFromUrl) window.history.replaceState({}, document.title, window.location.href.split('?')[0]);

            window.addEventListener('click', (e) => {
                if (!e.target.closest('button') && !e.target.closest('.relative')) {
                    document.querySelectorAll('[id^="dropdown"], [id^="menu-"]').forEach(el => el.classList.add('hidden'));
                }
            });

            @if (session('success'))
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); } }).fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif

            @if (session('error'))
                Swal.fire({ icon: 'error', title: 'Akses Ditolak', text: '{{ session('error') }}', confirmButtonColor: '#0F4C5C' });
            @endif

            @if ($errors->any())
                switchTab('beranda');
                toggleModal('modalPengumuman');
                Swal.fire({ icon: 'error', title: 'Gagal Memposting', text: 'Silakan periksa kembali isian formulir.', confirmButtonColor: '#ef4444' });
            @endif

            document.querySelectorAll('.btn-delete').forEach(btn => btn.addEventListener('click', (e) => confirmAction(e, 'Hapus Data?', 'Data yang dihapus tidak dapat dikembalikan!')));
            document.querySelectorAll('.btn-remove-student').forEach(btn => btn.addEventListener('click', (e) => confirmAction(e, 'Keluarkan Siswa?', 'Siswa ini tidak akan bisa mengakses materi dan tugas di kelas ini lagi.')));
        });
    </script>
@endsection