<footer class="footer">
    <div class="footer_col"></div>
    <div class="footer_col">
        <div class="footer__copy">
            <p>&copy; 2023 - Nartag</p>
        </div>
    </div>
    <div class="footer_col">
        <ul class="nav">
            <li>
                <a href="{{ URL::to('/politicas-privacidad') }}"class="block">Políticas de privacidad</a>   
            </li>
        </ul>
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