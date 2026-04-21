@extends('layouts.app')
@section('content')
    <x-sidebar />

    {{-- Breadcrumb + Navigasi Prev/Next --}}
    <div class="flex justify-between items-center mb-8">
        <x-breadcrumb :items="[
            ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
            [
                'label' => $classRoom->name,
                'url' => route('teacher.kelas.show', ['kelas' => $classRoom->id, 'tab' => 'pertemuan']),
            ],
            ['label' => $meeting->title, 'url' => route('teacher.meetings.show', $meeting)],
            ['label' => 'Jawaban Siswa', 'url' => route('teacher.meetings.submissions.index', $meeting)],
            ['label' => $submission->student->name, 'url' => null],
        ]" />
        <div class="flex items-center gap-2 flex-shrink-0">
            @if ($prevId)
                <a href="{{ route('teacher.meetings.submissions.show', [$meeting, $prevId]) }}"
                    class="px-3 py-1.5 text-xs font-medium border border-slate-200 rounded-[8px] hover:bg-slate-50 transition-colors text-slate-600">
                    ← Sebelumnya
                </a>
            @endif
            <span class="text-xs text-slate-400 px-1">{{ $currentIndex + 1 }} / {{ $submittedIds->count() }}</span>
            @if ($nextId)
                <a href="{{ route('teacher.meetings.submissions.show', [$meeting, $nextId]) }}"
                    class="px-3 py-1.5 text-xs font-medium border border-slate-200 rounded-[8px] hover:bg-slate-50 transition-colors text-slate-600">
                    Berikutnya →
                </a>
            @endif
        </div>
    </div>

    {{-- Flash Success --}}
    @if (session('success'))
        <div
            class="mb-5 bg-teal-50 border border-teal-200 text-teal-700 text-sm px-4 py-2.5 rounded-[10px] flex items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header Siswa --}}
    @php
        $status = $submission->status;
        $initials = collect(explode(' ', $submission->student->name))
            ->take(2)
            ->map(fn($w) => strtoupper($w[0]))
            ->join('');
        $avatarClass = match ($status) {
            'graded' => 'bg-teal-100 text-teal-700',
            'submitted' => 'bg-blue-100 text-blue-700',
            'late' => 'bg-orange-100 text-orange-700',
            default => 'bg-slate-100 text-slate-500',
        };
        $badgeClass = match ($status) {
            'graded' => 'bg-teal-50 text-teal-700 border-teal-100',
            'submitted' => 'bg-blue-50 text-blue-700 border-blue-100',
            'late' => 'bg-orange-50 text-orange-700 border-orange-100',
            default => 'bg-red-50 text-red-600 border-red-100',
        };
        $badgeLabel = match ($status) {
            'graded' => 'Dinilai',
            'submitted' => 'Terkumpul',
            'late' => 'Terlambat',
            default => 'Belum Kumpul',
        };
    @endphp

    <div class="bg-white rounded-[15px] p-5 shadow-sm border border-slate-200 mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 flex-shrink-0">
    <img
        src="{{ $submission->student->profile_photo 
            ? asset('storage/' . $submission->student->profile_photo) 
            : 'https://ui-avatars.com/api/?name=' . urlencode($submission->student->name) . '&background=044153&color=ffffff' }}"
        class="w-full h-full object-cover"
        alt="{{ $submission->student->name }}"
    >
