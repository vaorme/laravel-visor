<x-app-layout>
	<div class="main__content main__home">
		<section class="section">
			<div class="section__title">
				<h2>Nuevos capítulos</h2>
			</div>
			<div class="section__content">
				<div class="manga">
					<div class="manga__list">
						@foreach ($latestChapters['manga'] as $item)
							<div class="manga__item">
								<div class="manga__cover">
									<a href="{{ $item->slug }}" class="manga__link">
										<figure class="manga__image">
											<img src="{{ asset('storage/'.$item->featured_image) }}" alt="{{ $item->manga_name }}">
										</figure>
									</a>
									<div class="manga__terms">
										<div class="manga__type">
											<a href="{{ $item->manga_demography_slug }}">{{ $item->manga_demography_name }}</a>
										</div>
										<div class="manga__demography">
											<a href="{{ $item->manga_type_slug }}">{{ $item->manga_type_name }}</a>
										</div>
									</div>
								</div>
								<h4 class="manga__title">
									<a href="{{ $item->slug }}" class="manga__link">{{ $item->manga_name }}</a>
								</h4>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</section>
		<aside class="main__sidebar">
			<section class="section new_manga">
				<div class="section__title">
					<h2>Nuevos capítulos</h2>
					<div class="section__type">Manga</div>
				</div>
				<div class="section__content">
					<div class="new__chapters">
						@foreach ($latestChapters['manga'] as $item)
							<div class="new__chapters__item">
								<a href="{{ $item->slug }}" class="new__chapters__link">
									<figure class="new__chapters__image">
										<img src="{{ asset('storage/'.$item->featured_image) }}" alt="{{ $item->manga_name }}">
									</figure>
									<div class="new__chapters__content">
										<h6>{{ $item->manga_name }}</h6>
										<span class="new__chapters__chapter">{{ $item->name }}</span>
										<span class="new__chapters__date">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</span>
									</div>
									<div class="new__chapters__icon">
										<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M19.9375 3.45899H15.0219C13.967 3.45899 12.9357 3.76191 12.0484 4.3334L11 5.00586L9.95156 4.3334C9.06514 3.76202 8.03274 3.45842 6.97812 3.45899H2.0625C1.68223 3.45899 1.375 3.76621 1.375 4.14649V16.3496C1.375 16.7299 1.68223 17.0371 2.0625 17.0371H6.97812C8.03301 17.0371 9.06426 17.34 9.95156 17.9115L10.9055 18.526C10.9334 18.5432 10.9656 18.5539 10.9979 18.5539C11.0301 18.5539 11.0623 18.5453 11.0902 18.526L12.0441 17.9115C12.9336 17.34 13.967 17.0371 15.0219 17.0371H19.9375C20.3178 17.0371 20.625 16.7299 20.625 16.3496V4.14649C20.625 3.76621 20.3178 3.45899 19.9375 3.45899ZM8.67969 11.8916C8.67969 11.9797 8.61094 12.0527 8.52715 12.0527H4.53535C4.45156 12.0527 4.38281 11.9797 4.38281 11.8916V10.9248C4.38281 10.8367 4.45156 10.7637 4.53535 10.7637H8.525C8.60879 10.7637 8.67754 10.8367 8.67754 10.9248V11.8916H8.67969ZM8.67969 8.88379C8.67969 8.97188 8.61094 9.04492 8.52715 9.04492H4.53535C4.45156 9.04492 4.38281 8.97188 4.38281 8.88379V7.91699C4.38281 7.82891 4.45156 7.75586 4.53535 7.75586H8.525C8.60879 7.75586 8.67754 7.82891 8.67754 7.91699V8.88379H8.67969ZM17.6172 11.8916C17.6172 11.9797 17.5484 12.0527 17.4646 12.0527H13.4729C13.3891 12.0527 13.3203 11.9797 13.3203 11.8916V10.9248C13.3203 10.8367 13.3891 10.7637 13.4729 10.7637H17.4625C17.5463 10.7637 17.615 10.8367 17.615 10.9248V11.8916H17.6172ZM17.6172 8.88379C17.6172 8.97188 17.5484 9.04492 17.4646 9.04492H13.4729C13.3891 9.04492 13.3203 8.97188 13.3203 8.88379V7.91699C13.3203 7.82891 13.3891 7.75586 13.4729 7.75586H17.4625C17.5463 7.75586 17.615 7.82891 17.615 7.91699V8.88379H17.6172Z"/>
										</svg>										
									</div>
								</a>
							</div>
						@endforeach
					</div>
				</div>
			</section>
			<section class="section new_novels">
				<div class="section__title">
					<h2>Nuevos capítulos</h2>
					<div class="section__type">Novelas</div>
				</div>
				<div class="section__content">
					<div class="new__chapters">
						@foreach ($latestChapters['novel'] as $item)
							<div class="new__chapters__item">
								<a href="{{ $item->slug }}" class="new__chapters__link">
									<figure class="new__chapters__image">
										<img src="{{ asset('storage/'.$item->featured_image) }}" alt="{{ $item->manga_name }}">
									</figure>
									<div class="new__chapters__content">
										<h6>{{ $item->manga_name }}</h6>
										<span class="new__chapters__chapter">{{ $item->name }}</span>
										<span class="new__chapters__date">{{ Carbon\Carbon::parse($item->created_at)->diffForHumans()}}</span>
									</div>
									<div class="new__chapters__icon">
										<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M19.9375 3.45899H15.0219C13.967 3.45899 12.9357 3.76191 12.0484 4.3334L11 5.00586L9.95156 4.3334C9.06514 3.76202 8.03274 3.45842 6.97812 3.45899H2.0625C1.68223 3.45899 1.375 3.76621 1.375 4.14649V16.3496C1.375 16.7299 1.68223 17.0371 2.0625 17.0371H6.97812C8.03301 17.0371 9.06426 17.34 9.95156 17.9115L10.9055 18.526C10.9334 18.5432 10.9656 18.5539 10.9979 18.5539C11.0301 18.5539 11.0623 18.5453 11.0902 18.526L12.0441 17.9115C12.9336 17.34 13.967 17.0371 15.0219 17.0371H19.9375C20.3178 17.0371 20.625 16.7299 20.625 16.3496V4.14649C20.625 3.76621 20.3178 3.45899 19.9375 3.45899ZM8.67969 11.8916C8.67969 11.9797 8.61094 12.0527 8.52715 12.0527H4.53535C4.45156 12.0527 4.38281 11.9797 4.38281 11.8916V10.9248C4.38281 10.8367 4.45156 10.7637 4.53535 10.7637H8.525C8.60879 10.7637 8.67754 10.8367 8.67754 10.9248V11.8916H8.67969ZM8.67969 8.88379C8.67969 8.97188 8.61094 9.04492 8.52715 9.04492H4.53535C4.45156 9.04492 4.38281 8.97188 4.38281 8.88379V7.91699C4.38281 7.82891 4.45156 7.75586 4.53535 7.75586H8.525C8.60879 7.75586 8.67754 7.82891 8.67754 7.91699V8.88379H8.67969ZM17.6172 11.8916C17.6172 11.9797 17.5484 12.0527 17.4646 12.0527H13.4729C13.3891 12.0527 13.3203 11.9797 13.3203 11.8916V10.9248C13.3203 10.8367 13.3891 10.7637 13.4729 10.7637H17.4625C17.5463 10.7637 17.615 10.8367 17.615 10.9248V11.8916H17.6172ZM17.6172 8.88379C17.6172 8.97188 17.5484 9.04492 17.4646 9.04492H13.4729C13.3891 9.04492 13.3203 8.97188 13.3203 8.88379V7.91699C13.3203 7.82891 13.3891 7.75586 13.4729 7.75586H17.4625C17.5463 7.75586 17.615 7.82891 17.615 7.91699V8.88379H17.6172Z"/>
										</svg>										
									</div>
								</a>
							</div>
						@endforeach
					</div>
				</div>
			</section>
		</aside>
	</div>
</x-app-layout>