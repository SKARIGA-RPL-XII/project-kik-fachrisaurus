@props([
    'placeholder' => 'Telusuri...',
    'target' => null,  // ID div yang akan di-refresh
    'formId' => null,  // ID form filter (agar select option ikut terkirim)
])

@php
    $uniqueId = 'search_' . uniqid();
@endphp

<div class="relative w-full md:w-[380px] h-[40px]" id="{{ $uniqueId }}">
    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </span>
    
    {{-- Input Search --}}
    <input type="text" 
           name="search" 
           value="{{ request('search') }}" 
           placeholder="{{ $placeholder }}" 
           autocomplete="off"
           class="searchInput w-full h-full pl-12 pr-10 rounded-[10px] border border-gray-200 text-sm focus:outline-none focus:border-[#00A79D] transition-all">
    
    {{-- Tombol X (Clear) --}}
    <button type="button" class="clearSearchBtn absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 hidden">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </button>
</div>

@push('scripts') 
<script>
    // FIX 1: Gunakan EventListener DOMContentLoaded
    // Ini memastikan script baru jalan SETELAH semua HTML selesai dirender
    document.addEventListener('DOMContentLoaded', function() {
        
        const wrapper = document.getElementById('{{ $uniqueId }}');
        
        // Safety check: jika wrapper gak ketemu, stop biar gak error di console
        if (!wrapper) return; 

        const input = wrapper.querySelector('.searchInput');
        const clearBtn = wrapper.querySelector('.clearSearchBtn');
        const targetContainer = document.getElementById('{{ $target }}');
        const formFilter = document.getElementById('{{ $formId }}');

        let debounceTimer;

        // 1. Logic Tombol Clear
        function toggleClearBtn() {
            if (input.value.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        // 2. Logic Fetch Data
        function performSearch() {
            // Jika tidak ada target container, tidak usah fetch (hemat resource)
            if (!targetContainer) return;

            targetContainer.style.opacity = '0.5';

            const url = new URL(window.location.href);
            
            // Ambil data filter lain (seperti select option) jika ada
            if (formFilter) {
                const formData = new FormData(formFilter);
                for (const [key, value] of formData.entries()) {
                    // Jangan ambil 'search' dari form, karena kita akan set manual dari input value realtime
                    if (key !== 'search') {
                        url.searchParams.set(key, value);
                    }
                }
            }

            // FIX 2: Paksa set parameter 'search' dari input value saat ini
            // Ini untuk memastikan apa yang diketik user itu yang dikirim
            url.searchParams.set('search', input.value);
            // Reset page ke 1 saat searching agar hasil tidak kosong jika page > 1
            url.searchParams.set('page', 1);

            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('{{ $target }}');
                    
                    if(newContent) {
                        targetContainer.innerHTML = newContent.innerHTML;
                    }
                    
                    window.history.pushState({}, '', url);
                    targetContainer.style.opacity = '1';
                })
                .catch(err => {
                    console.error('Search Error:', err);
                    targetContainer.style.opacity = '1';
                });
        }

        // 3. Event Listeners
        input.addEventListener('input', function() {
            toggleClearBtn();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(performSearch, 500);
        });

        clearBtn.addEventListener('click', function() {
            input.value = '';
            toggleClearBtn();
            performSearch(); // Fetch ulang data kosong
            input.focus();
        });

        // Handler jika select filter berubah (Triggered from Parent Form)
        if (formFilter) {
            formFilter.addEventListener('change', function(e) {
                if (e.target !== input) {
                    performSearch();
                }
            });
            
            // Prevent Enter Key Submit
            formFilter.addEventListener('submit', function(e) {
                e.preventDefault();
                performSearch();
            });
        }

        // Init Check saat halaman pertama load (misal ada query param ?search=abc)
        toggleClearBtn();
    });
</script>
@endpush