<div class="flex justify-center mb-6 overflow-visible">
<div class="relative w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center overflow-visible">
    <!-- Ripple -->
    <span class="ripple animation-delay-0"></span>
    <span class="ripple animation-delay-200"></span>
    <span class="ripple animation-delay-400"></span>

    <!-- Icon SVG -->
    <svg class="relative z-10" width="35" height="35" viewBox="0 0 43 43" fill="none" xmlns="http://www.w3.org/2000/svg">
        {{ $slot }}
    </svg>
</div>
</div>
