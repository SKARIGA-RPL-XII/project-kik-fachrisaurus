<div class="flex items-center">
    {{-- Foto Profil: 50x50px, Jarak kanan 10px --}}
    <div class="w-[50px] h-[50px] rounded-full overflow-hidden mr-[10px] border border-slate-100 shadow-sm">
        <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=044153&color=fff' }}" 
             alt="Profile" class="w-full h-full object-cover">
    </div>
    
    {{-- Nama & Email --}}
    <div>
        <h1 class="text-[18px] font-medium text-[#434343] leading-tight">
            Selamat datang, {{ auth()->user()->name }}
        </h1>
        <p class="text-[15px] font-light text-[#C9C9C9]">
            {{ auth()->user()->email }}
        </p>
    </div>
</div>