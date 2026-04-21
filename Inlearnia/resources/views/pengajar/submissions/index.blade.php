@extends('layouts.app')
@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-10">
        <x-breadcrumb :items="[
            ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
            ['label' => $classRoom->name, 'url' => route('teacher.kelas.show', ['kelas' => $classRoom->id, 'tab' => 'pertemuan'])],
            ['label' => $meeting->title, 'url' => route('teacher.meetings.show', $meeting)],
            ['label' => 'Jawaban Siswa', 'url' => null],
        ]" />
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['label' => 'Dinilai',      'value' => $stats['graded'],    'text' => 'text-[#00A79D]'],
            ['label' => 'Terkumpul',    'value' => $stats['submitted'], 'text' => 'text-blue-600'],
            ['label' => 'Terlambat',    'value' => $stats['late'],      'text' => 'text-orange-500'],
            ['label' => 'Belum Kumpul', 'value' => $stats['missing'],   'text' => 'text-red-500'],
        ] as $stat)
        <div class="bg-white rounded-[15px] p-5 shadow-sm border border-slate-200 text-center">
            <span class="block text-2xl font-bold {{ $stat['text'] }}">{{ $stat['value'] }}</span>
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">{{ $stat['label'] }}</span>
        </div>
        @endforeach
    </div>

    {{-- Filter + Search --}}
    <div class="flex items-center gap-3 mb-4 flex-wrap">
        <div class="flex gap-1 bg-slate-100 p-1 rounded-[10px]">
            @foreach (['all' => 'Semua ('.$stats['total'].')', 'graded' => 'Dinilai', 'submitted' => 'Terkumpul', 'late' => 'Terlambat', 'missing' => 'Belum'] as $key => $label)
            <button
                onclick="filterRows('{{ $key }}')"
                id="tab-{{ $key }}"
                class="tab-btn px-3 py-1.5 text-xs font-medium rounded-[8px] transition-colors text-slate-500 hover:text-slate-700"
            >{{ $label }}</button>
            @endforeach
        </div>
        <input
            id="search-input"
            type="text"
            placeholder="Cari nama siswa..."
            oninput="searchRows(this.value)"
            class="ml-auto px-3 py-1.5 text-sm border border-slate-200 rounded-[10px] focus:outline-none focus:border-[#00A79D] w-48"
        />
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[15px] shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm" id="submissions-table">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50">
                    <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-5 py-3 w-8">#</th>
                    <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 py-3">Siswa</th>
                    <th class="text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 py-3 hidden md:table-cell">Waktu Kumpul</th>
                    <th class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 py-3">Nilai</th>
                    <th class="text-center text-[10px] font-bold text-slate-400 uppercase tracking-wider px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $i => $row)
                @php
                    $student    = $row['student'];
                    $submission = $row['submission'];
                    $status     = $row['status'];
                    $initials   = collect(explode(' ', $student->name))
                                    ->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                    $avatarClass = match($status) {
                        'graded'    => 'bg-teal-100 text-teal-700',
                        'submitted' => 'bg-blue-100 text-blue-700',
                        'late'      => 'bg-orange-100 text-orange-700',
                        default     => 'bg-slate-100 text-slate-500',
                    };
                    $badgeClass = match($status) {
                        'graded'    => 'bg-teal-50 text-teal-700 border-teal-100',
                        'submitted' => 'bg-blue-50 text-blue-700 border-blue-100',
                        'late'      => 'bg-orange-50 text-orange-700 border-orange-100',
                        default     => 'bg-red-50 text-red-600 border-red-100',
                    };
                    $badgeLabel = match($status) {
                        'graded'    => 'Dinilai',
                        'submitted' => 'Terkumpul',
                        'late'      => 'Terlambat',
                        default     => 'Belum Kumpul',
                    };
                @endphp
                <tr
                    class="border-b border-slate-50 transition-colors submission-row {{ $submission ? 'hover:bg-slate-50 cursor-pointer' : '' }}"
                    data-status="{{ $status }}"
                    data-name="{{ strtolower($student->name) }}"
                    @if ($submission)
                        onclick="window.location='{{ route('teacher.meetings.submissions.show', [$meeting, $submission->id]) }}'"
                    @endif
                >
                    <td class="px-5 py-3.5 text-slate-400 text-xs">{{ $i + 1 }}</td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="font-semibold text-[#092C4C]">{{ $student->name }}</p>
                                <p class="text-xs text-slate-400">{{ $student->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 hidden md:table-cell text-xs">
                        @if ($submission?->submitted_at)
                            <span class="{{ $status === 'late' ? 'text-orange-500 font-medium' : 'text-slate-500' }}">
                                {{ $submission->submitted_at->format('d M Y · H:i') }}
                                @if ($status === 'late')
                                    <span class="text-[10px]">(terlambat)</span>
                                @endif
                            </span>
                        @else
                            <span class="text-slate-300 italic">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if ($submission?->score !== null)
                            <span class="text-sm font-bold text-[#092C4C]">{{ $submission->score }}</span>
                            @if ($meeting->max_score)
                                <span class="text-xs text-slate-400">/ {{ $meeting->max_score }}</span>
                            @endif
                        @else
                            <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded border uppercase tracking-wider {{ $badgeClass }}">
                            {{ $badgeLabel }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-right">
                        @if ($submission)
                            <span class="text-xs text-[#00A79D] font-medium">Buka →</span>
                        @else
                            <span class="text-xs text-slate-300">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if ($rows->isEmpty())
        <div class="text-center py-12">
            <p class="text-slate-400 text-sm italic">Belum ada siswa di kelas ini.</p>
        </div>
        @endif
    </div>

    <script>
        // Set tab aktif saat load
        setActiveTab('all');

        function filterRows(status) {
            setActiveTab(status);
            document.querySelectorAll('.submission-row').forEach(row => {
                row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
            });
        }

        function searchRows(q) {
            document.querySelectorAll('.submission-row').forEach(row => {
                row.style.display = row.dataset.name.includes(q.toLowerCase()) ? '' : 'none';
            });
        }

        function setActiveTab(active) {
            document.querySelectorAll('.tab-btn').forEach(btn => {
                const isActive = btn.id === 'tab-' + active;
                btn.classList.toggle('bg-white', isActive);
                btn.classList.toggle('text-[#092C4C]', isActive);
                btn.classList.toggle('shadow-sm', isActive);
                btn.classList.toggle('text-slate-500', !isActive);
            });
        }
    </script>
@endsection