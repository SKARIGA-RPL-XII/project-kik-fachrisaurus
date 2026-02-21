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
        <x-stat-card label="Jumlah Seluruh Kelas" :count="$totalKelas" suffix="Kelas" bgColor="bg-[#E6F6F5]"
            iconBg="bg-[#00A79D]" icon="grid" />
        <x-stat-card label="Jumlah Seluruh Siswa" :count="$totalSiswa" suffix="Siswa" bgColor="bg-[#FFF4E8]"
            iconBg="bg-[#FF9933]" icon="student" />
        <x-stat-card label="Jumlah Seluruh Pengajar" :count="$totalPengajar" suffix="Pengajar" bgColor="bg-[#F1E9FF]"
            iconBg="bg-[#8E59FF]" icon="teacher" />
    </div>

    {{-- Main Content: Menggunakan items-stretch agar tinggi kiri & kanan sama --}}
    <div class="mt-10 grid grid-cols-12 gap-[25px] items-stretch">

        {{-- Sisi Kiri: Chart --}}
        <div
            class="col-span-8 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50 flex flex-col justify-between h-full">
            <div>
                <h2 class="text-[16px] font-bold text-[#092C4C] mb-6">Chart Penambahan Siswa</h2>
                <div id="studentChart"></div>
            </div>

            {{-- Legenda --}}
            <div class="flex justify-center gap-8 mt-4">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#3B28FF]"></div>
                    <span class="text-[12px] font-medium text-[#092C4C]">Tertinggi</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#00A79D]"></div>
                    <span class="text-[12px] font-medium text-[#092C4C]">Terendah</span>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Pengajar Terbaru (Tinggi Sama dengan Chart) --}}
        <div class="col-span-4 bg-white p-6 rounded-[15px] shadow-sm border border-slate-50 flex flex-col h-full">
            <h2 class="text-[16px] font-bold text-[#092C4C] mb-6">Pengajar Terbaru</h2>

            {{-- Gunakan flex-col + gap untuk daftar dinamis --}}
            <div class="flex flex-col flex-grow gap-y-3 overflow-y-auto">
                @foreach($latestTeachers as $teacher)
                    <div class="flex items-center justify-between group py-1">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-[38px] h-[38px] rounded-full overflow-hidden shrink-0 border border-slate-100">
                                <img src="{{ $teacher->profile_photo ? asset('storage/' . $teacher->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->name) . '&background=044153&color=fff' }}"
                                    class="w-full h-full object-cover">
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="text-[13px] font-bold text-[#434343] truncate leading-tight">{{ $teacher->name }}</h4>
                                <p class="text-[11px] text-[#C9C9C9] font-light truncate">{{ $teacher->email }}</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.users.show', $teacher->id) }}"
                            class="w-[22px] h-[22px] bg-[#00A79D] rounded-full flex items-center justify-center text-white shrink-0 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3 h-3"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="3">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var options = {
                series: [{
                    name: 'Tertinggi',
                    data: [15, 18, 12, 19, 14, 18, 22]
                }, {
                    name: 'Terendah',
                    data: [12, 11, 22, 9, 11, 13, 10]
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false },
                    animations: { enabled: true }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%', // Lebar total grup batang ditingkatkan sedikit
                        borderRadius: 3,
                        // Memberikan jarak (gap) antar batang di dalam satu kategori
                        dataLabels: { position: 'top' },
                    },
                },
                // Kuncinya ada di sini: stroke dengan width memberikan jarak visual
                stroke: {
                    show: true,
                    width: 5, // Mengatur jarak antar batang dalam grup
                    colors: ['transparent']
                },
                colors: ['#3B28FF', '#00A79D'],
                dataLabels: { enabled: false },
                xaxis: {
                    categories: ['2019', '2020', '2021', '2022', '2023', '2024', '2025'],
                    axisBorder: { show: false },
                    labels: { style: { fontSize: '11px', colors: '#94a3b8' } }
                },
                yaxis: {
                    tickAmount: 5,
                    labels: { style: { fontSize: '11px', colors: '#94a3b8' } }
                },
                legend: { show: false },
                grid: { strokeDashArray: 4, borderColor: '#f1f5f9' }
            };

            new ApexCharts(document.querySelector("#studentChart"), options).render();
        });
    </script>
@endsection