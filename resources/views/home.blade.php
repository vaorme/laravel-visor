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
			<section class="section most__viewed">
				<div class="section__title">
					<h2><span>mes</span> Más vistos</h2>
				</div>
				<div class="section__content">
					<div class="manga">
						@if ($mostViewed)
							<div class="manga__list">
								@foreach ($mostViewed as $item)
									<x-manga-loop-item :item="$item" />
								@endforeach
							</div>
						@else
							<div class="empty">No hay elementos para mostrar</div>
						@endif
					</div>
				</div>
			</section>
			@if ($categories->isNotEmpty())
				<div class="home__categories">
					@foreach ($categories as $category)
						<section class="section section__cat">
							<div class="section__title">
								<h2>{{ $category->name }}</h2>
							</div>
							<div class="section__content">
								<div class="manga">
									<div class="manga__list">
										@foreach ($category->mangas as $item)
											<x-manga-loop-item :item="$item" />
										@endforeach
									</div>
								</div>
							</div>
						</section>
					@endforeach
				</div>
			@endif
		</div>
		<aside class="main__sidebar">
			@if ($newChapterManga->isNotEmpty() || $newChapterNovel->isNotEmpty())
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
												$pathImage = 'storage/'.$item->manga->featured_image;
												$imageExtension = pathinfo($pathImage)["extension"];
												$img = ManipulateImage::cache(function($image) use ($item) {
													return $image->make('storage/'.$item->manga->featured_image)->fit(80, 68);
												}, 10, true);

												$img->response($imageExtension, 70);
												$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
												
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
			@endif
			@if ($newChapterNovel->isNotEmpty())
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
												$pathImage = 'storage/'.$item->manga->featured_image;
												$imageExtension = pathinfo($pathImage)["extension"];
												$img = ManipulateImage::cache(function($image) use ($item) {
													return $image->make('storage/'.$item->manga->featured_image)->fit(80, 68);
												}, 10, true);

												$img->response($imageExtension, 70);
												$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
												
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
			@endif
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
												$pathImage = 'storage/'.$item->featured_image;
												$imageExtension = pathinfo($pathImage)["extension"];
												$img = ManipulateImage::cache(function($image) use ($item) {
													return $image->make('storage/'.$item->featured_image)->fit(60, 54);
												}, 10, true);

												$img->response($imageExtension, 70);
												$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
												
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