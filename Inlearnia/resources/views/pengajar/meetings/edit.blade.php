@extends('layouts.app')

@section('content')
    <x-sidebar />

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .ql-toolbar.ql-snow {
            border: 1px solid #cbd5e1 !important;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            background-color: #f8fafc;
            font-family: inherit;
        }

        .ql-container.ql-snow {
            border: 1px solid #cbd5e1 !important;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            border-top: none !important;
            font-family: inherit;
            font-size: 0.875rem;
        }

        .ql-editor {
            min-height: 200px;
        }

        .ql-editor.ql-blank::before {
            color: #94a3b8;
            font-style: normal;
        }

        .ql-editor:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 167, 157, 0.2);
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>

    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
                ['label' => $kelas->name, 'url' => route('teacher.kelas.show', $kelas->id)],
                ['label' => 'Edit ' . ucfirst($type), 'url' => null],
            ]" />
        </div>
        <x-calendar />
    </div>

    <div class="max-w-6xl bg-white rounded-[15px] shadow-sm border border-slate-200 p-8 mb-10">

        <div class="mb-8 border-b border-slate-200 pb-5 flex justify-between items-end">
            <div>
                <h2 class="text-xl font-bold text-[#092C4C]">Edit {{ ucfirst($type) }}</h2>
                <p class="text-sm text-slate-500 mt-1.5">
                    Perbarui {{ $type }} untuk kelas
                    <span class="font-semibold text-slate-700">{{ $kelas->name }}</span>
                </p>
            </div>
            <span
                class="text-xs font-bold px-2.5 py-1 rounded border uppercase tracking-wider {{ $type == 'tugas' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                {{ ucfirst($type) }}
            </span>
        </div>

        {{-- Gunakan method PUT untuk update --}}
        <form action="{{ route('teacher.meetings.update', $meeting->id) }}" method="POST" enctype="multipart/form-data"
            id="meetingForm" class="space-y-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="class_id" value="{{ $kelas->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

                <div class="md:col-span-2 space-y-7">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-[#092C4C] mb-2">Judul Pertemuan <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3"
                            value="{{ old('title', $meeting->title) }}">
                        @error('title')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#092C4C] mb-2">Deskripsi / Instruksi
                            (Opsional)</label>
                        <input type="hidden" name="description" id="description">
                        {{-- Isi editor-container dengan data lama --}}
                        <div id="editor-container">{!! old('description', $meeting->description) !!}</div>
                    </div>

                    {{-- Section Link Lama & Tambah Baru --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-semibold text-[#092C4C]">Lampirkan Tautan (Opsional)</label>
                            <button type="button" onclick="addLinkField()"
                                class="text-[#00A79D] text-xs font-semibold flex items-center gap-1 hover:underline bg-teal-50 px-2 py-1.5 rounded-md transition-colors hover:bg-teal-100">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Link
                            </button>
                        </div>
                        <div id="link-container" class="space-y-3">
                            {{-- Looping Link Lama --}}
                            @if (is_array($meeting->links))
                                @foreach ($meeting->links as $link)
                                    <div class="flex gap-2">
                                        <input type="url" name="links[]" value="{{ $link }}"
                                            class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3"
                                            placeholder="https://...">
                                        <button type="button" onclick="this.parentElement.remove()"
                                            class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0"
                                            title="Hapus Link Ini">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Section File Lama & Tambah Baru --}}
                    <div class="pt-6 border-t border-slate-200">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-semibold text-[#092C4C]">File Terlampir</label>
                            <button type="button" onclick="addFileField()"
                                class="text-[#00A79D] text-xs font-semibold flex items-center gap-1 hover:underline bg-teal-50 px-2 py-1.5 rounded-md transition-colors hover:bg-teal-100">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah File Baru
                            </button>
                        </div>

                        {{-- Tampilkan File Lama (Hanya Visual, Butuh backend logic tambahan jika ingin menghapus spesifik file lama) --}}
                        @if (is_array($meeting->files) && count($meeting->files) > 0)
                            <div class="mb-4 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <p class="text-xs text-slate-500 mb-2 font-medium">File yang sudah diupload (menambahkan
                                    file baru tidak akan menghapus file lama ini):</p>
                                <ul class="space-y-2">
                                    @foreach ($meeting->files as $file)
                                        <li class="flex items-center gap-2 text-sm text-slate-600">
                                            <svg class="text-[#00A79D]" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg>
                                            {{ $file['original_name'] }} <span
                                                class="text-xs text-slate-400">({{ number_format($file['size'] / 1024, 1) }}
                                                KB)</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div id="file-container" class="space-y-3">
                            {{-- Kosong di awal, akan ditambah via JS jika pengajar mau upload file tambahan --}}
                        </div>
                    </div>
                </div>

                <div class="space-y-6 bg-slate-50 p-6 rounded-[12px] border border-slate-200 h-fit">
                    <div>
                        <label for="topic" class="block text-sm font-semibold text-[#092C4C] mb-2">Topik
                            (Opsional)</label>
                        <input type="text" name="topic" id="topic"
                            class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3"
                            value="{{ old('topic', $meeting->topic) }}" placeholder="Contoh: Bab 1">
                    </div>

                    @if ($type === 'tugas')
                        <div class="pt-5 border-t border-slate-200">
                            <label for="deadline" class="block text-sm font-semibold text-[#092C4C] mb-2">Batas Waktu
                                (Opsional)</label>
                            <input type="datetime-local" name="deadline" id="deadline"
                                class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3"
                                value="{{ old('deadline', $meeting->deadline ? $meeting->deadline->format('Y-m-d\TH:i') : '') }}">

                            <div class="mt-3 flex items-start gap-2.5">
                                <input type="checkbox" name="disable_late_submission" id="disable_late_submission"
                                    value="1"
                                    {{ old('disable_late_submission', $meeting->disable_late_submission) ? 'checked' : '' }}
                                    class="mt-0.5 rounded text-[#00A79D] focus:ring-[#00A79D]/20 border-slate-300">
                                <label for="disable_late_submission"
                                    class="text-[11px] text-slate-600 leading-relaxed font-medium">
                                    Cegah siswa mengumpulkan tugas jika melewati tenggat waktu.
                                </label>
                            </div>
                        </div>

                        <div class="pt-5 border-t border-slate-200">
                            <label class="block text-sm font-semibold text-[#092C4C] mb-2">Penilaian</label>
                            @php
                                $isUngraded = is_null($meeting->max_score);
                            @endphp
                            <input type="number" name="max_score" id="max_score"
                                placeholder="Nilai Maksimal (Misal: 100)"
                                class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3 disabled:bg-slate-100 disabled:text-slate-400"
                                value="{{ old('max_score', $meeting->max_score ?? 100) }}"
                                {{ $isUngraded ? 'disabled' : '' }}>

                            <div class="mt-3 flex items-center gap-2.5">
                                <input type="checkbox" id="is_ungraded" {{ $isUngraded ? 'checked' : '' }}
                                    class="rounded text-[#00A79D] focus:ring-[#00A79D]/20 border-slate-300">
                                <label for="is_ungraded" class="text-[11px] text-slate-600 font-medium">Tidak
                                    dinilai</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('teacher.kelas.show', $kelas->id) }}"
                    class="text-slate-500 hover:text-slate-700 font-medium px-4 py-2 text-sm transition-colors rounded-[8px] hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                    class="bg-[#00A79D] hover:bg-[#008f87] text-white px-6 py-2 rounded-[8px] text-sm font-medium transition-all shadow-md shadow-teal-100 hover:shadow-lg">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Tuliskan instruksi atau detail di sini...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        [{
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        [{
                            'header': [1, 2, 3, false]
                        }],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        ['clean']
                    ]
                }
            });

            var form = document.getElementById('meetingForm');
            form.onsubmit = function() {
                var htmlContent = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
                document.getElementById('description').value = htmlContent;
            };

            const ungradedCheck = document.getElementById('is_ungraded');
            const scoreInput = document.getElementById('max_score');

            if (ungradedCheck && scoreInput) {
                // Set initial state based on database
                if (ungradedCheck.checked) {
                    scoreInput.classList.add('opacity-50');
                }

                ungradedCheck.addEventListener('change', function() {
                    if (this.checked) {
                        scoreInput.disabled = true;
                        scoreInput.value = '';
                        scoreInput.classList.add('opacity-50');
                    } else {
                        scoreInput.disabled = false;
                        scoreInput.value = '100';
                        scoreInput.classList.remove('opacity-50');
                    }
                });
            }
        });

        function addLinkField() {
            const container = document.getElementById('link-container');
            const wrapper = document.createElement('div');
            wrapper.className = 'flex gap-2 animate-fade-in mt-3';

            wrapper.innerHTML = `
                <input type="url" name="links[]" 
                    class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3"
                    placeholder="https://...">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;
            container.appendChild(wrapper);
        }

        function addFileField() {
            const container = document.getElementById('file-container');
            const wrapper = document.createElement('div');
            wrapper.className = 'flex gap-2 items-center animate-fade-in mt-3';

            wrapper.innerHTML = `
                <input type="file" name="files[]" 
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 transition-all cursor-pointer border border-slate-300 rounded-[8px] p-1.5">
                <button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            `;
            container.appendChild(wrapper);
        }
    </script>

@endsection
