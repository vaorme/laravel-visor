<div class="member__card">
    <a href="{{ $item->link() }}">
        <div class="card__cover{{ (!isset($item->profile->cover))? ' no__cover': null }}">
            @if (isset($item->profile->cover))
                <div class="cover__image lazy" data-bg="{{ asset($item->profile->cover) }}"></div>
            @endif
        </div>
        <div class="card__body">
            @if ($item->profile->getCountry())
                <div class="card__fly card__country" data-tippy-placement="top" data-tippy-content="{{ $item->profile->getCountry()->name }}">
                    <div class="card__icon">
                        <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 9.5C7.98125 9.5 8.39337 9.3285 8.73637 8.9855C9.07879 8.64308 9.25 8.23125 9.25 7.75C9.25 7.26875 9.07879 6.85663 8.73637 6.51363C8.39337 6.17121 7.98125 6 7.5 6C7.01875 6 6.60692 6.17121 6.2645 6.51363C5.9215 6.85663 5.75 7.26875 5.75 7.75C5.75 8.23125 5.9215 8.64308 6.2645 8.9855C6.60692 9.3285 7.01875 9.5 7.5 9.5ZM7.5 18.25C5.15208 16.2521 3.39858 14.3962 2.2395 12.6824C1.07983 10.9691 0.5 9.38333 0.5 7.925C0.5 5.7375 1.20379 3.99479 2.61138 2.69687C4.01838 1.39896 5.64792 0.75 7.5 0.75C9.35208 0.75 10.9816 1.39896 12.3886 2.69687C13.7962 3.99479 14.5 5.7375 14.5 7.925C14.5 9.38333 13.9205 10.9691 12.7614 12.6824C11.6017 14.3962 9.84792 16.2521 7.5 18.25Z" fill="white" fill-opacity="0.5"/>
                        </svg>                                
                    </div>
                    {{ $item->profile->getCountry()->code }}
                </div>
            @endif
            <div class="card__image">
                <img src="{{ asset('storage/'.$item->profile->avatar) }}">
            </div>
            @if ($item->profile->name)
                <h4 class="card__name">{{ $item->profile->name }}</h4>
            @endif
            <h6 class="card__username">{{ '@'.$item->username }}</h6>
            <div class="card__role{{ " ".$item->profile->getRole() }}">{{ $item->profile->getRole() }}</div>
        </div>
    </a>
</div>