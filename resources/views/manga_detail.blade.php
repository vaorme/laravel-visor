<x-app-layout>
    <x-slot:title>{{ $manga->name }}</x-slot>
	<div class="main__wrap manga__detail">
        <aside class="manga__sidebar">
            <div class="manga__chapters">
                <div class="chapters__head">
                    <div class="chapters__title">
                        <h3>Capítulos</h3>
                    </div>
                    <div class="chapters__next">
                        <span>Proximo capítulo</span>
                        <h4>24 de Junio, 2023</h4>
                    </div>
                </div>
                @if ($manga->chapters->isNotEmpty())
                    <div class="chapters__list">
                        @foreach ($manga->chapters as $chapter)
                            <div class="chapter__item">
                                <div class="chapter__name">
                                    <span class="chapter__date">{{ Carbon\Carbon::parse($chapter->created_at)->diffForHumans()}}</span>
                                    <h4 class="chapter__title">{{ Str::limit($chapter->name, 35); }}</h4>
                                </div>
                                <div class="chapter__actions">
                                    @if (Auth::check())
                                        <button class="action__view{{ in_array($chapter->id, $viewedChapters)? ' unview' : ' view' }}" data-id="{{ $chapter->id }}" data-tippy-content="{{ in_array($chapter->id, $viewedChapters)? ' Desmarcar como visto' : ' Marcar como visto' }}">
                                            @if (in_array($chapter->id, $viewedChapters))
                                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13.2825 14.2104L18.5666 19.4959C18.6276 19.5569 18.7001 19.6053 18.7798 19.6383C18.8595 19.6713 18.9449 19.6883 19.0312 19.6883C19.1175 19.6883 19.203 19.6713 19.2827 19.6383C19.3624 19.6053 19.4348 19.5569 19.4958 19.4959C19.5569 19.4349 19.6053 19.3624 19.6383 19.2827C19.6713 19.203 19.6883 19.1175 19.6883 19.0313C19.6883 18.945 19.6713 18.8595 19.6383 18.7798C19.6053 18.7001 19.5569 18.6276 19.4958 18.5666L2.43335 1.50413C2.37233 1.44311 2.2999 1.39471 2.22018 1.36169C2.14046 1.32867 2.05501 1.31168 1.96872 1.31168C1.88244 1.31168 1.79699 1.32867 1.71727 1.36169C1.63755 1.39471 1.56511 1.44311 1.5041 1.50413C1.44308 1.56515 1.39468 1.63758 1.36166 1.7173C1.32864 1.79702 1.31165 1.88247 1.31165 1.96876C1.31165 2.05504 1.32864 2.14049 1.36166 2.22021C1.39468 2.29993 1.44308 2.37236 1.5041 2.43338L5.74347 6.67276C5.16648 7.0815 4.64729 7.56625 4.19997 8.11388C3.56656 8.89109 3.07073 9.77089 2.73391 10.7153C2.70635 10.7953 2.67222 10.9056 2.67222 10.9056L2.6486 10.9869C2.6486 10.9869 2.54885 11.6393 3.11454 11.7889C3.28266 11.8333 3.46155 11.8092 3.61192 11.7218C3.76228 11.6345 3.87182 11.491 3.91647 11.3229L3.91779 11.319L3.92829 11.2862L3.97422 11.1431C4.26029 10.3458 4.67969 9.60288 5.21454 8.94601C5.63499 8.42922 6.13258 7.98033 6.68979 7.61513L8.76091 9.68626C8.34296 9.94821 7.98945 10.301 7.72667 10.7185C7.46389 11.1359 7.2986 11.6072 7.24309 12.0973C7.18759 12.5874 7.2433 13.0838 7.40608 13.5494C7.56886 14.015 7.83452 14.4379 8.18331 14.7867C8.53209 15.1355 8.95503 15.4012 9.42065 15.564C9.88627 15.7267 10.3826 15.7825 10.8727 15.7269C11.3629 15.6714 11.8342 15.5062 12.2516 15.2434C12.669 14.9806 13.0218 14.6271 13.2838 14.2091L13.2825 14.2104ZM8.29497 5.51119L9.41585 6.63207C9.77533 6.58536 10.1375 6.56213 10.5 6.56251C13.1827 6.56251 14.8128 7.75951 15.7867 8.94732C16.3217 9.60412 16.7411 10.3471 17.027 11.1444C17.0493 11.2074 17.0638 11.256 17.073 11.2875L17.0835 11.3203V11.3243L17.0848 11.3256C17.1333 11.4894 17.2439 11.6278 17.3929 11.7114C17.5419 11.795 17.7176 11.8171 17.8827 11.7732C18.0478 11.7293 18.1893 11.6227 18.277 11.4761C18.3648 11.3296 18.3919 11.1545 18.3527 10.9883V10.9843L18.3513 10.9791L18.3461 10.962C18.3234 10.8791 18.2971 10.7972 18.2673 10.7166C17.9305 9.77221 17.4347 8.8924 16.8013 8.11519C15.6253 6.67801 13.6447 5.25001 10.5026 5.25001C9.69147 5.25001 8.95779 5.3445 8.29629 5.51119H8.29497Z" fill="white"></path>
                                                </svg>
                                            @else
                                                <svg width="18" height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M2.29002 6.09833C2.21542 6.30201 2.06433 6.46858 1.86887 6.56263C1.67341 6.65669 1.44897 6.67082 1.24326 6.60203C1.03754 6.53324 0.866749 6.38695 0.767179 6.19424C0.667608 6.00153 0.647103 5.77759 0.710021 5.57C0.693355 5.62 0.710021 5.56833 0.710021 5.56833C0.739658 5.47657 0.774151 5.38644 0.813355 5.29833C0.880021 5.14 0.978355 4.92167 1.11335 4.66333C1.38835 4.14667 1.81669 3.45833 2.44835 2.77C3.72335 1.37833 5.81169 0 9.00002 0C12.1884 0 14.2767 1.37833 15.5517 2.77C16.2322 3.51663 16.7842 4.37101 17.185 5.29833L17.2617 5.48667C17.2667 5.5 17.2834 5.58667 17.3 5.67L17.3334 5.83333C17.3334 5.83333 17.4734 6.38833 16.7634 6.62333C16.5543 6.69316 16.326 6.67729 16.1286 6.57921C15.9312 6.48112 15.7807 6.30881 15.71 6.1V6.095L15.7 6.06833C15.6163 5.85423 15.5206 5.64501 15.4134 5.44167C15.117 4.88253 14.7507 4.36336 14.3234 3.89667C13.3067 2.78833 11.645 1.66667 9.00002 1.66667C6.35502 1.66667 4.69335 2.78833 3.67669 3.89667C3.12625 4.50012 2.67863 5.18985 2.35169 5.93833C2.3342 5.98135 2.31753 6.02469 2.30169 6.06833L2.29002 6.09833ZM5.66669 6.66667C5.66669 5.78261 6.01788 4.93476 6.643 4.30964C7.26812 3.68452 8.11597 3.33333 9.00002 3.33333C9.88408 3.33333 10.7319 3.68452 11.357 4.30964C11.9822 4.93476 12.3334 5.78261 12.3334 6.66667C12.3334 7.55072 11.9822 8.39857 11.357 9.02369C10.7319 9.64881 9.88408 10 9.00002 10C8.11597 10 7.26812 9.64881 6.643 9.02369C6.01788 8.39857 5.66669 7.55072 5.66669 6.66667Z" fill="#FFF"/>
                                                </svg>
                                            @endif
                                        </button>
                                    @endif
                                    <a href="#" class="action_download" data-tippy-content="Descargar capítulo">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.00008 1.41666V10.625M9.00008 10.625L12.2501 7.37499M9.00008 10.625L5.75008 7.37499M1.41675 12.25V14.4167C1.41675 14.9913 1.64502 15.5424 2.05135 15.9487C2.45768 16.3551 3.00878 16.5833 3.58341 16.5833H14.4167C14.9914 16.5833 15.5425 16.3551 15.9488 15.9487C16.3551 15.5424 16.5834 14.9913 16.5834 14.4167V12.25" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                    <a href="{{ $chapter->url() }}" class="action__viewer" data-tippy-content="Ver capítulo">
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.875 14.4164V2.58362C1.87494 2.38567 1.92711 2.19121 2.02625 2.01987C2.12538 1.84854 2.26797 1.70639 2.43961 1.60779C2.61125 1.50919 2.80587 1.45762 3.00382 1.45829C3.20177 1.45896 3.39603 1.51185 3.567 1.61162L13.7089 7.52912C13.8786 7.62823 14.0193 7.77007 14.1172 7.9405C14.215 8.11094 14.2665 8.30403 14.2665 8.50055C14.2665 8.69707 14.215 8.89016 14.1172 9.0606C14.0193 9.23104 13.8786 9.37288 13.7089 9.47199L3.567 15.3884C3.39603 15.4881 3.20177 15.541 3.00382 15.5417C2.80587 15.5424 2.61125 15.4908 2.43961 15.3922C2.26797 15.2936 2.12538 15.1514 2.02625 14.9801C1.92711 14.8088 1.87494 14.6143 1.875 14.4164Z" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>      
                @else
                    <div class="chapter_alert warning">no tiene capitulos</div>
                @endif
            </div>
        </aside>
        <section class="manga__contain">
            <section class="manga__card">
                <div class="manga__cover">
                    <img src="{{ asset('storage/'.$manga->featured_image) }}" alt="{{ $manga->name }}" />
                    <div class="manga__terms">
                        @if ($manga->demography)
                            <div class="manga__demography {{ $manga->demography->slug }}">
                                <a href="{{ $manga->demography->slug }}">{{ $manga->demography->name }}</a>
                            </div>
                        @endif
                        @if ($manga->type)
                            <div class="manga__type {{ $manga->type->slug }}">
                                <a href="{{ $manga->type->slug }}">{{ $manga->type->name }}</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="manga__info">
                    <div class="manga__title">
                        <h2>{{ $manga->name }}
                        @if ($manga->release_date)
                            <span class="manga__year">({{ date('Y', strtotime($manga->release_date)); }})</span>
                        @endif</h2>
                        <h4>{{ $manga->alternative_name }}</h4>
                        <div class="manga__rating">
                            @php
                                $rating = round($manga->rating->avg('rating'), 0, PHP_ROUND_HALF_DOWN);
                            @endphp
                            <div class="rating__group">
                                <input disabled="" class="rating__input rating__input--none" name="rating" id="rating3-none" value="0" type="radio" {{ ($rating == 0)? 'checked': null }}>
                                <label aria-label="1 star" class="rating__label" for="rating-1"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                <input class="rating__input" name="rating" id="rating-1" value="1" data-manga-id="{{ $manga->id }}" type="radio" {{ ($rating == 1)? 'checked': null }}>
                                <label aria-label="2 stars" class="rating__label" for="rating-2"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                <input class="rating__input" name="rating" id="rating-2" value="2" data-manga-id="{{ $manga->id }}" type="radio" {{ ($rating == 2)? 'checked': null }}>
                                <label aria-label="3 stars" class="rating__label" for="rating-3"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                <input class="rating__input" name="rating" id="rating-3" value="3" data-manga-id="{{ $manga->id }}" type="radio" {{ ($rating == 3)? 'checked': null }}>
                                <label aria-label="4 stars" class="rating__label" for="rating-4"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                <input class="rating__input" name="rating" id="rating-4" value="4" data-manga-id="{{ $manga->id }}" type="radio" {{ ($rating == 4)? 'checked': null }}>
                                <label aria-label="5 stars" class="rating__label" for="rating-5"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
                                <input class="rating__input" name="rating" id="rating-5" value="5" data-manga-id="{{ $manga->id }}" type="radio" {{ ($rating == 5)? 'checked': null }}>
                            </div>
                            <div class="rating__count">
                                {{ $manga->rating->avg('rating') }}
                                <div class="count__users">
                                    (<span class="users__num">{{ count($manga->rating) }}</span>)
                                </div>
                            </div>
                        </div>
                        @if (Auth::check())
                            <div class="manga__actions">
                                @if ($manga->userFollowManga)
                                    <button class="action__follow unfollow" data-tippy-content="Dejar de seguir" data-id="{{ $manga->id }}">Siguiendo</button>
                                @else
                                    <button class="action__follow follow" data-id="{{ $manga->id }}">Seguir</button>
                                @endif
                                @if ($mangaViewed)
                                    <button class="action__view unview" data-id="{{ $manga->id }}" data-tippy-content="Desmarcar como visto">
                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13.2825 14.2104L18.5666 19.4959C18.6276 19.5569 18.7001 19.6053 18.7798 19.6383C18.8595 19.6713 18.9449 19.6883 19.0312 19.6883C19.1175 19.6883 19.203 19.6713 19.2827 19.6383C19.3624 19.6053 19.4348 19.5569 19.4958 19.4959C19.5569 19.4349 19.6053 19.3624 19.6383 19.2827C19.6713 19.203 19.6883 19.1175 19.6883 19.0313C19.6883 18.945 19.6713 18.8595 19.6383 18.7798C19.6053 18.7001 19.5569 18.6276 19.4958 18.5666L2.43335 1.50413C2.37233 1.44311 2.2999 1.39471 2.22018 1.36169C2.14046 1.32867 2.05501 1.31168 1.96872 1.31168C1.88244 1.31168 1.79699 1.32867 1.71727 1.36169C1.63755 1.39471 1.56511 1.44311 1.5041 1.50413C1.44308 1.56515 1.39468 1.63758 1.36166 1.7173C1.32864 1.79702 1.31165 1.88247 1.31165 1.96876C1.31165 2.05504 1.32864 2.14049 1.36166 2.22021C1.39468 2.29993 1.44308 2.37236 1.5041 2.43338L5.74347 6.67276C5.16648 7.0815 4.64729 7.56625 4.19997 8.11388C3.56656 8.89109 3.07073 9.77089 2.73391 10.7153C2.70635 10.7953 2.67222 10.9056 2.67222 10.9056L2.6486 10.9869C2.6486 10.9869 2.54885 11.6393 3.11454 11.7889C3.28266 11.8333 3.46155 11.8092 3.61192 11.7218C3.76228 11.6345 3.87182 11.491 3.91647 11.3229L3.91779 11.319L3.92829 11.2862L3.97422 11.1431C4.26029 10.3458 4.67969 9.60288 5.21454 8.94601C5.63499 8.42922 6.13258 7.98033 6.68979 7.61513L8.76091 9.68626C8.34296 9.94821 7.98945 10.301 7.72667 10.7185C7.46389 11.1359 7.2986 11.6072 7.24309 12.0973C7.18759 12.5874 7.2433 13.0838 7.40608 13.5494C7.56886 14.015 7.83452 14.4379 8.18331 14.7867C8.53209 15.1355 8.95503 15.4012 9.42065 15.564C9.88627 15.7267 10.3826 15.7825 10.8727 15.7269C11.3629 15.6714 11.8342 15.5062 12.2516 15.2434C12.669 14.9806 13.0218 14.6271 13.2838 14.2091L13.2825 14.2104ZM8.29497 5.51119L9.41585 6.63207C9.77533 6.58536 10.1375 6.56213 10.5 6.56251C13.1827 6.56251 14.8128 7.75951 15.7867 8.94732C16.3217 9.60412 16.7411 10.3471 17.027 11.1444C17.0493 11.2074 17.0638 11.256 17.073 11.2875L17.0835 11.3203V11.3243L17.0848 11.3256C17.1333 11.4894 17.2439 11.6278 17.3929 11.7114C17.5419 11.795 17.7176 11.8171 17.8827 11.7732C18.0478 11.7293 18.1893 11.6227 18.277 11.4761C18.3648 11.3296 18.3919 11.1545 18.3527 10.9883V10.9843L18.3513 10.9791L18.3461 10.962C18.3234 10.8791 18.2971 10.7972 18.2673 10.7166C17.9305 9.77221 17.4347 8.8924 16.8013 8.11519C15.6253 6.67801 13.6447 5.25001 10.5026 5.25001C9.69147 5.25001 8.95779 5.3445 8.29629 5.51119H8.29497Z" fill="white"/>
                                        </svg>
                                    </button>
                                @else
                                    <button class="action__view view" data-id="{{ $manga->id }}" data-tippy-content="Marcar como visto">
                                        <svg width="18" height="10" viewBox="0 0 18 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2.29002 6.09833C2.21542 6.30201 2.06433 6.46858 1.86887 6.56263C1.67341 6.65669 1.44897 6.67082 1.24326 6.60203C1.03754 6.53324 0.866749 6.38695 0.767179 6.19424C0.667608 6.00153 0.647103 5.77759 0.710021 5.57C0.693355 5.62 0.710021 5.56833 0.710021 5.56833C0.739658 5.47657 0.774151 5.38644 0.813355 5.29833C0.880021 5.14 0.978355 4.92167 1.11335 4.66333C1.38835 4.14667 1.81669 3.45833 2.44835 2.77C3.72335 1.37833 5.81169 0 9.00002 0C12.1884 0 14.2767 1.37833 15.5517 2.77C16.2322 3.51663 16.7842 4.37101 17.185 5.29833L17.2617 5.48667C17.2667 5.5 17.2834 5.58667 17.3 5.67L17.3334 5.83333C17.3334 5.83333 17.4734 6.38833 16.7634 6.62333C16.5543 6.69316 16.326 6.67729 16.1286 6.57921C15.9312 6.48112 15.7807 6.30881 15.71 6.1V6.095L15.7 6.06833C15.6163 5.85423 15.5206 5.64501 15.4134 5.44167C15.117 4.88253 14.7507 4.36336 14.3234 3.89667C13.3067 2.78833 11.645 1.66667 9.00002 1.66667C6.35502 1.66667 4.69335 2.78833 3.67669 3.89667C3.12625 4.50012 2.67863 5.18985 2.35169 5.93833C2.3342 5.98135 2.31753 6.02469 2.30169 6.06833L2.29002 6.09833ZM5.66669 6.66667C5.66669 5.78261 6.01788 4.93476 6.643 4.30964C7.26812 3.68452 8.11597 3.33333 9.00002 3.33333C9.88408 3.33333 10.7319 3.68452 11.357 4.30964C11.9822 4.93476 12.3334 5.78261 12.3334 6.66667C12.3334 7.55072 11.9822 8.39857 11.357 9.02369C10.7319 9.64881 9.88408 10 9.00002 10C8.11597 10 7.26812 9.64881 6.643 9.02369C6.01788 8.39857 5.66669 7.55072 5.66669 6.66667Z" fill="#FFF"/>
                                        </svg>
                                    </button>
                                @endif
                                @if ($mangaFavorite)
                                    <button class="action__fav unfav" data-id="{{ $manga->id }}" data-tippy-content="Desmarcar como favorito">
                                        <svg width="18" height="10" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/>
                                        </svg>
                                    </button>
                                @else
                                    <button class="action__fav fav" data-id="{{ $manga->id }}" data-tippy-content="Agregar a favoritos">
                                        <svg width="18" height="10" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m11.322 2.923c.126-.259.39-.423.678-.423.289 0 .552.164.678.423.974 1.998 2.65 5.44 2.65 5.44s3.811.524 6.022.829c.403.055.65.396.65.747 0 .19-.072.383-.231.536-1.61 1.538-4.382 4.191-4.382 4.191s.677 3.767 1.069 5.952c.083.462-.275.882-.742.882-.122 0-.244-.029-.355-.089-1.968-1.048-5.359-2.851-5.359-2.851s-3.391 1.803-5.359 2.851c-.111.06-.234.089-.356.089-.465 0-.825-.421-.741-.882.393-2.185 1.07-5.952 1.07-5.952s-2.773-2.653-4.382-4.191c-.16-.153-.232-.346-.232-.535 0-.352.249-.694.651-.748 2.211-.305 6.021-.829 6.021-.829s1.677-3.442 2.65-5.44z" fill-rule="nonzero"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="manga__description">
                        <p>{{ $manga->description }}</p>
                    </div>
                    <div class="manga__extra">
                        <div class="manga__categories">
                            <h4>Géneros:</h4>
                            <div class="categories__list">
                                @foreach ($manga->categories as $category)
                                    <div class="category__item">
                                        <a href="{{ $category->slug }}" class="category__link">
                                            {{ $category->name }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @if ($manga->bookStatus)
                            <div class="manga__status">
                                <h4>Estado:</h4>
                                <span class="status__name{{ ($manga->bookStatus->slug)? " ".$manga->bookStatus->slug : null}}">{{ $manga->bookStatus->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
            <section class="manga__comments">
                
            </section>
        </section>
	</div>
</x-app-layout>