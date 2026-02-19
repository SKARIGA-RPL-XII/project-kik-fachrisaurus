@props(['action'])

<form action="{{ $action }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')" class="inline-block">
    @csrf
    @method('DELETE')
    <button type="submit" 
            class="w-9 h-9 flex items-center justify-center rounded-[8px] bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition shadow-sm"
            title="Hapus">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/>
        </svg>
    </button>
</form>