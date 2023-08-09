<div class="manga__item">
    <div class="manga__cover">
        <a href="{{ $item->url() }}" class="manga__link">
            <figure class="manga__image">
                <img src="{{ $item->cover() }}" alt="{{ $item->manga_name }}">
            </figure>
        </a>
        @if ($item->rating->avg('rating'))
            <div class="manga__ratings">
                <i class="fa-solid fa-star"></i>
                <div class="rating__avg">{{ round($item->rating->avg('rating'), 1, PHP_ROUND_HALF_DOWN) }}</div>
            </div>
        @endif
        <div class="manga__terms">
            @if ($item->demography)
                <div class="manga__demography {{ $item->demography->slug }}">
                    <a href="/">{{ $item->demography->name }}</a>
                </div>
            @endif
            @if ($item->type)
                <div class="manga__type {{ $item->type->slug }}">
                    <a href="/">{{ $item->type->name }}</a>
                </div>
            @endif
        </div>
    </div>
    <h4 class="manga__title">
        <a href="{{ $item->url() }}" class="manga__link">{{ $item->name }}</a>
    </h4>
</div>