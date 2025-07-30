@if ($paginator->hasPages())
    <div class="flex items-center justify-between mt-4">
        {{-- Results Text --}}
        <div class="text-sm text-gray-700">
            Showing 
            <span class="font-medium">{{ $paginator->firstItem() }}</span> 
            to 
            <span class="font-medium">{{ $paginator->lastItem() }}</span> 
            of 
            <span class="font-medium">{{ $paginator->total() }}</span> 
            results
        </div>

        {{-- Pagination Buttons --}}
        <nav class="inline-flex -space-x-px rounded-md border border-gray-300 shadow-sm overflow-hidden">
            {{-- Prev Button --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1 text-sm text-gray-400 bg-white border-r border-gray-300 cursor-not-allowed">
                    &lsaquo;
                </span>
            @else
                <button wire:click="previousPage('{{ $paginator->getPageName() }}')" class="px-3 py-1 text-sm text-gray-600 bg-white hover:bg-gray-100 border-r border-gray-300">
                    &lsaquo;
                </button>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1 text-sm text-gray-400 bg-white border-r border-gray-300">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1 text-sm text-blue-600 bg-gray-100 font-semibold border-r border-gray-300">{{ $page }}</span>
                        @else
                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="px-3 py-1 text-sm text-gray-600 bg-white hover:bg-gray-100 border-r border-gray-300">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $paginator->getPageName() }}')" class="px-3 py-1 text-sm text-gray-600 bg-white hover:bg-gray-100">
                    &rsaquo;
                </button>
            @else
                <span class="px-3 py-1 text-sm text-gray-400 bg-white cursor-not-allowed">
                    &rsaquo;
                </span>
            @endif
        </nav>
    </div>
@endif
