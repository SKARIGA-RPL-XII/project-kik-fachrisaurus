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

        <x-ui.button-create href="{{ route('admin.mapel.create') }}" label="Buat Mapel" />
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

                    <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 4h18M6 10h12M10 16h4"/>
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
                                        @if($subject->image)
                                            <img src="{{ asset('storage/' . $subject->image) }}" class="w-12 h-12 rounded-full object-cover">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-slate-200"></div>
                                        @endif
                                        <span class="font-medium">{{ $subject->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <x-ui.badge 
                                        :label="$subject->curriculum" 
                                        :color="$subject->curriculum == 'merdeka' ? 'success' : 'info'" 
                                    />
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <x-ui.action.edit href="{{ route('admin.mapel.edit', $subject->id) }}" />
                                        <x-ui.action.delete action="{{ route('admin.mapel.destroy', $subject->id) }}" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-6 text-slate-400">Belum ada mapel</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6 text-[13px] text-slate-500">
                <div>
                    Menampilkan {{ $subjects->firstItem() ?? 0 }}-{{ $subjects->lastItem() ?? 0 }} dari {{ $subjects->total() }} data
                </div>
                <div>
                    {{ $subjects->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
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
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif
    </script>
@endsection