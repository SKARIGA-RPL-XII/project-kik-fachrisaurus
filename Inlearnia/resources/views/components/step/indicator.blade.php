@props(['active'])

<div class="flex justify-center gap-3 mt-8">
@for ($i = 1; $i <= 4; $i++)
    <span class="w-2.5 h-2.5 rounded-full
        {{ $i === $active ? 'bg-teal-400' : 'bg-white/30' }}">
    </span>
@endfor
</div>
