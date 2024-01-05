@if ($paginator->hasPages())
    <nav>
        <ul class="pagination" style="display: flex; list-style: none; margin-right: -10px;">

            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true"
                        style="background-color: white; padding: 10px 12px; font-size: 16px; color: blue; text-decoration: none; margin-right: 3px;">＜</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        aria-label="@lang('pagination.previous')"
                        style="background-color: white; padding: 10px 12px; font-size: 16px; color: blue; text-decoration: none; margin-right: 3px;">＜</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true"
                            style="background-color: white; padding: 10px 12px; font-size: 16px; color: blue; text-decoration: none; margin-right: 3px;">...</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link current-page"
                                    style="background-color: blue; color: white; padding: 10px 12px; font-size: 16px; margin-right: 3px;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}"
                                    style="background-color: white; padding: 10px 12px; font-size: 16px; color: blue; text-decoration: none; margin-right: 3px;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        aria-label="@lang('pagination.next')"
                        style="background-color: white; padding: 10px 12px; font-size: 16px; color: blue; text-decoration: none; margin-right: 3px;">＞</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" aria-hidden="true"
                        style="background-color: white; padding: 10px 12px; font-size: 16px; color: blue; text-decoration: none;">＞</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
