@if ($paginator->hasPages())
    <nav role="navigation" class="flex items-center gap-2">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="w-9 h-9 flex items-center justify-center rounded-lg
                         border border-gray-200 text-gray-400 cursor-not-allowed">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg
                      border border-gray-200 text-gray-600
                      hover:bg-gray-100 transition">
                ‹
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)

            {{-- Dots --}}
            @if (is_string($element))
                <span class="px-2 text-gray-400">{{ $element }}</span>
            @endif

            {{-- Page Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-9 h-9 flex items-center justify-center
                                     rounded-lg bg-gray-200 text-gray-700 border border-gray-300">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="w-9 h-9 flex items-center justify-center
                                  rounded-lg border border-gray-200
                                  text-gray-600
                                  hover:bg-gray-100
                                  transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg
                      border border-gray-200 text-gray-600
                      hover:bg-gray-100 transition">
                ›
            </a>
        @else
            <span class="w-9 h-9 flex items-center justify-center rounded-lg
                         border border-gray-200 text-gray-400 cursor-not-allowed">
                ›
            </span>
        @endif

    </nav>
@endif
