<footer class="footer">
    <div class="footer__copy">
        <p>&copy; 2023 - Nartag</p>
    </div>
</footer>
@if (!Route::is(['shop.index']))
    @if (showAds())
        @php
            $insertFooter = config('app.footer');
        @endphp
        @if ($insertFooter)
            <div class="fto vealo">
                {!! $insertFooter !!}
            </div>
        @endif
    @endif
@endif