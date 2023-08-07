<footer class="footer">
    <div class="footer__copy">
        <p>&copy; 2023 - Nartag</p>
    </div>
</footer>
@php
    $insertFooter = config('app.footer');
@endphp
@if ($insertFooter)
    {!! $insertFooter !!}
@endif