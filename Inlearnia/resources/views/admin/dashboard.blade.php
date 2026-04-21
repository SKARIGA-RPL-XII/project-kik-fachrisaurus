@extends('layouts.app')

@section('content')
    <x-sidebar />

    <header class="flex justify-between items-center mb-7">
        <x-header-profile />
        <x-calendar />
    </header>

    <x-breadcrumb :items="[]" />

    {{-- Statistik Section --}}
    <div class="mt-8 flex w-full gap-[25px]">
        <x-stat-card label="Jumlah Seluruh Kelas" :count="$totalKelas" suffix="Kelas" bgColor="bg-[#E6F6F5]" iconBg="bg-[#00A79D]"
            icon="grid" />
        <x-stat-card label="Jumlah Seluruh Siswa" :count="$totalSiswa" suffix="Siswa" bgColor="bg-[#FFF4E8]" iconBg="bg-[#FF9933]"
            icon="student" />
        <x-stat-card label="Jumlah Seluruh Pengajar" :count="$totalPengajar" suffix="Pengajar" bgColor="bg-[#F1E9FF]"
            iconBg="bg-[#8E59FF]" icon="teacher" />
    </div>

    {{-- GRID UTAMA: CHART & PENGAJAR --}}
    <div class="mt-10 grid grid-cols-12 gap-[25px] items-stretch">
        {{-- Sisi Kiri: Chart --}}
        <div class="col-span-8 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50 flex flex-col h-[420px]">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-[16px] font-bold text-[#092C4C]">Tren Pendaftaran Siswa</h2>
                    <p class="text-[12px] text-slate-400">Statistik pertumbuhan siswa per bulan</p>
                </div>
                <form action="{{ route('admin.dashboard') }}" method="GET" id="filterForm">
                    <select name="year" onchange="document.getElementById('filterForm').submit()"
                        class="text-[13px] border-slate-200 bg-slate-50 rounded-lg py-1.5 pl-3 pr-8 font-medium text-[#092C4C]">
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>Tahun
                                {{ $year }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="flex-1 flex items-center">
                <div id="studentChart" class="w-full"></div>
            </div>
            <div class="flex justify-center gap-8 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-0.5 rounded-full bg-[#3B28FF]"></div>
                    <span class="text-[12px] font-medium text-[#092C4C]">Siswa Terdaftar</span>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Pengajar Terbaru --}}
        <div class="col-span-4 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50 flex flex-col h-[420px]">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-[16px] font-bold text-[#092C4C]">Pengajar Terbaru</h2>
            </div>
            <div class="flex flex-col gap-y-4 flex-1">
                @forelse($latestTeachers as $teacher)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-[40px] h-[40px] rounded-full overflow-hidden shrink-0 border border-slate-100">
                                <img src="{{ $teacher->profile_photo ? asset('storage/' . $teacher->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=044153&color=fff' }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="text-[13px] font-bold text-[#434343] truncate leading-tight">{{ $teacher->name }}
                                </h4>
                                <p class="text-[11px] text-[#C9C9C9] font-light truncate">{{ $teacher->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.show', $teacher->id) }}"
                            class="w-[24px] h-[24px] bg-slate-50 group-hover:bg-[#00A79D] text-slate-400 group-hover:text-white rounded-full flex items-center justify-center transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @empty
                    <p class="text-[12px] opacity-50 py-10 text-center">Belum ada data</p>
                @endforelse
            </div>
            <div class="mt-auto pt-4 border-t border-slate-50">
                <a href="{{ route('admin.users.index', ['role' => 'teacher']) }}"
                    class="flex items-center justify-center py-2.5 rounded-xl bg-slate-50 text-[#00A79D] text-[12px] font-bold hover:bg-[#00A79D] hover:text-white transition-all">
                    Lihat Semua Pengajar
                </a>
            </div>
        </div>
    </div>

    {{-- TABEL KELAS TERBARU (DI BAWAH GRID) --}}
    <div class="mt-8 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-[16px] font-bold text-slate-900">
                Daftar Kelas Baru Ditambahkan
            </h2>

            <a href="{{ route('admin.kelas.index') }}" class="text-[12px] font-semibold text-[#00A79D] hover:underline">
                Lihat Semua Kelas
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left table-auto">

                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-3 px-3 text-[11px] font-medium text-slate-400">No</th>
                        <th class="pb-3 px-3 text-[11px] font-medium text-slate-400">Nama kelas</th>
                        <th class="pb-3 px-3 text-[11px] font-medium text-slate-400">Mata pelajaran</th>
                        <th class="pb-3 px-3 text-[11px] font-medium text-slate-400">Pengajar</th>
                        <th class="pb-3 px-3 text-[11px] font-medium text-slate-400 text-center">Jumlah siswa</th>
                        <th class="pb-3 px-3 text-[11px] font-medium text-slate-400 text-right pr-5">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">

                    @forelse($latestClasses as $index => $class)
                        <tr class="hover:bg-slate-50 transition">

                            <td class="py-4 px-3 text-[13px] text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="py-4 px-3 text-[13px] font-medium text-slate-900">
                                {{ $class->name }}
                            </td>

                            <td class="py-4 px-3 text-[13px] text-slate-600">
                                {{ $class->subject->name ?? '-' }}
                            </td>

                            <td class="py-4 px-3 text-[13px] text-slate-600">
                                {{ $class->teacher->name ?? 'Belum ada' }}
                            </td>

                            <td class="py-4 px-3 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-600 text-[11px] font-medium">
                                    {{ $class->students_count }} siswa
                                </span>
                            </td>

                            <td class="py-4 px-3 text-right pr-5">
                                <a href="{{ route('admin.kelas.show', $class->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                    title="Detail">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                                    </svg>

                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-sm text-slate-400">
                                Tidak ada data kelas terbaru.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>
    </div>

    {{-- ApexCharts Script --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'Siswa Baru',
                    data: @json($monthlyStudents)
                }],
                chart: {
                    type: 'area',
                    height: '100%',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: ['#3B28FF'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.05
                    }
                },
                markers: {
                    size: 4,
                    colors: ["#3B28FF"],
                    strokeColors: "#fff",
                    strokeWidth: 2
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov',
                        'Des'
                    ],
                    labels: {
                        style: {
                            fontSize: '11px',
                            colors: '#94a3b8'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '11px',
                            colors: '#94a3b8'
                        }
                    }
                },
                grid: {
                    borderColor: '#F1F5F9',
                    strokeDashArray: 4
                },
                tooltip: {
                    y: {
                        formatter: val => val + " Siswa Baru"
                    }
                }
            };
            new ApexCharts(document.querySelector("#studentChart"), options).render();
        });
    </script>
@endsection
