@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-500 bg-white border border-zinc-200 cursor-default leading-5 rounded-md dark:text-zinc-400 dark:bg-zinc-800 dark:border-zinc-700">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-700 bg-white border border-zinc-200 leading-5 rounded-md hover:text-emerald-600 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-400 active:bg-emerald-50 active:text-emerald-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:text-emerald-400 dark:focus:border-emerald-600 dark:active:bg-zinc-700 dark:active:text-emerald-300">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-zinc-700 bg-white border border-zinc-200 leading-5 rounded-md hover:text-emerald-600 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-400 active:bg-emerald-50 active:text-emerald-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:text-emerald-400 dark:focus:border-emerald-600 dark:active:bg-zinc-700 dark:active:text-emerald-300">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-zinc-500 bg-white border border-zinc-200 cursor-default leading-5 rounded-md dark:text-zinc-400 dark:bg-zinc-800 dark:border-zinc-700">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-zinc-700 leading-5 dark:text-zinc-400">
                    {!! __('Showing') !!}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {!! __('to') !!}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {!! __('of') !!}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-zinc-500 bg-white border border-zinc-200 cursor-default rounded-l-md leading-5 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-l-md leading-5 hover:text-emerald-600 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-400 active:bg-emerald-50 active:text-emerald-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:text-emerald-400 dark:focus:border-emerald-600 dark:active:bg-zinc-700 dark:active:text-emerald-300" aria-label="{{ __('pagination.previous') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-zinc-700 bg-white border border-zinc-200 cursor-default leading-5 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-emerald-600 border border-emerald-600 cursor-default leading-5 dark:bg-emerald-700 dark:border-emerald-600">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-zinc-700 bg-white border border-zinc-200 leading-5 hover:text-emerald-600 hover:bg-emerald-50 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-400 active:bg-emerald-100 active:text-emerald-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:text-emerald-300 dark:hover:bg-zinc-700 dark:active:bg-zinc-700 dark:focus:border-emerald-600" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-zinc-600 bg-white border border-zinc-200 rounded-r-md leading-5 hover:text-emerald-600 focus:z-10 focus:outline-none focus:ring ring-emerald-300 focus:border-emerald-400 active:bg-emerald-50 active:text-emerald-700 transition ease-in-out duration-150 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-300 dark:hover:text-emerald-400 dark:focus:border-emerald-600 dark:active:bg-zinc-700 dark:active:text-emerald-300" aria-label="{{ __('pagination.next') }}">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-zinc-500 bg-white border border-zinc-200 cursor-default rounded-r-md leading-5 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400" aria-hidden="true">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