</div>
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                <h1 class="text-lg font-bold text-[#092C4C]">{{ $submission->student->name }}</h1>
                <span
                    class="text-[10px] font-bold px-2.5 py-0.5 rounded border uppercase tracking-wider {{ $badgeClass }}">
                    {{ $badgeLabel }}
                </span>
            </div>
            <p class="text-xs text-slate-400">
                {{ $submission->student->email }} ·
                Dikumpulkan: <span class="{{ $status === 'late' ? 'text-orange-500 font-medium' : '' }}">
                    {{ $submission->submitted_at?->format('d M Y, H:i') ?? 'Belum mengumpulkan' }}
                </span>
            </p>
        </div>
        @if ($meeting->deadline)
            <div class="text-right hidden md:block flex-shrink-0">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mb-0.5">Tenggat</p>
                <p class="text-sm font-bold {{ $meeting->deadline->isPast() ? 'text-red-500' : 'text-[#092C4C]' }}">
                    {{ $meeting->deadline->format('d M Y, H:i') }}
                </p>
            </div>
        @endif
    </div>

    {{-- Grid Konten + Penilaian --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KIRI: Jawaban Siswa --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Teks Jawaban --}}
            @if ($submission->content_text)
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="text-base font-bold text-[#092C4C] mb-4 pb-4 border-b border-slate-100">Jawaban Teks</h3>

                    <div class="prose max-w-none text-slate-600 text-sm leading-relaxed">
                        {!! $submission->content_text !!}
                    </div>
                </div>
            @endif

            {{-- Link dari siswa --}}
            @php $subLinks = is_array($submission->links) ? $submission->links : []; @endphp
            @if (count($subLinks) > 0)
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="text-base font-bold text-[#092C4C] mb-4 pb-4 border-b border-slate-100">Tautan Terlampir</h3>
                    <div class="flex flex-col gap-2">
                        @foreach ($subLinks as $link)
                            <a href="{{ $link }}" target="_blank"
                                class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:bg-teal-50 transition-colors group">
                                <div
                                    class="w-8 h-8 rounded bg-white border border-slate-200 text-[#00A79D] flex items-center justify-center flex-shrink-0">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71" />
                                        <path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" />
                                    </svg>
                                </div>
                                <span
                                    class="text-sm text-slate-600 truncate group-hover:text-[#00A79D]">{{ $link }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- File dari siswa --}}
            @php $subFiles = is_array($submission->files) ? $submission->files : []; @endphp
            @if (count($subFiles) > 0)
                <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                    <h3 class="text-base font-bold text-[#092C4C] mb-4 pb-4 border-b border-slate-100">
                        File Lampiran
                        <span class="text-slate-400 font-normal text-sm">({{ count($subFiles) }})</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($subFiles as $file)
                            @if (isset($file['path']))
                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                    class="flex items-center gap-3 bg-white p-3 rounded-lg border border-slate-200 hover:border-[#00A79D] hover:shadow-sm transition-all group">
                                    <div
                                        class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-700 truncate group-hover:text-[#00A79D]">
                                            {{ $file['original_name'] ?? 'File Lampiran' }}
                                        </p>
                                        <p class="text-xs text-slate-400">
                                            {{ isset($file['size']) ? number_format($file['size'] / 1024, 1) . ' KB' : '' }}
                                        </p>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Kosong --}}
            @if (!$submission->content_text && count($subLinks) === 0 && count($subFiles) === 0)
                <div class="bg-white rounded-[15px] p-10 shadow-sm border border-slate-200 text-center">
                    <p class="text-slate-400 italic text-sm">Siswa belum mengumpulkan jawaban apapun.</p>
                </div>
            @endif
        </div>

        {{-- KANAN: Form Penilaian + Quick Nav --}}
        <div class="space-y-5">

            {{-- Form Penilaian --}}
            <div class="bg-white rounded-[15px] p-6 shadow-sm border border-slate-200">
                <h3 class="text-base font-bold text-[#092C4C] mb-5 pb-4 border-b border-slate-100">Penilaian</h3>

                <form action="{{ route('teacher.meetings.submissions.grade', [$meeting, $submission]) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    {{-- Input Nilai --}}
                    <div class="mb-5">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Nilai
                            @if ($meeting->max_score)
                                <span class="font-normal normal-case">(maks. {{ $meeting->max_score }})</span>
                            @endif
                        </label>
                        <input type="number" name="score" value="{{ old('score', $submission->score) }}"
                            placeholder="0 – {{ $meeting->max_score ?? 100 }}" min="0"
                            max="{{ $meeting->max_score ?? 100 }}"
                            class="w-full text-center text-3xl font-bold text-[#092C4C] border border-slate-200 rounded-[10px] py-4 focus:outline-none focus:border-[#00A79D] transition-colors" />
                        @error('score')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Feedback --}}
                    <div class="mb-5">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
                            Catatan / Feedback
                        </label>
                        <textarea name="feedback" rows="5" placeholder="Berikan catatan atau masukan untuk siswa..."
                            class="w-full text-sm text-slate-600 border border-slate-200 rounded-[10px] p-3 focus:outline-none focus:border-[#00A79D] transition-colors resize-none leading-relaxed">{{ old('feedback', $submission->feedback) }}</textarea>
                    </div>

                    {{-- Tombol --}}
                    <div class="space-y-2">
                        <button type="submit"
                            class="w-full bg-[#092C4C] hover:bg-[#061b30] text-white py-2.5 rounded-[8px] text-sm font-medium transition-colors">
                            Simpan Penilaian
                        </button>
                        @if ($nextId)
                            <button type="submit" name="next_submission_id" value="{{ $nextId }}"
                                class="w-full border border-slate-200 hover:bg-slate-50 text-slate-600 py-2.5 rounded-[8px] text-sm font-medium transition-colors">
                                Simpan & Lanjut Berikutnya →
                            </button>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Quick Nav Grid --}}
            <div class="bg-white rounded-[15px] p-5 shadow-sm border border-slate-200">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">Navigasi Cepat</p>
                <div class="grid grid-cols-5 gap-1.5 mb-3">
                    @foreach ($submittedIds as $idx => $subId)
                        @php
                            $s = \App\Models\Submission::find($subId);
                            $chipClass = match ($s?->status) {
                                'graded' => 'bg-teal-100 text-teal-700 hover:bg-teal-200',
                                'late' => 'bg-orange-100 text-orange-700 hover:bg-orange-200',
                                default => 'bg-blue-100 text-blue-700 hover:bg-blue-200',
                            };
                            $isActive = $subId == $submission->id;
                        @endphp
                        <a href="{{ route('teacher.meetings.submissions.show', [$meeting, $subId]) }}"
                            class="aspect-square rounded-[6px] flex items-center justify-center text-xs font-medium transition-colors
                              {{ $isActive ? 'bg-[#092C4C] text-white' : $chipClass }}">
                            {{ $idx + 1 }}
                        </a>
                    @endforeach
                </div>
                
            </div>

        </div>
    </div>
@endsection
