@php
    if (! isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp

<div class="mt-3">
    @if ($paginator->hasPages())
        <div class="flex flex-col items-center sm:flex-row sm:justify-between sm:items-center">
            <!-- Informasi Posisi Halaman -->
            <span class="text-sm text-zinc-700 dark:text-zinc-400">
                {!! __('Showing') !!}
                <span class="font-semibold text-zinc-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                -
                <span class="font-semibold text-zinc-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                {!! __('of') !!}
                <span class="font-semibold text-zinc-900 dark:text-white">{{ $paginator->total() }}</span>
                {!! __('Entries') !!}
            </span>

            <!-- Kontrol Pagination -->
            <nav aria-label="Page navigation example" class="mt-2 sm:mt-0">
                <ul class="inline-flex -space-x-px text-sm">

                    {{-- Tombol Sebelumnya --}}
                    <li>
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-zinc-500 bg-white border border-e-0 border-zinc-300 rounded-s-lg dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400">
                                    Sebelumnya
                                </span>
                            </span>
                        @else
                            <button type="button"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-zinc-500 bg-white border border-e-0 border-zinc-300 rounded-s-lg hover:bg-zinc-100 hover:text-zinc-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white">
                                Sebelumnya
                            </button>
                        @endif
                    </li>

                    {{-- Elemen Pagination --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li>
                                <span class="flex items-center justify-center px-3 h-8 leading-tight text-zinc-500 bg-white border border-zinc-300 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400">
                                    {{ $element }}
                                </span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                <li wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                    @if ($page == $paginator->currentPage())
                                        <span aria-current="page" class="flex items-center justify-center px-3 h-8 text-blue-600 border border-zinc-300 bg-blue-50 dark:border-zinc-700 dark:bg-zinc-700 dark:text-white">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <button type="button"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                                x-on:click="{{  $scrollIntoViewJsSnippet }}"
                                                aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                                class="flex items-center justify-center px-3 h-8 leading-tight text-zinc-500 bg-white border border-zinc-300 hover:bg-zinc-100 hover:text-zinc-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white">
                                            {{ $page }}
                                        </button>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Selanjutnya --}}
                    <li>
                        @if ($paginator->hasMorePages())
                            <button type="button"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    aria-label="{{ __('pagination.next') }}"
                                    class="flex items-center justify-center px-3 h-8 leading-tight text-zinc-500 bg-white border border-zinc-300 rounded-e-lg hover:bg-zinc-100 hover:text-zinc-700 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400 dark:hover:bg-zinc-700 dark:hover:text-white">
                                Selanjutnya
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span class="flex items-center justify-center px-3 h-8 leading-tight text-zinc-500 bg-white border border-zinc-300 rounded-e-lg dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-400">
                                    Selanjutnya
                                </span>
                            </span>
                        @endif
                    </li>
                </ul>
            </nav>
        </div>
    @endif
</div>

