<x-app-layout>
    <x-slot:title>{{ $user->username }}</x-slot>
    @php
        $country = $user->profile->getCountry();
    @endphp
    <div class="main__wrap profile">
        @if ($user->profile->public_profile || $user->id == Auth::id())
            <div class="profile__cover{{ (!isset($user->profile->cover))? ' no__cover': null }}">
                @if (isset($user->profile->cover))
                    <div class="cover__image lazy" data-bg="{{ asset($user->profile->cover) }}"></div>
                @endif
            </div>
            <div class="profile__wrap">
                <div class="profile__card">
                    <div class="card__box">
                        <div class="card__content">
                            @if (isset($user->profile->coins))
                                <div class="card__fly card__money" data-tippy-placement="top" data-tippy-content="Monedas">
                                    <div class="card__icon">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 0.5C12.1423 0.5 15.5 3.85775 15.5 8C15.5 12.1423 12.1423 15.5 8 15.5C3.85775 15.5 0.5 12.1423 0.5 8C0.5 3.85775 3.85775 0.5 8 0.5ZM7.46975 5.348L5.348 7.46975C5.2074 7.6104 5.12841 7.80113 5.12841 8C5.12841 8.19887 5.2074 8.3896 5.348 8.53025L7.46975 10.652C7.6104 10.7926 7.80113 10.8716 8 10.8716C8.19887 10.8716 8.3896 10.7926 8.53025 10.652L10.652 8.53025C10.7926 8.3896 10.8716 8.19887 10.8716 8C10.8716 7.80113 10.7926 7.6104 10.652 7.46975L8.53025 5.348C8.3896 5.2074 8.19887 5.12841 8 5.12841C7.80113 5.12841 7.6104 5.2074 7.46975 5.348Z" fill="white" fill-opacity="0.5"/>
                                        </svg>                                                              
                                    </div>
                                    {{ $user->profile->coins }}
                                </div>
                            @endif
                            @if (isset($country))
                                <div class="card__fly card__country" data-tippy-placement="top" data-tippy-content="{{ $country->name }}">
                                    <div class="card__icon">
                                        <svg width="15" height="19" viewBox="0 0 15 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.5 9.5C7.98125 9.5 8.39337 9.3285 8.73637 8.9855C9.07879 8.64308 9.25 8.23125 9.25 7.75C9.25 7.26875 9.07879 6.85663 8.73637 6.51363C8.39337 6.17121 7.98125 6 7.5 6C7.01875 6 6.60692 6.17121 6.2645 6.51363C5.9215 6.85663 5.75 7.26875 5.75 7.75C5.75 8.23125 5.9215 8.64308 6.2645 8.9855C6.60692 9.3285 7.01875 9.5 7.5 9.5ZM7.5 18.25C5.15208 16.2521 3.39858 14.3962 2.2395 12.6824C1.07983 10.9691 0.5 9.38333 0.5 7.925C0.5 5.7375 1.20379 3.99479 2.61138 2.69687C4.01838 1.39896 5.64792 0.75 7.5 0.75C9.35208 0.75 10.9816 1.39896 12.3886 2.69687C13.7962 3.99479 14.5 5.7375 14.5 7.925C14.5 9.38333 13.9205 10.9691 12.7614 12.6824C11.6017 14.3962 9.84792 16.2521 7.5 18.25Z" fill="white" fill-opacity="0.5"/>
                                        </svg>                                
                                    </div>
                                    {{ $country->code }}
                                </div>
                            @endif
                            <div class="card__user">
                                <div class="user__avatar">
                                    <img src="{{ asset('storage/'.$user->profile->avatar) }}">
                                </div>
                                <div class="user__titles">
                                    @if ($user->profile->name)
                                        <h2 class="user__name">{{ $user->profile->name }}</h2>
                                    @endif
                                    @if ($user->username)
                                        <h4 class="user__username">{{ "@".$user->username }}</h4>
                                    @endif
                                    @if ($user->profile->message)
                                        <div class="user__message">
                                            <p>{{ $user->profile->message }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="user__role{{ " ".$user->profile->getRole() }}">{{ $user->profile->getRole() }}</div>
                                @if ($user->profile->redes)
                                    @php
                                        $redes = json_decode($user->profile->redes);
                                    @endphp
                                    <div class="user__socials">
                                        @foreach ($redes as $red)
                                            @php
                                                $parse = parse_url($red);
                                            @endphp
                                            <div class="social__item">
                                                <a href="{{ $red }}" class="social__link" target="_blank">
                                                    <img src="{{ getFavicon($red) }}" alt="">
                                                    @if (isset($parse['host']))
                                                        {{ $parse['host'] }}{{ (isset($parse['path']))? $parse['path'] : null }}
                                                    @endif
                                                    @if (!isset($parse['host']) && isset($parse['path']))
                                                        {{ $parse['path'] }}
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if (Auth::check() && Auth::id() == $user->id)
                                    <div class="user__buttons">
                                        <a href="{{ route('account.index') }}" class="button__item">Editar perfil</a>
                                    </div>                        
                                @endif
                            </div>
                        </div>
                    </div>
                    @php
                        $ad_7 = config('app.ads_7');
                    @endphp
                    @if ($ad_7)
                        <div class="vealo">
                            {!! $ad_7 !!}
                        </div>
                    @endif
                </div>
                <div class="profile__content manga">
                    <div class="profile__navbar">
                        <ul class="navbar__list">
                            @if (Auth::check())
                                <li class="navbar__item">
                                    <a href="{{ route('profile.index', [
                                        'username' => request()->route('username')
                                    ]) }}" class="navbar__link{{ (request()->route('item') == null)? ' active' : null }}">Ultimos capitulos <span class="count">Semana</span></a>
                                </li>
                            @endif
                            <li class="navbar__item">
                                <a href="{{ route('profile.index', [
                                    'username' => request()->route('username'),
                                    'item' => 'siguiendo'
                                ]) }}" class="navbar__link{{ ((request()->route('item') && request()->route('item') == 'siguiendo') || (is_null(request()->route('item')) && !Auth::check()))? ' active': null }}">
                                    Siguiendo
                                    @if (isset($user->followedMangas))
                                        <span class="count">{{ $user->followedMangas->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="navbar__item">
                                <a href="{{ route('profile.index', [
                                    'username' => request()->route('username'),
                                    'item' => 'favoritos'
                                ]) }}" class="navbar__link{{ (request()->route('item') && request()->route('item') == 'favoritos')? ' active' : null }}">
                                    Favoritos
                                    @if (isset($user->favoriteMangas))
                                        <span class="count">{{ $user->favoriteMangas->count() }}</span>
                                    @endif
                                </a>
                            </li>
                            @if (Auth::check())
                                <li class="navbar__item">
                                    <a href="{{ route('profile.index', [
                                        'username' => request()->route('username'),
                                        'item' => 'atajos'
                                    ]) }}" class="navbar__link {{ (request()->route('item') && request()->route('item') == 'atajos')? ' active' : null }}">Editar atajos</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @php
                        $ad_8 = config('app.ads_8');
                    @endphp
                    @if ($ad_8)
                        <div class="vealo">
                            {!! $ad_8 !!}
                        </div>
                    @endif
                    @if (isset($page) || !Auth::check())
                        @if (!isset($page) || $page == "siguiendo" || $page == "favoritos" || ($page == "atajos" && Auth::check()))
                            @if (isset($manga) && $manga->isNotEmpty())
                                @php
                                    $pagination = $manga->links('vendor.pagination.default');
                                @endphp
                                <div class="manga__list">
                                    @foreach ($manga as $item)
                                        <div class="manga__item" id="shortcut-p-{{ $item->id }}">
                                            <div class="manga__cover">
                                                <a href="{{ $item->url() }}" class="manga__link">
                                                    <figure class="manga__image">
                                                        <img data-src="{{ $item->cover() }}" alt="{{ $item->manga_name }}" class="lazy">
                                                    </figure>
                                                </a>
                                                @if ($page == "atajos")
                                                    <button class="shortcut__remove" data-manga-id="{{ $item->id }}" data-user-id="{{ $user->id }}">Remover</button>
                                                @else
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
                                                @endif
                                            </div>
                                            <h4 class="manga__title">
                                                <a href="{{ $item->url() }}" class="manga__link">{{ $item->name }}</a>
                                            </h4>
                                        </div>
                                    @endforeach
                                </div>
                                {{ $pagination }}
                            @else
                                <div class="empty">No hay elementos para mostrar</div>
                            @endif
                        @endif
                    @else
                        @if (isset($manga) && $manga->isNotEmpty())
                            @php
                                $pagination = $manga->links('vendor.pagination.default');
                            @endphp
                            <div class="new__chapters">
                                @foreach ($manga as $item)
                                    @if (!isset($item->lastChapter))
                                        @continue
                                    @endif
                                    <div class="new__chapters__item">
                                        <a href="{{ $item->lastChapter->url() }}" class="new__chapters__link">
                                            <figure class="new__chapters__image">
                                                @php
                                                    $base64 = asset('storage/images/error-loading-image.png');
                                                    if (Storage::disk('public')->exists($item->featured_image)) {
                                                        $pathImage = 'storage/'.$item->featured_image;
                                                        $imageExtension = pathinfo($pathImage)["extension"];
                                                        $img = ManipulateImage::cache(function($image) use ($item) {
                                                            return $image->make('storage/'.$item->featured_image)->fit(80, 68);
                                                        }, 10, true);

                                                        $img->response($imageExtension, 70);
                                                        $base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
                                                    }
                                                    
                                                @endphp
                                                <img src="{!! $base64 !!}" alt="{{ $item->name }}">
                                            </figure>
                                            <div class="new__chapters__group">
                                                <div class="new__chapters__content">
                                                    <h6>{{ $item->name }}</h6>
                                                    <span class="new__chapters__chapter">{{ $item->lastChapter->name }}</span>
                                                    <span class="new__chapters__date">{{ Carbon\Carbon::parse($item->lastChapter->created_at)->diffForHumans()}}</span>
                                                </div>
                                                <div class="new__chapters__icon">
                                                    <i class="fa-solid fa-book-open"></i>									
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            {{ $pagination }}
                        @else
                            <div class="empty">No hay elementos para mostrar</div>
                        @endif
                    @endif
                </div>
            </div>
        @else
            <div class="private__profile">
                <div class="profile__image">
                    <img src="{{ asset('storage/images/private-profile.jpg') }}" alt="Perfil privado" />
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
