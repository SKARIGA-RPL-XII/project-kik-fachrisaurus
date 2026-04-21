@extends('layouts.app')

@section('content')
    <x-sidebar />

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[
                ['label' => 'List Kelas', 'url' => route('admin.kelas.index')],
                ['label' => 'Buat Kelas', 'url' => null],
            ]" />
        </div>
        <x-calendar />
    </div>

    <style>
        .form-input {
            width: 100%;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 13px;
            color: #092C4C;
            transition: border-color .15s, box-shadow .15s;
            background: #fff;
            outline: none;
        }
        .form-input:focus {
            border-color: #00A99D;
            box-shadow: 0 0 0 3px rgba(0, 169, 157, .1);
        }
        textarea.form-input { resize: none; }
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%2394a3b8'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 36px;
        }
        .logo-drop {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            overflow: hidden;
            position: relative;
        }
        .logo-drop:hover { border-color: #00A99D; background: #f0fdfb; }
        .logo-drop input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
        .stu-row { transition: background .12s; }
        .stu-row:hover { background: #f0fdfb; }
        .stu-row td {
            padding: 9px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            color: #334155;
            vertical-align: middle;
        }
        .stu-row.is-checked { background: #f0fdfb; }
        .stu-cb { width: 16px; height: 16px; accent-color: #00A99D; cursor: pointer; }
        .empty-state { padding: 32px 16px; text-align: center; color: #94a3b8; font-size: 13px; }
        .char-warn { color: #f59e0b; }
        .char-over { color: #ef4444; }
    </style>

    <div class="max-w-6xl">

        <div class="flex items-center gap-3 mb-6">
            <div>
                <h1 class="text-[18px] font-bold text-[#092C4C]">Buat Kelas Baru</h1>
                <p class="text-[12px] text-slate-400">Isi informasi kelas dan pilih anggota siswa</p>
            </div>
        </div>

        <form action="{{ route('admin.kelas.store') }}" method="POST" enctype="multipart/form-data" id="mainForm">
            @csrf

            {{-- Hidden input untuk students --}}
            <input type="hidden" name="students" id="studentsHidden" value="[]">

            {{-- ===== BAGIAN ATAS: Info Kelas ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5">

                {{-- KIRI (3 col) --}}
                <div class="lg:col-span-3 bg-white rounded-[18px] border border-slate-200 shadow-sm p-7 space-y-5">
                    {{-- Logo Upload --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-[#092C4C] mb-2">Logo Kelas</label>
                        <div class="logo-drop" id="logoDrop">
                            <input type="file" name="logo" accept="image/*" id="logoInput">
                            <div id="logoPlaceholder" class="flex flex-col items-center justify-center py-6 gap-2 pointer-events-none">
                                <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[12px] text-slate-400">Klik untuk unggah logo</span>
                                <span class="text-[11px] text-slate-300">PNG, JPG — maks. 2MB</span>
                            </div>
                            <div id="logoPreviewWrap" class="hidden items-center justify-center py-4">
                                <img id="logoPreview" class="w-20 h-20 rounded-full object-cover border-2 border-[#00A99D] shadow">
                            </div>
                        </div>
                    </div>

                    {{-- Nama Kelas --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-[#092C4C] mb-2">Nama Kelas<span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="form-input" placeholder="Masukkan nama kelas">
                    </div>

                    {{-- Mapel --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-[#092C4C] mb-2">Mata Pelajaran<span class="text-red-500">*</span></label>
                        <select name="subject_id" class="form-input" required>
                            <option value="">Pilih mata pelajaran</option>
                            @foreach ($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pengajar --}}
                    <div>
                        <label class="block text-[13px] font-semibold text-[#092C4C] mb-2">Pengajar<span class="text-red-500">*</span></label>
                        <select name="teacher_id" class="form-input" required>
                            <option value="">Pilih pengajar</option>
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- KANAN (2 col): Deskripsi --}}
                <div class="lg:col-span-2 bg-white rounded-[18px] border border-slate-200 shadow-sm p-7 flex flex-col">
                    <div class="flex-1 flex flex-col">
                        <label class="block text-[13px] font-semibold text-[#092C4C] mb-2">Deskripsi</label>
                        <textarea name="description" id="descInput" maxlength="210" class="form-input flex-1" style="min-height: 120px;" placeholder="Tuliskan deskripsi singkat tentang kelas ini..."></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-[11px] text-slate-400">Maksimal 210 karakter</span>
                            <span id="charCount" class="text-[11px] text-slate-400 font-medium">0 / 210</span>
                        </div>
                    </div>
                    <div class="mt-5 bg-[#f0fdfb] border border-[#ccf0ed] rounded-[12px] p-4">
                        <p class="text-[11px] font-semibold text-[#00A99D] mb-2">💡 Tips Pengisian</p>
                        <ul class="text-[11px] text-slate-500 space-y-1.5">
                            <li>· Nama kelas sebaiknya mencerminkan jenjang &amp; mata pelajaran</li>
                            <li>· Deskripsi singkat membantu siswa memahami konten kelas</li>
                            <li>· Logo kelas memudahkan siswa mengenali kelas mereka</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ===== BAGIAN: Pilih Siswa ===== --}}
            <div class="bg-white rounded-[20px] border border-slate-200 shadow-sm p-6 mb-5">
                <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
                    <h3 class="text-[18px] font-bold text-[#092C4C]">Pilih Siswa Untuk Bergabung</h3>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- TABEL SEMUA SISWA --}}
                    <div class="overflow-hidden rounded-[15px] border border-slate-200 flex flex-col">
                        <div class="bg-white px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center">
                                <span class="text-[15px] font-bold text-[#092C4C]">Semua Siswa</span>
                                <span id="allCountBadge" class="ml-2 text-[12px] bg-slate-100 text-slate-600 font-semibold px-2.5 py-1 rounded-full"></span>
                            </div>
                            <div class="relative w-full sm:w-auto sm:min-w-[220px]">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35" />
                                </svg>
                                <input id="searchAll" class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-[12px] text-[13px] text-[#092C4C] placeholder-slate-400 focus:outline-none focus:border-[#0F4C5C] focus:ring-1 focus:ring-[#0F4C5C] transition-all" placeholder="Cari siswa...">
                            </div>
                        </div>
                        <div class="w-full overflow-x-auto bg-white">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead class="bg-[#0F4C5C] text-white text-[13px]">
                                    <tr>
                                        <th class="px-5 py-3 text-center w-12 font-medium">
                                            <input type="checkbox" id="selectAll" class="w-4 h-4 cursor-pointer accent-[#00A99D] rounded-[4px]">
                                        </th>
                                        <th class="px-5 py-3 font-medium uppercase tracking-wider text-[11px]">Nama Siswa</th>
                                        <th class="px-5 py-3 font-medium uppercase tracking-wider text-[11px]">Email</th>
                                    </tr>
                                </thead>
                                <tbody id="allTable" class="text-[14px] text-[#092C4C]"></tbody>
                            </table>
                        </div>
                        <div class="bg-white border-t border-slate-200 px-5 py-4 flex items-center justify-between">
                            <button type="button" onclick="prev('all')" id="prevAll" class="text-[13px] font-medium text-slate-600 hover:text-[#0F4C5C] px-3 py-1.5 border border-slate-200 hover:bg-slate-50 rounded-lg transition">← Prev</button>
                            <span id="pageAll" class="text-[13px] text-slate-500 font-medium"></span>
                            <button type="button" onclick="next('all')" id="nextAll" class="text-[13px] font-medium text-slate-600 hover:text-[#0F4C5C] px-3 py-1.5 border border-slate-200 hover:bg-slate-50 rounded-lg transition">Next →</button>
                        </div>
                    </div>

                    {{-- TABEL SISWA TERPILIH --}}
                    <div class="overflow-hidden rounded-[15px] border border-slate-200 flex flex-col shadow-sm">
                        <div class="bg-white px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center">
                                <span class="text-[15px] font-bold text-[#0F4C5C]">Siswa Terpilih</span>
                                <span id="selCountBadge" class="ml-2 text-[12px] bg-[#00A99D] text-white font-semibold px-2.5 py-1 rounded-full">0</span>
                            </div>
                            <div class="relative w-full sm:w-auto sm:min-w-[220px]">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="11" cy="11" r="8" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35" />
                                </svg>
                                <input id="searchSelected" class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-[12px] text-[13px] text-[#092C4C] placeholder-slate-400 focus:outline-none focus:border-[#0F4C5C] focus:ring-1 focus:ring-[#0F4C5C] transition-all" placeholder="Cari...">
                            </div>
                        </div>
                        <div class="w-full overflow-x-auto bg-white">
                            <table class="w-full text-left whitespace-nowrap">
                                <thead class="bg-[#0F4C5C] text-white text-[14px]">
                                    <tr>
                                        <th class="px-5 py-4 text-center w-12 font-medium">
                                            <svg class="w-5 h-5 text-white/70 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </th>
                                        <th class="px-5 py-4 font-medium">Nama Siswa</th>
                                        <th class="px-5 py-4 font-medium">Email</th>
                                    </tr>
                                </thead>
                                <tbody id="selectedTable" class="text-[14px] text-[#092C4C]"></tbody>
                            </table>
                        </div>
                        <div class="bg-white border-t border-slate-200 px-5 py-4 flex items-center justify-between">
                            <button type="button" onclick="prev('selected')" id="prevSelected" class="text-[13px] font-medium text-slate-600 hover:text-[#0F4C5C] px-3 py-1.5 border border-slate-200 hover:bg-slate-50 rounded-lg transition">← Prev</button>
                            <span id="pageSelected" class="text-[13px] text-slate-500 font-medium"></span>
                            <button type="button" onclick="next('selected')" id="nextSelected" class="text-[13px] font-medium text-slate-600 hover:text-[#0F4C5C] px-3 py-1.5 border border-slate-200 hover:bg-slate-50 rounded-lg transition">Next →</button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 mt-10">
                <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-[10px] border border-slate-200 text-[13px] text-slate-500 hover:bg-slate-50 transition">
                    Batalkan
                </a>
                <button type="submit" class="inline-flex items-center gap-2 bg-[#00A99D] hover:bg-[#008f87] text-white px-7 py-2.5 rounded-[10px] text-[13px] font-semibold shadow-md shadow-teal-500/20 transition">
                    Simpan Kelas
                </button>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ===== Logo Preview ===== */
            const logoInput = document.getElementById('logoInput');
            const logoPlaceholder = document.getElementById('logoPlaceholder');
            const logoPreviewWrap = document.getElementById('logoPreviewWrap');
            const logoPreview = document.getElementById('logoPreview');

            logoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        logoPreview.src = e.target.result;
                        logoPlaceholder.classList.add('hidden');
                        logoPreviewWrap.classList.remove('hidden');
                        logoPreviewWrap.classList.add('flex');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            /* ===== Char counter ===== */
            const descInput = document.getElementById('descInput');
            const charCount = document.getElementById('charCount');

            descInput.addEventListener('input', function () {
                const len = this.value.length;
                charCount.textContent = `${len} / 210`;
                charCount.className = len >= 210 ? 'text-[11px] font-medium char-over' :
                    len >= 180 ? 'text-[11px] font-medium char-warn' :
                    'text-[11px] text-slate-400 font-medium';
            });

            /* ===== Student Picker ===== */
            const students = @json($students);
            let selected = [];
            const perPage = 8;

            let state = {
                all:      { page: 1, search: '' },
                selected: { page: 1, search: '' },
            };

            function filter(data, search) {
                if (!search) return data;
                return data.filter(s =>
                    s.name.toLowerCase().includes(search) ||
                    s.email.toLowerCase().includes(search)
                );
            }

            function paginate(data, page) {
                const start = (page - 1) * perPage;
                return data.slice(start, start + perPage);
            }

            function avatarInitials(name) {
                return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
            }

            const avatarColors = [
                ['#E1F5EE', '#0F6E56'],
                ['#E6F1FB', '#185FA5'],
                ['#FAEEDA', '#854F0B'],
                ['#EEEDFE', '#534AB7'],
                ['#FBEAF0', '#993556'],
                ['#FEF3C7', '#92400E'],
            ];

            function studentRow(s, isChecked, idx) {
                const [bg, fg] = avatarColors[idx % avatarColors.length];
                const initials = avatarInitials(s.name);
                return `
                <tr class="stu-row border-b border-slate-50 hover:bg-slate-50/50 transition-colors ${isChecked ? 'is-checked bg-emerald-50/40' : ''}" id="row-${s.id}">
                    <td class="px-5 py-3 text-center">
                        <input type="checkbox"
                            class="stu-cb w-4 h-4 cursor-pointer rounded-[4px] border-slate-300 text-[#00A99D] focus:ring-[#00A99D] focus:ring-offset-0 transition-all"
                            ${isChecked ? 'checked' : ''}
                            onchange="window.toggle(${s.id})">
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold flex-shrink-0"
                                style="background:${bg}; color:${fg};">${initials}</div>
                            <span class="font-medium text-[#092C4C] text-[13.5px]">${s.name}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-slate-400 text-[12px]">${s.email}</td>
                </tr>`;
            }

            function emptyRow(msg) {
                return `<tr><td colspan="3"><div class="empty-state">
                    <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    ${msg}
                </div></td></tr>`;
            }

            function syncHidden() {
                document.getElementById('studentsHidden').value = JSON.stringify(selected);
            }

            function renderAll() {
                const filtered = filter(students, state.all.search);
                const totalPages = Math.ceil(filtered.length / perPage) || 1;
                state.all.page = Math.min(state.all.page, totalPages);
                const rows = paginate(filtered, state.all.page);

                document.getElementById('allTable').innerHTML = rows.length
                    ? rows.map(s => {
                        const globalIdx = students.findIndex(x => x.id === s.id);
                        return studentRow(s, selected.includes(s.id), globalIdx);
                    }).join('')
                    : emptyRow('Tidak ada siswa ditemukan');

                document.getElementById('pageAll').textContent = `${state.all.page} / ${totalPages}`;
                document.getElementById('allCountBadge').textContent = filtered.length + ' siswa';
                document.getElementById('prevAll').disabled = state.all.page <= 1;
                document.getElementById('nextAll').disabled = state.all.page >= totalPages;
            }

            function renderSelected() {
                let data = students.filter(s => selected.includes(s.id));
                data = filter(data, state.selected.search);
                const totalPages = Math.ceil(data.length / perPage) || 1;
                state.selected.page = Math.min(state.selected.page, totalPages);
                const rows = paginate(data, state.selected.page);

                document.getElementById('selectedTable').innerHTML = rows.length
                    ? rows.map(s => {
                        const globalIdx = students.findIndex(x => x.id === s.id);
                        return studentRow(s, true, globalIdx);
                    }).join('')
                    : emptyRow('Belum ada siswa terpilih');

                document.getElementById('pageSelected').textContent = `${state.selected.page} / ${totalPages}`;
                document.getElementById('selCountBadge').textContent = selected.length;
                document.getElementById('prevSelected').disabled = state.selected.page <= 1;
                document.getElementById('nextSelected').disabled = state.selected.page >= totalPages;
            }

            function render() {
                renderAll();
                renderSelected();
                syncHidden(); // ← update hidden input setiap kali state berubah
            }

            window.toggle = id => {
                selected.includes(id)
                    ? selected = selected.filter(x => x !== id)
                    : selected.push(id);
                render();
            };

            document.getElementById('selectAll').addEventListener('change', function () {
                selected = this.checked ? students.map(s => s.id) : [];
                render();
            });

            window.next = type => { state[type].page++; render(); };
            window.prev = type => { if (state[type].page > 1) state[type].page--; render(); };

            document.getElementById('searchAll').addEventListener('input', e => {
                state.all.search = e.target.value.toLowerCase();
                state.all.page = 1;
                renderAll();
            });

            document.getElementById('searchSelected').addEventListener('input', e => {
                state.selected.search = e.target.value.toLowerCase();
                state.selected.page = 1;
                renderSelected();
            });

            render();
        });
    </script>
@endsection