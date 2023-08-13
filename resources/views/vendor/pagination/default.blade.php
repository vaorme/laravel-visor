@if ($paginator->hasPages())
    <nav class="paginator">
        <div class="paginator__results">
            {!! __('Showing') !!}
            <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
            {!! __('of') !!}
            <span class="fw-semibold">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        </div>

        <div class="paginator__links">
            <div class="link__navigation link__prev">
                {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                    <button class="page__link disabled" aria-disabled="true">
                        @lang('pagination.previous')
                    </button>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page__link">
                        @lang('pagination.previous')
                    </a>
                @endif
            </div>
            <ul class="pagination">
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <a class="page-item disabled" aria-disabled="true">{{ $element }}</a>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button class="page__link active" aria-current="page">{{ $page }}</span></button>
                            @else
                                <a href="{{ $url }}" class="page__link">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
            <div class="link__navigation link__next">
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a class="page__link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                @else
                    <button class="page__link disabled" aria-disabled="true">@lang('pagination.next')</button>
                @endif
            </div>
        </div>
    </nav>
@endif
