<aside
    class="fixed top-[20px] left-[20px] bottom-[20px] w-[300px] bg-white rounded-[15px] flex flex-col z-50 shadow-sm border border-slate-100 overflow-hidden font-sans">

    {{-- INITIALIZE ROLE --}}
    @php
        $user = auth()->user();
        $role = $user->role; // 'admin', 'teacher', or 'student'
        
        // Tentukan route dashboard berdasarkan role
        $dashboardRoute = route($role . '.dashboard');
    @endphp

    {{-- 1. Logo Section --}}
    <div class="pt-[20px] pl-[20px] mb-[55px]">
        {{-- Logo mengarah ke dashboard sesuai role --}}
        <a href="{{ $dashboardRoute }}">
            <img src="{{ asset('assets/img/inlearnia-logo.png') }}" alt="Inlearnia Logo"
                style="width: 170px; height: 36px;" class="object-contain">
        </a>
    </div>

    {{-- 2. Menu Section --}}
    <nav class="flex-1 flex flex-col gap-[30px] overflow-y-auto">

        {{-- Group: Beranda (SEMUA ROLE) --}}
        <div>
            <p class="px-[20px] text-[14px] font-semibold text-slate-700 mb-[15px]">Beranda</p>
            <div class="pl-[20px]">
                @php $isActive = request()->routeIs($role . '.dashboard'); @endphp
                <a href="{{ $dashboardRoute }}"
                    class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                    {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12.9883 2.29755C13.3009 1.9851 13.7247 1.80957 14.1667 1.80957C14.6086 1.80957 15.0325 1.9851 15.345 2.29755L17.7025 4.65505C18.015 4.96759 18.1905 5.39144 18.1905 5.83338C18.1905 6.27532 18.015 6.69917 18.015 6.69917L15.345 9.36922C15.0325 9.68167 14.6086 9.85719 14.1667 9.85719C13.7247 9.85719 13.3009 9.68167 12.9883 9.36922L10.6308 7.01171C10.3184 6.69917 10.1429 6.27532 10.1429 5.83338C10.1429 5.39144 10.3184 4.96759 10.6308 4.65505L12.9883 2.29755ZM7.5 2.50005C7.94203 2.50005 8.36595 2.67564 8.67851 2.9882C8.99107 3.30076 9.16667 3.72469 9.16667 4.16671V7.50005C9.16667 7.94208 8.99107 8.366 8.67851 8.67856C8.36595 8.99112 7.94203 9.16672 7.5 9.16672H4.16667C3.72464 9.16672 3.30072 8.99112 2.98816 8.67856C2.67559 8.366 2.5 7.94208 2.5 7.50005V4.16671C2.5 3.72469 2.67559 3.30076 2.98816 2.9882C3.30072 2.67564 3.72464 2.50005 4.16667 2.50005H7.5ZM17.5 12.5C17.5 12.058 17.3244 11.6341 17.0118 11.3215C16.6993 11.009 16.2754 10.8334 15.8333 10.8334H12.5C12.058 10.8334 11.6341 11.009 11.3215 11.3215C11.0089 11.6341 10.8333 12.058 10.8333 12.5V15.8334C10.8333 16.2754 11.0089 16.6993 11.3215 17.0119C11.6341 17.3245 12.058 17.5 12.5 17.5H15.8333C16.2754 17.5 16.6993 17.3245 17.0118 17.0119C17.3244 16.6993 17.5 16.2754 17.5 15.8334V12.5ZM7.5 10.8334C7.94203 10.8334 8.36595 11.009 8.67851 11.3215C8.99107 11.6341 9.16667 12.058 9.16667 12.5V15.8334C9.16667 16.2754 8.99107 16.6993 8.67851 17.0119C8.36595 17.3245 7.94203 17.5 7.5 17.5H4.16667C3.72464 17.5 3.30072 17.3245 2.98816 17.0119C2.67559 16.6993 2.5 16.2754 2.5 15.8334V12.5C2.5 12.058 2.67559 11.6341 2.98816 11.3215C3.30072 11.009 3.72464 10.8334 4.16667 10.8334H7.5Z"
                            fill="{{ $isActive ? '#FFFFFF' : 'currentColor' }}"
                            class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                    </svg>
                    <span class="text-[14px] font-medium">Beranda</span>
                </a>
            </div>
            <div class="mt-[30px] px-[20px]">
                <hr class="border-t border-[#D9D9D9]">
            </div>
        </div>

        {{-- Group: Pengelolaan (KHUSUS ADMIN) --}}
        @if ($role === 'admin')
            <div>
                <p class="px-[20px] text-[14px] font-semibold text-slate-700 mb-[15px]">Pengelolaan</p>
                <div class="pl-[20px] space-y-4">
                    {{-- List Kelas (Admin CRUD) --}}
                    @php $isActive = request()->routeIs('admin.kelas.*'); @endphp
                    <a href="{{ route('admin.kelas.index') }}"
                        class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                    {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M19.1667 1.66699H0.833333C0.61232 1.66699 0.400358 1.75479 0.244078 1.91107C0.0877974 2.06735 0 2.27931 0 2.50033L0 17.5003C0 17.7213 0.0877974 17.9333 0.244078 18.0896C0.400358 18.2459 0.61232 18.3337 0.833333 18.3337H19.1667C19.3877 18.3337 19.5996 18.2459 19.7559 18.0896C19.9122 17.9333 20 17.7213 20 17.5003V2.50033C20 2.27931 19.9122 2.06735 19.7559 1.91107C19.5996 1.75479 19.3877 1.66699 19.1667 1.66699ZM18.3333 16.667H16.6667V15.8337H12.5V16.667H1.66667V3.33366H18.3333V16.667ZM8.575 8.09199C8.575 7.71406 8.72513 7.3516 8.99237 7.08437C9.25961 6.81713 9.62207 6.66699 10 6.66699C10.7917 6.66699 11.425 7.30866 11.425 8.09199C11.425 8.88366 10.7917 9.52533 10 9.52533C9.20833 9.52533 8.575 8.88366 8.575 8.09199ZM4.75833 9.40866C4.75833 8.81699 5.24167 8.33366 5.83333 8.33366C6.11844 8.33366 6.39187 8.44692 6.59347 8.64852C6.79507 8.85012 6.90833 9.12355 6.90833 9.40866C6.90833 10.0003 6.425 10.4753 5.83333 10.4753C5.24167 10.4753 4.75833 10.0003 4.75833 9.40866ZM13.0917 9.40866C13.0917 9.12355 13.2049 8.85012 13.4065 8.64852C13.6081 8.44692 13.8816 8.33366 14.1667 8.33366C14.4518 8.33366 14.7252 8.44692 14.9268 8.64852C15.1284 8.85012 15.2417 9.12355 15.2417 9.40866C15.2417 10.0003 14.7583 10.4753 14.1667 10.4753C13.575 10.4753 13.0917 10.0003 13.0917 9.40866ZM16.6667 12.617V13.3337H3.33333V12.617C3.33333 11.8337 4.625 11.192 5.83333 11.192C6.29167 11.192 6.75833 11.2837 7.16667 11.442C7.79167 10.867 8.91667 10.4753 10 10.4753C11.0833 10.4753 12.2083 10.867 12.8333 11.442C13.2417 11.2837 13.7083 11.192 14.1667 11.192C15.375 11.192 16.6667 11.8337 16.6667 12.617Z"
                                fill="{{ $isActive ? '#FFFFFF' : '#5E5E5E' }}"
                                fill-opacity="{{ $isActive ? '0.8' : '0.5' }}" />
                        </svg>
                        <span class="text-[14px] font-medium">List Kelas</span>
                    </a>

                    {{-- List Mapel --}}
                    @php $isActive = request()->routeIs('admin.mapel.*'); @endphp
                    <a href="{{ route('admin.mapel.index') }}"
                        class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                    {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.8346 9.07533V7.51699C13.3388 7.3031 13.8546 7.14269 14.382 7.03574C14.9094 6.9288 15.463 6.87533 16.043 6.87533C16.4402 6.87533 16.8298 6.90588 17.2117 6.96699C17.5937 7.0281 17.968 7.10449 18.3346 7.19616V8.66283C17.968 8.52533 17.5973 8.42235 17.2227 8.35391C16.8481 8.28546 16.4549 8.25094 16.043 8.25033C15.4624 8.25033 14.9048 8.32305 14.3701 8.46849C13.8353 8.61394 13.3235 8.81621 12.8346 9.07533ZM12.8346 14.117V12.5587C13.3388 12.3448 13.8546 12.1844 14.382 12.0774C14.9094 11.9705 15.463 11.917 16.043 11.917C16.4402 11.917 16.8298 11.9475 17.2117 12.0087C17.5937 12.0698 17.968 12.1462 18.3346 12.2378V13.7045C17.968 13.567 17.5973 13.4637 17.2227 13.3947C16.8481 13.3256 16.4549 13.2914 16.043 13.292C15.4624 13.292 14.9048 13.3607 14.3701 13.4982C13.8353 13.6357 13.3235 13.842 12.8346 14.117ZM12.8346 11.5962V10.0378C13.3388 9.82394 13.8546 9.66352 14.382 9.55658C14.9094 9.44963 15.463 9.39616 16.043 9.39616C16.4402 9.39616 16.8298 9.42671 17.2117 9.48783C17.5937 9.54894 17.968 9.62533 18.3346 9.71699V11.1837C17.968 11.0462 17.5973 10.9429 17.2227 10.8738C16.8481 10.8048 16.4549 10.7705 16.043 10.7712C15.4624 10.7712 14.9048 10.8439 14.3701 10.9893C13.8353 11.1348 13.3235 11.337 12.8346 11.5962ZM5.95964 14.667C6.67769 14.667 7.3768 14.7474 8.05697 14.9081C8.73714 15.0688 9.41302 15.3093 10.0846 15.6295V6.60033C9.45825 6.23366 8.79366 5.95866 8.09089 5.77533C7.38811 5.59199 6.67769 5.50033 5.95964 5.50033C5.40964 5.50033 4.8633 5.5538 4.32064 5.66074C3.77797 5.76769 3.25486 5.9281 2.7513 6.14199V15.217C3.28602 15.0337 3.81708 14.8962 4.34447 14.8045C4.87186 14.7128 5.41025 14.667 5.95964 14.667ZM11.918 15.6295C12.5902 15.3087 13.2664 15.0682 13.9466 14.9081C14.6267 14.748 15.3255 14.6676 16.043 14.667C16.593 14.667 17.1317 14.7128 17.6591 14.8045C18.1864 14.8962 18.7172 15.0337 19.2513 15.217V6.14199C18.7471 5.9281 18.2237 5.76769 17.6811 5.66074C17.1384 5.5538 16.5924 5.50033 16.043 5.50033C15.3249 5.50033 14.6145 5.59199 13.9117 5.77533C13.2089 5.95866 12.5444 6.23366 11.918 6.60033V15.6295ZM11.0013 18.3337C10.268 17.7531 9.47353 17.3024 8.61797 16.9816C7.76241 16.6607 6.8763 16.5003 5.95964 16.5003C5.31797 16.5003 4.68791 16.5844 4.06947 16.7524C3.45102 16.9205 2.85886 17.1573 2.29297 17.4628C1.97214 17.6309 1.66291 17.6232 1.3653 17.4399C1.06769 17.2566 0.91858 16.9892 0.917969 16.6378V5.59199C0.917969 5.42394 0.960135 5.26352 1.04447 5.11074C1.1288 4.95796 1.25469 4.84338 1.42214 4.76699C2.12491 4.40033 2.85825 4.12533 3.62214 3.94199C4.38602 3.75866 5.16519 3.66699 5.95964 3.66699C6.84575 3.66699 7.71291 3.78158 8.56114 4.01074C9.40936 4.23991 10.2227 4.58366 11.0013 5.04199C11.7805 4.58366 12.5942 4.23991 13.4424 4.01074C14.2906 3.78158 15.1575 3.66699 16.043 3.66699C16.8374 3.66699 17.6166 3.75866 18.3805 3.94199C19.1444 4.12533 19.8777 4.40033 20.5805 4.76699C20.7485 4.84338 20.8747 4.95796 20.9591 5.11074C21.0434 5.26352 21.0852 5.42394 21.0846 5.59199V16.6378C21.0846 16.9892 20.9358 17.2566 20.6382 17.4399C20.3406 17.6232 20.0311 17.6309 19.7096 17.4628C19.1444 17.1573 18.5525 16.9205 17.9341 16.7524C17.3156 16.5844 16.6852 16.5003 16.043 16.5003C15.1263 16.5003 14.2402 16.6607 13.3846 16.9816C12.5291 17.3024 11.7346 17.7531 11.0013 18.3337Z"
                                fill="{{ $isActive ? '#FFFFFF' : '#5E5E5E' }}"
                                fill-opacity="{{ $isActive ? '0.8' : '0.5' }}" />
                        </svg>
                        <span class="text-[14px] font-medium">List Mapel</span>
                    </a>
                </div>
                <div class="mt-[30px] px-[20px]">
                    <hr class="border-t border-[#D9D9D9]">
                </div>
            </div>
        @endif

        {{-- Group: Akademik (KHUSUS TEACHER & STUDENT) --}}
        @if ($role !== 'admin')
            <div>
                <p class="px-[20px] text-[14px] font-semibold text-slate-700 mb-[15px]">Akademik</p>
                <div class="pl-[20px] space-y-4">

                    {{-- List Kelas (UBAH DISINI: Ganti "List Kelas" jadi "Kelas Saya") --}}
                    @php $kelasRoute = route($role . '.kelas.index'); @endphp
                    @php $isActive = request()->routeIs($role . '.kelas.*'); @endphp
                    
                    {{-- List Kelas / Kelas Saya (Ikon disamakan dengan Admin) --}}
                    @php $kelasRoute = route($role . '.kelas.index'); @endphp
                    @php $isActive = request()->routeIs($role . '.kelas.*'); @endphp
                    
                    <a href="{{ $kelasRoute }}"
                        class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                        {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            {{-- Path ini sekarang sama persis dengan milik Admin --}}
                            <path
                                d="M19.1667 1.66699H0.833333C0.61232 1.66699 0.400358 1.75479 0.244078 1.91107C0.0877974 2.06735 0 2.27931 0 2.50033L0 17.5003C0 17.7213 0.0877974 17.9333 0.244078 18.0896C0.400358 18.2459 0.61232 18.3337 0.833333 18.3337H19.1667C19.3877 18.3337 19.5996 18.2459 19.7559 18.0896C19.9122 17.9333 20 17.7213 20 17.5003V2.50033C20 2.27931 19.9122 2.06735 19.7559 1.91107C19.5996 1.75479 19.3877 1.66699 19.1667 1.66699ZM18.3333 16.667H16.6667V15.8337H12.5V16.667H1.66667V3.33366H18.3333V16.667ZM8.575 8.09199C8.575 7.71406 8.72513 7.3516 8.99237 7.08437C9.25961 6.81713 9.62207 6.66699 10 6.66699C10.7917 6.66699 11.425 7.30866 11.425 8.09199C11.425 8.88366 10.7917 9.52533 10 9.52533C9.20833 9.52533 8.575 8.88366 8.575 8.09199ZM4.75833 9.40866C4.75833 8.81699 5.24167 8.33366 5.83333 8.33366C6.11844 8.33366 6.39187 8.44692 6.59347 8.64852C6.79507 8.85012 6.90833 9.12355 6.90833 9.40866C6.90833 10.0003 6.425 10.4753 5.83333 10.4753C5.24167 10.4753 4.75833 10.0003 4.75833 9.40866ZM13.0917 9.40866C13.0917 9.12355 13.2049 8.85012 13.4065 8.64852C13.6081 8.44692 13.8816 8.33366 14.1667 8.33366C14.4518 8.33366 14.7252 8.44692 14.9268 8.64852C15.1284 8.85012 15.2417 9.12355 15.2417 9.40866C15.2417 10.0003 14.7583 10.4753 14.1667 10.4753C13.575 10.4753 13.0917 10.0003 13.0917 9.40866ZM16.6667 12.617V13.3337H3.33333V12.617C3.33333 11.8337 4.625 11.192 5.83333 11.192C6.29167 11.192 6.75833 11.2837 7.16667 11.442C7.79167 10.867 8.91667 10.4753 10 10.4753C11.0833 10.4753 12.2083 10.867 12.8333 11.442C13.2417 11.2837 13.7083 11.192 14.1667 11.192C15.375 11.192 16.6667 11.8337 16.6667 12.617Z"
                                fill="{{ $isActive ? '#FFFFFF' : '#5E5E5E' }}"
                                fill-opacity="{{ $isActive ? '0.8' : '0.5' }}" />
                        </svg>
                        <span class="text-[14px] font-medium">Kelas Saya</span>
                    </a>

                    {{-- Jadwal Saya --}}
                    @php $jadwalRoute = route($role . '.jadwal.index'); @endphp
                    @php $isActive = request()->routeIs($role . '.jadwal.*'); @endphp
                    
                    <a href="{{ $jadwalRoute }}"
                        class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M19 4H5C3.89543 4 3 4.89543 3 6V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V6C21 4.89543 20.1046 4 19 4Z"
                                stroke="{{ $isActive ? '#FFFFFF' : 'currentColor' }}" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                            <path d="M16 2V6" stroke="{{ $isActive ? '#FFFFFF' : 'currentColor' }}" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                            <path d="M8 2V6" stroke="{{ $isActive ? '#FFFFFF' : 'currentColor' }}" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                            <path d="M3 10H21" stroke="{{ $isActive ? '#FFFFFF' : 'currentColor' }}" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                        </svg>
                        <span class="text-[14px] font-medium">Jadwal Saya</span>
                    </a>
                </div>
                <div class="mt-[30px] px-[20px]">
                    <hr class="border-t border-[#D9D9D9]">
                </div>
            </div>
        @endif

        {{-- Group: Lainnya (SEMUA ROLE) --}}
        <div>
            <p class="px-[20px] text-[14px] font-semibold text-slate-700 mb-[15px]">Lainnya</p>
            <div class="pl-[20px] space-y-4">
                {{-- List Pengguna (HANYA ADMIN) --}}
                @if ($role === 'admin')
                    @php $isActive = request()->routeIs('admin.users.*'); @endphp
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                    {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                        <svg width="15" height="15" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.125 14.375H0.625C0.45924 14.375 0.300269 14.4408 0.183058 14.5581C0.065848 14.6753 0 14.8342 0 15L0 17.5C0 17.6658 0.065848 17.8247 0.183058 17.9419C0.300269 18.0592 0.45924 18.125 0.625 18.125H3.125C3.29076 18.125 3.44973 18.0592 3.56694 17.9419C3.68415 17.8247 3.75 17.6658 3.75 17.5V15C3.75 14.8342 3.68415 14.6753 3.56694 14.5581C3.44973 14.4408 3.29076 14.375 3.125 14.375ZM3.125 1.875H0.625C0.45924 1.875 0.300269 1.94085 0.183058 2.05806C0.065848 2.17527 0 2.33424 0 2.5L0 5C0 5.16576 0.065848 5.32473 0.183058 5.44194C0.300269 5.55915 0.45924 5.625 0.625 5.625H3.125C3.29076 5.625 3.44973 5.55915 3.56694 5.44194C3.68415 5.32473 3.75 5.16576 3.75 5V2.5C3.75 2.33424 3.68415 2.17527 3.56694 2.05806C3.44973 1.94085 3.29076 1.875 3.125 1.875ZM3.125 8.125H0.625C0.45924 8.125 0.300269 8.19085 0.183058 8.30806C0.065848 8.42527 0 8.58424 0 8.75L0 11.25C0 11.4158 0.065848 11.5747 0.183058 11.6919C0.300269 11.8092 0.45924 11.875 0.625 11.875H3.125C3.29076 11.875 3.44973 11.8092 3.56694 11.6919C3.68415 11.5747 3.75 11.4158 3.75 11.25V8.75C3.75 8.58424 3.68415 8.42527 3.56694 8.30806C3.44973 8.19085 3.29076 8.125 3.125 8.125ZM19.375 15H6.875C6.70924 15 6.55027 15.0658 6.43306 15.1831C6.31585 15.3003 6.25 15.4592 6.25 15.625V16.875C6.25 17.0408 6.31585 17.1997 6.43306 17.3169C6.55027 17.4342 6.70924 17.5 6.875 17.5H19.375C19.5408 17.5 19.6997 17.4342 19.8169 17.3169C19.9342 17.1997 20 17.0408 20 16.875V15.625C20 15.4592 19.9342 15.3003 19.8169 15.1831C19.6997 15.0658 19.5408 15 19.375 15ZM19.375 2.5H6.875C6.70924 2.5 6.55027 2.56585 6.43306 2.68306C6.31585 2.80027 6.25 2.95924 6.25 3.125V4.375C6.25 4.54076 6.31585 4.69973 6.43306 4.81694C6.55027 4.93415 6.70924 5 6.875 5H19.375C19.5408 5 19.6997 4.93415 19.8169 4.81694C19.9342 4.69973 20 4.54076 20 4.375V3.125C20 2.95924 19.9342 2.80027 19.8169 2.68306C19.6997 2.56585 19.5408 2.5 19.375 2.5ZM19.375 8.75H6.875C6.70924 8.75 6.55027 8.81585 6.43306 8.93306C6.31585 9.05027 6.25 9.20924 6.25 9.375V10.625C6.25 10.7908 6.31585 10.9497 6.43306 11.0669C6.55027 11.1842 6.70924 11.25 6.875 11.25H19.375C19.5408 11.25 19.6997 11.1842 19.8169 11.0669C19.9342 10.9497 20 10.7908 20 10.625V9.375C20 9.20924 19.9342 9.05027 19.8169 8.93306C19.6997 8.81585 19.5408 8.75 19.375 8.75Z"
                                fill="{{ $isActive ? '#FFFFFF' : '#5E5E5E' }}"
                                fill-opacity="{{ $isActive ? '0.8' : '0.5' }}" />
                        </svg>
                        <span class="text-[14px] font-medium">List Pengguna</span>
                    </a>
                @endif

                {{-- Profil Saya (Saya perbaiki syntax error di bagian ini agar tidak error saat dicopy) --}}
                @php
                    $profileRoute = route($role . '.profile.index');
                    $isActive = request()->routeIs($role . '.profile.*');
                @endphp

                <a href="{{ $profileRoute }}"
                    class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 
                    {{ $isActive ? 'bg-[#044153] text-white' : 'text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]' }}">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z"
                            stroke="{{ $isActive ? '#FFFFFF' : 'currentColor' }}" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                        <path d="M20 21C20 18.2386 16.4183 16 12 16C7.58172 16 4 18.2386 4 21"
                            stroke="{{ $isActive ? '#FFFFFF' : 'currentColor' }}" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="{{ $isActive ? 'opacity-80' : 'opacity-50' }}" />
                    </svg>
                    <span class="text-[14px] font-medium">Profil Saya</span>
                </a>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}" id="logout-form" class="hidden">
                    @csrf
                </form>

                <a href="#" onclick="confirmLogout()"
                    class="flex items-center gap-3 w-[235px] h-[40px] px-[15px] rounded-[8px] transition-all duration-200 text-[#5E5E5E] hover:bg-[#DFDFDF] hover:text-[#044153]">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" class="opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="text-[14px] font-medium">Logout</span>
                </a>
            </div>
        </div>
    </nav>
</aside>

{{-- Script Logout tetap sama --}}
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Mau keluar?',
            text: "Kamu harus login lagi nanti untuk masuk.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#044153',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal',
            borderRadius: '15px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }
</script>