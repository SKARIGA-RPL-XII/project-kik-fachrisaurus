@extends('layouts.app')

@section('content')
    <x-sidebar />

    <header class="flex justify-between items-center mb-7">
        <x-header-profile />
        <x-calendar />
    </header>

    <x-breadcrumb :items="[]" />
@endsection