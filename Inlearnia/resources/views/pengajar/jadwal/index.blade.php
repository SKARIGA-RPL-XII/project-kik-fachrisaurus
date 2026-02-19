@extends('layouts.app')

@section('content')
    <x-sidebar />
    
    <div class="flex justify-between items-center mb-10">
        <div class="flex-1">
            <x-breadcrumb :items="[['label' => 'Profil Saya', 'url' => null]]" />
        </div>
        <x-calendar />
    </div>

    <div class="flex justify-between items-center mt-[40px] mb-[25px]">
        <x-ui.title text="Jadwal Saya" />

        <x-ui.button-create href="#" label="Buat Jadwal" />
    </div>
@endsection
