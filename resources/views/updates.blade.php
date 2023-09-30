<x-app-layout>
    <x-slot:title>Actualizaciones | Nartag</x-slot>
	<div class="main__wrap updates">
        @if (showAds())
            @php
                $ad_2 = config('app.ads_2');
            @endphp
            @if ($ad_2)
                <div class="vealo">
                    {!! $ad_2 !!}
                </div>
            @endif
        @endif
		<div class="main__content">
            <div class="updates__top">
                <div class="page__title">
                    <a href="{{ route('web.index') }}" class="view__back">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"></path>
                        </svg>
                    </a>
                    <div class="view__text">
                        <h2>Actualizaciones</h2>
                    </div>
                </div>
            </div>
            <div class="manga pt-6">
                @if ($loop->isNotEmpty())
                    @php
                        $pagination = $loop->links('vendor.pagination.default');
                    @endphp
                    <div class="manga__list">
                        @foreach ($loop as $item)
                            <div class="manga__item">
                                <div class="manga__cover">
                                    <a href="{{ $item->url() }}" class="manga__link">
                                        <figure class="manga__image">
                                            <img data-src="{{ $item->cover() }}" alt="{{ $item->manga_name }}" class="lazy">
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
                                                <a href="{{ $item->demography->url() }}">{{ $item->demography->name }}</a>
                                            </div>
                                        @endif
                                        @if ($item->type)
                                            <div class="manga__type {{ $item->type->slug }}">
                                                <a href="{{ $item->type->url() }}">{{ $item->type->name }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <h4 class="manga__title">
                                    <a href="{{ $item->url() }}" class="manga__link">{{ $item->name }}</a>
                                </h4>
                                <div class="manga__chapters">
                                    @foreach ($item->latestMonthChapters as $item)
                                        <div class="chapter__item">
                                            <a href="{{ $item->url() }}">
                                                <span class="chapter__name">{{ Str::limit($item->name, 16); }}</span>
                                                <span class="chapter__date">
                                                    {{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                                                </span>
                                                <div class="chapter__float">
                                                    @if ($item->isChapterPremium())
                                                        @php
                                                            $user = Auth::user();
                                                        @endphp
                                                        <button class="chapter__premium{{ (isset($user) && $user->userBuyChapter($item->id))? ' paid' : ' buy' }}" data-id="{{ $item->id }}" data-price="{{ $item->price }}" data-tippy-content="{{ (isset($user) && $user->userBuyChapter($item->id))? ' Capítulo comprado' : ' Capítulo premium' }}">
                                                            @if (isset($user) && $user->userBuyChapter($item->id))
                                                                <svg baseProfile="tiny" height="24px" id="Layer_1" version="1.2" viewBox="0 0 24 24" width="24px" fill="white">
                                                                    <path d="M18,4c-2.206,0-4,1.795-4,4v3h-4v-1H7c-1.103,0-2,0.896-2,2v7c0,1.104,0.897,2,2,2h10c1.103,0,2-0.896,2-2v-7  c0-1.104-0.897-2-2-2h-1V8c0-1.104,0.897-2,2-2s2,0.896,2,2v3c0,0.553,0.448,1,1,1s1-0.447,1-1V8C22,5.795,20.206,4,18,4z   M12,18.299c-0.719,0-1.3-0.58-1.3-1.299s0.581-1.301,1.3-1.301s1.3,0.582,1.3,1.301S12.719,18.299,12,18.299z"/>
                                                                </svg>
                                                            @else
                                                                <svg baseProfile="tiny" height="24px" id="Layer_1" version="1.2" viewBox="0 0 24 24" width="24px" fill="white">
                                                                    <path d="M17,10h-1V8c0-2.205-1.794-4-4-4S8,5.795,8,8v2H7c-1.103,0-2,0.896-2,2v7c0,1.104,0.897,2,2,2h10c1.103,0,2-0.896,2-2v-7  C19,10.896,18.103,10,17,10z M12,18.299c-0.719,0-1.3-0.58-1.3-1.299s0.581-1.301,1.3-1.301s1.3,0.582,1.3,1.301  S12.719,18.299,12,18.299z M14,11h-4V8c0-1.104,0.897-2,2-2s2,0.896,2,2V11z"/>
                                                                </svg>
                                                            @endif
                                                        </button>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{ $pagination }}
                @else
                    <div class="empty">No hay elementos para mostrar</div>
                @endif
            </div>
        </div>
        @if (showAds())
            @php
                $ad_9 = config('app.ads_9');
            @endphp
            @if ($ad_9)
                <div class="vealo">
                    {!! $ad_9 !!}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>