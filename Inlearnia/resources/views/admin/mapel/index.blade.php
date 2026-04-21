@extends('layouts.app')

@section('content')
    <x-sidebar />

    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'List Mapel', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="List Mapel">
            <span class="text-[20px] font-normal text-[#092C4C] opacity-50">
                {{ $subjects->total() }} Data
            </span>
        </x-ui.title>

        <x-ui.button-create href="#" onclick="event.preventDefault(); toggleModal('modalCreate')" label="Buat Mapel" />
    </div>

    <div class="bg-white rounded-[20px] shadow-sm border border-slate-200 p-6">

        <form method="GET" id="filterForm" class="mb-6">
            <label class="text-[14px] font-medium text-[#092C4C] block mb-2">Cari & Filter</label>

            <div class="flex flex-wrap items-center gap-4">
                <div>
                    <x-ui.search-bar target="mapelResultContainer" formId="filterForm" />
                </div>

                <div class="relative">
                    <select name="curriculum"
                        class="appearance-none bg-white pl-9 pr-8 py-2 rounded-[10px] border border-slate-300 focus:outline-none focus:border-[#0F4C5C] text-[14px] transition h-[40px] cursor-pointer">
                        <option value="">Semua Kurikulum</option>
                        <option value="k13" {{ request('curriculum') == 'k13' ? 'selected' : '' }}>K-13</option>
                        <option value="merdeka" {{ request('curriculum') == 'merdeka' ? 'selected' : '' }}>Merdeka</option>
                    </select>

                    <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400 pointer-events-none" fill="none"
                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 4h18M6 10h12M10 16h4" />
                    </svg>
                </div>
            </div>
        </form>

        <div id="mapelResultContainer">
            <div class="overflow-hidden rounded-[15px] border border-slate-200">
                <table class="w-full text-left">
                    <thead class="bg-[#0F4C5C] text-white text-[14px]">
                        <tr>
                            <x-ui.table.th-sort label="Nama Mapel" field="name" class="pl-14" />
                            <x-ui.table.th-sort label="Kurikulum" />
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="text-[14px] text-[#092C4C]">
                        @forelse($subjects as $subject)
                            <tr class="border-t border-slate-200 hover:bg-slate-50 transition">
                                <td class="pl-14 pr-6 py-4">
                                    <div class="flex items-center gap-4">
                                        @if ($subject->image)
                                            <img src="{{ asset('storage/' . $subject->image) }}"
                                                class="w-12 h-12 rounded-full object-cover">
                                        @else
                                            <div
                                                class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="font-medium">{{ $subject->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :label="$subject->curriculum" :color="$subject->curriculum == 'merdeka' ? 'success' : 'info'" />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <x-ui.action.edit href="#"
                                            onclick="event.preventDefault(); toggleModal('modalEdit{{ $subject->id }}')" />
                                        <x-ui.action.delete action="{{ route('admin.mapel.destroy', $subject->id) }}" />
                                    </div>
                                </td>
                            </tr>
                            {{-- Modal Edit --}}
                            <x-ui.modal-form id="modalEdit{{ $subject->id }}" title="Edit Mapel"
                                description="Ubah data mapel sesuai kebutuhan" iconBg="#FFAE00"
                                buttonColor="bg-[#FFAE00] hover:bg-[#e69c00]">

                                <form action="{{ route('admin.mapel.update', $subject->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <x-slot name="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                        </svg>
                                    </x-slot>

                                    {{-- Gambar --}}
                                    <div class="mb-5">
                                        <label class="text-[13px] font-medium text-slate-600 block mb-2">Gambar
                                            Mapel</label>
                                        <div
                                            class="flex items-center border border-slate-300 rounded-[10px] overflow-hidden h-[40px]">
                                            <label for="imageEdit{{ $subject->id }}"
                                                class="px-4 h-full flex items-center bg-slate-50 text-[13px] text-slate-600 font-medium border-r border-slate-300 cursor-pointer hover:bg-slate-100 transition whitespace-nowrap">
                                                Choose File
                                            </label>

                                            <span id="fileLabelEdit{{ $subject->id }}"
                                                class="px-4 text-[13px] text-slate-400 truncate">
                                                {{ $subject->image ? 'Change image...' : 'No file chosen' }}
                                            </span>

                                            <input type="file" name="image" id="imageEdit{{ $subject->id }}"
                                                accept="image/*" class="hidden"
                                                onchange="document.getElementById('fileLabelEdit{{ $subject->id }}').textContent = this.files[0]?.name || 'No file chosen'">
                                        </div>
                                    </div>

                                    {{-- Nama --}}
                                    <div class="mb-5">
                                        <label class="text-[13px] font-medium text-slate-600 block mb-2">
                                            Nama Mapel <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" required maxlength="50"
                                            value="{{ $subject->name }}"
                                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px] text-[#092C4C] focus:outline-none focus:border-[#0F4C5C] transition">
                                        <p class="text-[12px] text-slate-400 mt-1">Maksimal 50 karakter</p>
                                    </div>

                                    {{-- Kurikulum --}}
                                    <div class="mb-7">
                                        <label class="text-[13px] font-medium text-slate-600 block mb-2">
                                            Jenis Kurikulum <span class="text-red-500">*</span>
                                        </label>

                                        <div class="relative">
                                            <select name="curriculum" required
                                                class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px] focus:outline-none focus:border-[#0F4C5C] transition cursor-pointer bg-white appearance-none {{ $subject->curriculum ? 'text-[#092C4C]' : 'text-slate-400' }}">

                                                <option value="" disabled>Pilih jenis kurikulum</option>
                                                <option value="k13"
                                                    {{ $subject->curriculum == 'k13' ? 'selected' : '' }}>K-13</option>
                                                <option value="merdeka"
                                                    {{ $subject->curriculum == 'merdeka' ? 'selected' : '' }}>Merdeka
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Buttons --}}
                                    <div class="flex justify-end gap-3">
                                        <button type="button" onclick="toggleModal('modalEdit{{ $subject->id }}')"
                                            class="px-5 py-2 rounded-[10px] border border-slate-300 text-[13px] text-slate-500 hover:bg-slate-50 transition">
                                            Batalkan
                                        </button>
                                        <button type="submit"
                                            class="px-5 py-2 bg-[#FFAE00] hover:bg-[#e69c00] text-white text-[13px] rounded-[10px] transition">
                                            Simpan
                                        </button>
                                    </div>
                                </form>

                            </x-ui.modal-form>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-6 text-slate-400">Data mapel tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6 text-[13px] text-slate-500">
                <div>
                    Menampilkan {{ $subjects->firstItem() ?? 0 }}-{{ $subjects->lastItem() ?? 0 }} dari
                    {{ $subjects->total() }} data
                </div>
                <div>
                    {{ $subjects->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Modal Create --}}
        <x-ui.modal-form id="modalCreate" title="Buat Mapel Baru"
            description="Buat mapel baru dengan mengisi form di bawah ini" iconBg="#0F9E8A"
            buttonColor="bg-indigo-600 hover:bg-indigo-700">

            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </x-slot>

            <form action="{{ route('admin.mapel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Gambar Mapel --}}
                <div class="mb-5">
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">Gambar Mapel</label>
                    <div class="flex items-center border border-slate-300 rounded-[10px] overflow-hidden h-[40px]">
                        <label for="imageInput"
                            class="px-4 h-full flex items-center bg-slate-50 text-[13px] text-slate-600 font-medium border-r border-slate-300 cursor-pointer hover:bg-slate-100 transition whitespace-nowrap">
                            Choose File
                        </label>
                        <span id="fileLabel" class="px-4 text-[13px] text-slate-400 truncate">No file
                            chosen</span>
                        <input type="file" name="image" id="imageInput" accept="image/*" class="hidden"
                            onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name || 'No file chosen'">
                    </div>
                </div>

                {{-- Nama Mapel --}}
                <div class="mb-5">
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">
                        Nama Mapel <span class="text-red-500">*</span>
                    </label>
                    <input type="text" required name="name" maxlength="50"
                        class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px] text-[#092C4C] focus:outline-none focus:border-[#0F4C5C] transition placeholder:text-slate-400"
                        placeholder="Masukan nama mapel">
                    <p class="text-[12px] text-slate-400 mt-1">Maksimal 50 karakter</p>
                </div>

                {{-- Jenis Kurikulum --}}
                <div class="mb-7">
                    <label class="text-[13px] font-medium text-slate-600 block mb-2">
                        Jenis Kurikulum <span class="text-red-500">*</span>
                    </label>

                    <div class="relative">
                        <select name="curriculum" required
                            class="w-full border border-slate-300 rounded-[10px] px-4 py-2 text-[13px] text-slate-400 focus:outline-none focus:border-[#0F4C5C] transition cursor-pointer bg-white appearance-none"
                            onchange="this.classList.remove('text-slate-400'); this.classList.add('text-[#092C4C]')">

                            <option value="" disabled selected>Pilih jenis kurikulum</option>
                            <option value="k13">K-13</option>
                            <option value="merdeka">Merdeka</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modalCreate')"
                        class="px-5 py-2 rounded-[10px] border border-slate-300 text-[13px] text-slate-500 hover:bg-slate-50 transition">
                        Batalkan
                    </button>
                    <button type="submit"
                        class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[13px] rounded-[10px]">
                        Selesai
                    </button>
                </div>
            </form>
        </x-ui.modal-form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        function toggleModal(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
    </script>
@endsection
