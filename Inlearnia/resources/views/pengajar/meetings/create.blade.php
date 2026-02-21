@extends('layouts.app')

@section('content')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Styling Toolbar Editor agar menyatu dengan border container Tailwind */
        .ql-toolbar.ql-snow {
            border: 1px solid #cbd5e1; /* slate-300 */
            border-bottom: 1px solid #e2e8f0; /* slate-200 */
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            background-color: #f8fafc;
            padding: 8px 12px;
        }

        /* Container tempat mengetik */
        .ql-container.ql-snow {
            border: 1px solid #cbd5e1; /* slate-300 */
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
        }

        /* Efek fokus manual jika container di-klik (meniru ring tailwind) */
        .ql-container.ql-snow.is-focused,
        .ql-toolbar.ql-snow:focus-within {
            border-color: #00A79D !important;
            box-shadow: 0 0 0 3px rgba(0, 167, 157, 0.2);
            transition: all 0.2s ease-in-out;
        }

        #editor-container .ql-editor { min-height: 200px; }
        .ql-editor.ql-blank::before { color: #94a3b8; font-style: normal; }
    </style>

    <x-sidebar />

    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'Kelas Saya', 'url' => route('teacher.kelas.index')],
                ['label' => $kelas->name, 'url' => route('teacher.kelas.show', $kelas->id)],
                ['label' => 'Buat ' . ucfirst($type), 'url' => null]
            ]" />
        </div>
        <x-calendar />
    </div>

    <div class="max-w-6xl bg-white rounded-[15px] shadow-sm border border-slate-200 p-8 mb-10">
        
        <div class="mb-8 border-b border-slate-200 pb-5">
            <h2 class="text-xl font-bold text-[#092C4C]">Buat {{ ucfirst($type) }} Baru</h2>
            <p class="text-sm text-slate-500 mt-1.5">Tambahkan {{ $type }} untuk kelas <span class="font-semibold text-slate-700">{{ $kelas->name }}</span></p>
        </div>

        <form action="{{ route('teacher.meetings.store') }}" method="POST" enctype="multipart/form-data" id="meetingForm" class="space-y-8">
            @csrf
            <input type="hidden" name="class_id" value="{{ $kelas->id }}">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="md:col-span-2 space-y-7">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-[#092C4C] mb-2">Judul Pertemuan <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required
                            class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3"
                            value="{{ old('title', 'Pertemuan ' . $nextMeetingNumber . ': ') }}">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#092C4C] mb-2">Deskripsi / Instruksi (Opsional)</label>
                        <input type="hidden" name="description" id="description">
                        <div id="editor-container">{!! old('description') !!}</div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-semibold text-[#092C4C]">Lampirkan Tautan (Opsional)</label>
                            <button type="button" onclick="addLinkField()" class="text-[#00A79D] text-xs font-semibold flex items-center gap-1 hover:underline bg-teal-50 px-2 py-1.5 rounded-md transition-colors hover:bg-teal-100">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Tambah Link
                            </button>
                        </div>
                        <div id="link-container" class="space-y-3">
                            <div class="flex gap-2">
                                <input type="url" name="links[]" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3" placeholder="https://...">
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-200">
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-semibold text-[#092C4C]">Lampirkan File (Opsional)</label>
                            <button type="button" onclick="addFileField()" class="text-[#00A79D] text-xs font-semibold flex items-center gap-1 hover:underline bg-teal-50 px-2 py-1.5 rounded-md transition-colors hover:bg-teal-100">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> Tambah File
                            </button>
                        </div>
                        <div id="file-container" class="space-y-3">
                            <div class="flex gap-2 items-center">
                                <input type="file" name="files[]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 transition-all cursor-pointer border border-slate-300 rounded-[8px] p-1.5">
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-2">Format: PDF, DOCX, XLSX, PPTX, JPG, PNG (Max: 5MB)</p>
                    </div>
                </div>

                <div class="space-y-6 bg-slate-50 p-6 rounded-[12px] border border-slate-200 h-fit">
                    <div>
                        <label for="topic" class="block text-sm font-semibold text-[#092C4C] mb-2">Topik (Opsional)</label>
                        <input type="text" name="topic" id="topic" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3" placeholder="Contoh: Bab 1">
                    </div>

                    @if($type === 'tugas')
                        <div class="pt-5 border-t border-slate-200">
                            <label for="deadline" class="block text-sm font-semibold text-[#092C4C] mb-2">Batas Waktu (Opsional)</label>
                            <input type="datetime-local" name="deadline" id="deadline" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3" value="{{ old('deadline') }}">
                            <div class="mt-3 flex items-start gap-2.5">
                                <input type="checkbox" name="disable_late_submission" id="disable_late_submission" value="1" class="mt-0.5 rounded text-[#00A79D] focus:ring-[#00A79D]/20 border-slate-300">
                                <label for="disable_late_submission" class="text-[11px] text-slate-600 leading-relaxed font-medium">Cegah siswa mengumpulkan tugas jika melewati tenggat waktu.</label>
                            </div>
                        </div>

                        <div class="pt-5 border-t border-slate-200">
                            <label class="block text-sm font-semibold text-[#092C4C] mb-2">Penilaian</label>
                            <input type="number" name="max_score" id="max_score" placeholder="Nilai Maksimal (Misal: 100)" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3 disabled:bg-slate-100 disabled:text-slate-400" value="{{ old('max_score', 100) }}">
                            <div class="mt-3 flex items-center gap-2.5">
                                <input type="checkbox" id="is_ungraded" class="rounded text-[#00A79D] focus:ring-[#00A79D]/20 border-slate-300">
                                <label for="is_ungraded" class="text-[11px] text-slate-600 font-medium">Tidak dinilai</label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-6 border-t border-slate-200 flex items-center justify-end gap-3 mt-8">
                <a href="{{ route('teacher.kelas.show', $kelas->id) }}" class="text-slate-500 hover:text-slate-700 font-medium px-4 py-2 text-sm transition-colors rounded-[8px] hover:bg-slate-50">Batal</a>
                <button type="submit" class="bg-[#00A79D] hover:bg-[#008f87] text-white px-6 py-2 rounded-[8px] text-sm font-medium transition-all shadow-md shadow-teal-100 hover:shadow-lg">Posting {{ ucfirst($type) }}</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const quill = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: 'Tuliskan instruksi atau detail di sini...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'header': [1, 2, 3, false] }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['clean']
                    ]
                }
            });

            const form = document.getElementById('meetingForm');
            form.addEventListener('submit', () => {
                const htmlContent = quill.root.innerHTML === '<p><br></p>' ? '' : quill.root.innerHTML;
                document.getElementById('description').value = htmlContent;
            });

            // Efek border fokus agar serasi dengan input Tailwind
            quill.on('selection-change', (range) => {
                const container = document.querySelector('.ql-container.ql-snow');
                const toolbar = document.querySelector('.ql-toolbar.ql-snow');
                if (range) {
                    container.classList.add('is-focused');
                    toolbar.style.borderColor = '#00A79D';
                } else {
                    container.classList.remove('is-focused');
                    toolbar.style.borderColor = '#cbd5e1';
                }
            });

            const ungradedCheck = document.getElementById('is_ungraded');
            const scoreInput = document.getElementById('max_score');
            if (ungradedCheck && scoreInput) {
                ungradedCheck.addEventListener('change', function() {
                    scoreInput.disabled = this.checked;
                    scoreInput.value = this.checked ? '' : '100';
                    scoreInput.classList.toggle('opacity-50', this.checked);
                });
            }
        });

        const removeBtnSvg = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>`;

        window.addLinkField = () => {
            const container = document.getElementById('link-container');
            container.insertAdjacentHTML('beforeend', `<div class="flex gap-2 animate-fade-in"><input type="url" name="links[]" class="w-full rounded-[8px] border-slate-300 focus:border-[#00A79D] focus:ring focus:ring-[#00A79D]/20 transition-all text-sm py-2 px-3" placeholder="https://..."><button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0">${removeBtnSvg}</button></div>`);
        };

        window.addFileField = () => {
            const container = document.getElementById('file-container');
            container.insertAdjacentHTML('beforeend', `<div class="flex gap-2 items-center animate-fade-in"><input type="file" name="files[]" class="block w-full text-sm text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#00A79D] hover:file:bg-teal-100 transition-all cursor-pointer border border-slate-300 rounded-[8px] p-1.5"><button type="button" onclick="this.parentElement.remove()" class="px-3 py-2 bg-red-50 text-red-500 rounded-[8px] hover:bg-red-100 transition-colors flex-shrink-0">${removeBtnSvg}</button></div>`);
        };
    </script>
@endsection