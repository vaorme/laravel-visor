<x-app-layout>
	@if (Session::has('success'))
		<div class="log__alert success">
			<div class="box">
				<p>{!! \Session::get('success') !!}</p>
			</div>
		</div>
		<script>
			let alerta = document.querySelector('.log__alert');
			setTimeout(() => {
				alerta.remove();
			}, 6000);
		</script>
	@endif
	<div class="main__wrap main__home">
		<div class="main__content">
			@if ($slider->isNotEmpty())
				<section class="section home__slider">
					<div class="slide__box">
						<div class="swiper home__swiper">
							<div class="swiper-wrapper">
								@foreach ($slider as $item)
									@php
										$rating = round($item->manga->rating->avg('rating'), 0, PHP_ROUND_HALF_DOWN);
									@endphp
									<div class="swiper-slide">
										<div class="slide__image lazy" data-bg="{{ asset('storage/'.$item->background) }}"></div>
										<div class="slide__content">
											<div class="content__box">
												<div class="slide__logo">
													<a href="{{ $item->manga->url() }}">
														@if (!empty($item->logo))
															<img src="{{ asset('storage/'.$item->logo) }}" alt="{{ $item->manga->name }}">
														@else
															<span class="slide__name">{{ $item->manga->name }}</span>
														@endif
													</a>
												</div>
												@if ($rating != 0)
													<div class="manga__rating">
														<div class="rating__group">
															<input disabled="" class="rating__input rating__input--none" value="0" type="radio">
															<label aria-label="1 star" class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
															<input class="rating__input" value="1" type="radio" {{ ($rating == 1)? 'checked': null }}>
															<label aria-label="2 stars" class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
															<input class="rating__input" value="2" type="radio" {{ ($rating == 2)? 'checked': null }}>
															<label aria-label="3 stars" class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
															<input class="rating__input" value="3" type="radio" {{ ($rating == 3)? 'checked': null }}>
															<label aria-label="4 stars" class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
															<input class="rating__input" value="4" type="radio" {{ ($rating == 4)? 'checked': null }}>
															<label aria-label="5 stars" class="rating__label"><i class="rating__icon rating__icon--star fa fa-star"></i></label>
															<input class="rating__input" value="5" type="radio" {{ ($rating == 5)? 'checked': null }}>
														</div>
													</div>
												@endif
												<div class="slide__description">
													<p>{{ $item->description }}</p>
												</div>
												<div class="slide__buttons">
													<a href="{{ $item->manga->url() }}">Leer ahora</a>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
							<div class="swiper-pagination"></div>
							<div class="swiper-button-prev"><i class="fa-solid fa-chevron-left"></i></div>
							<div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
						</div>
					</div>
				</section>
			@endif
			@php
				$ad_2 = config('app.ads_2');
			@endphp
			@if ($ad_2)
				<div class="vealo">
					{!! $ad_2 !!}
				</div>
			@endif
			<section class="section updates">
				<div class="section__title">
					<h2><span>semana</span> Actualizaciones</h2>
				</div>
				<div class="section__content">
					<div class="manga">
						@if ($newChapters)
							<div class="manga__list">
								@foreach ($newChapters as $item)
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
											@foreach ($item->latestChapters as $item)
												<div class="chapter__item">
													<a href="{{ $item->url() }}">
														<span class="chapter__name">{{ Str::limit($item->name, 16); }}</span>
														<span class="chapter__date">
															{{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
														</span>
													</a>
												</div>
											@endforeach
										</div>
									</div>
								@endforeach
							</div>
						@else
							<div class="empty">No hay elementos para mostrar</div>
						@endif
					</div>
				</div>
			</section>
		</div>
		<aside class="main__sidebar">
			<div class="section buttons">
				<a href="{{ route('members.index') }}">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
						<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
						<path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
						<path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
						<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
						<path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
					</svg>
					<span class="button__text">Usuarios</span>
				</a>
			</div>
			@if ($mostViewed->isNotEmpty())
				<h2 class="sidebar__title"><span>Mes</span> Más vistos</h2>
				<section class="section new_manga">
					<div class="section__content">
						<div class="new__chapters">
							@foreach ($mostViewed as $item)
								<div class="new__chapters__item">
									<a href="{{ $item->url() }}" class="new__chapters__link">
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
												<span class="new__chapters__chapter">{{ parse_numbers_count($item->view_count) }}</span>
											</div>
											<div class="new__chapters__icon">
												<i class="fa-solid fa-book-open"></i>
											</div>
										</div>
									</a>
								</div>
							@endforeach
						</div>
					</div>
				</section>
			@endif
			{{-- @if ($newChapterManga->isNotEmpty() || $newChapterNovel->isNotEmpty())
				<h2 class="sidebar__title"><span>Semana</span> Nuevos capítulos</h2>
			@endif
			@if ($newChapterManga->isNotEmpty())
				<section class="section new_manga">
					<div class="section__title">
						<div class="section__type">Manga</div>
					</div>
					<div class="section__content">
						<div class="new__chapters">
							@foreach ($newChapterManga as $item)
								<div class="new__chapters__item">
									<a href="{{ $item->url() }}" class="new__chapters__link">
										<figure class="new__chapters__image">
											@php
												$base64 = asset('storage/images/error-loading-image.png');
												if (Storage::disk($item->disk)->exists($item->manga->featured_image)) {
													$pathImage = 'storage/'.$item->manga->featured_image;
													$imageExtension = pathinfo($pathImage)["extension"];
													$img = ManipulateImage::cache(function($image) use ($item) {
														return $image->make('storage/'.$item->manga->featured_image)->fit(80, 68);
													}, 10, true);

													$img->response($imageExtension, 70);
													$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
												}
											@endphp
											<img src="{!! $base64 !!}" alt="{{ $item->manga->name }}">
										</figure>
										<div class="new__chapters__group">
											<div class="new__chapters__content">
												<h6>{{ $item->name }}</h6>
												<span class="new__chapters__chapter">{{ $item->manga->name }}</span>
												<span class="new__chapters__date">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</span>
											</div>
											<div class="new__chapters__icon">
												<i class="fa-solid fa-book-open"></i>
											</div>
										</div>
									</a>
								</div>
							@endforeach
						</div>
					</div>
				</section>
			@endif --}}
			{{-- @if ($newChapterNovel->isNotEmpty())
				<section class="section new_novels">
					<div class="section__title">
						<div class="section__type">Novelas</div>
					</div>
					<div class="section__content">
						<div class="new__chapters">
							@foreach ($newChapterNovel as $item)
								<div class="new__chapters__item">
									<a href="{{ $item->url() }}" class="new__chapters__link">
										<figure class="new__chapters__image">
											@php
												$base64 = asset('storage/images/error-loading-image.png');
												if (Storage::disk($item->disk)->exists($item->manga->featured_image)) {
													$pathImage = 'storage/'.$item->manga->featured_image;
													$imageExtension = pathinfo($pathImage)["extension"];
													$img = ManipulateImage::cache(function($image) use ($item) {
														return $image->make('storage/'.$item->manga->featured_image)->fit(80, 68);
													}, 10, true);

													$img->response($imageExtension, 70);
													$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
												}
											@endphp
											<img src="{!! $base64 !!}" alt="{{ $item->manga->name }}">
										</figure>
										<div class="new__chapters__group">
											<div class="new__chapters__content">
												<h6>{{ $item->name }}</h6>
												<span class="new__chapters__chapter">{{ $item->manga->name }}</span>
												<span class="new__chapters__date">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</span>
											</div>
											<div class="new__chapters__icon">
												<i class="fa-solid fa-book-open"></i>
											</div>
										</div>
									</a>
								</div>
							@endforeach
						</div>
					</div>
				</section>
			@endif --}}
			@php
				$ad_1 = config('app.ads_1');
			@endphp
			@if ($ad_1)
				<div class="vealo">
					{!! $ad_1 !!}
				</div>
			@endif
			@if ($topmonth->isNotEmpty())
				<section class="section tops">
					<h2 class="sidebar__title"><span>TOP</span> Mensual</h2>
					<div class="section__content">
						<ul class="tops__list">
							@foreach ($topmonth as $key => $item)
								@php
									$count = ($key > 8)? ($key + 1) : '0'.($key + 1);
								@endphp
								<li class="list__item">
									<a href="{{ $item->url() }}">
										<figure class="item__image">
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
											<img src="{!! $base64 !!}" alt="{{ $item->manga_name }}">
											<div class="item__count">{{ $count }}</div>
										</figure>
										<div class="item__info">
											<div class="item__name">
												{{ Str::limit($item->name, 28); }}
											</div>
											<div class="item__rate">
												<i class="fa-solid fa-star"></i>
												<span class="rate__count">{{ round($item->month_rating_avg_rating, 1, PHP_ROUND_HALF_DOWN) }}</span>
											</div>
										</div>
									</a>
								</li>
							@endforeach
						</ul>
						<button class="tops__viewmore">Ver más</button>
					</div>
				</section>
			@endif
			@php
				$ad_3 = config('app.ads_3');
			@endphp
			@if ($ad_3)
				<div class="vealo">
					{!! $ad_3 !!}
				</div>
			@endif
		</aside>
	</div>
</x-app-layout>