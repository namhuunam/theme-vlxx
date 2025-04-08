@if ($paginator->hasPages())
    <div class="pagenavi">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        @else
            <a title="Prev Page" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                &laquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <a class="active" href="javascript:void(0)">{{ $page }}</a>
                    @else
                    <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
@endif
