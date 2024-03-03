<div class="main__shortcuts">
	<div class="shortcuts__add">
		<button onclick="{{ Auth::check()? 'toggleFormShortcut(event)': 'alertShortcut()' }}" data-tippy-placement="left" data-tippy-content="Agregar atajo">
			<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12 5.14286H6.85714V0H5.14286V5.14286H0V6.85714H5.14286V12H6.85714V6.85714H12V5.14286Z" fill="#D8D8D8"/>
			</svg>					
		</button>
		@if (Auth::check())
			<div class="shortcuts__modal">
				<form action="{{ route('shortcut.store') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="shortcuts__head">
						<div class="shortcuts__title">
							<h4>Crear atajo</h4>
						</div>
						<div class="shortcuts__close">
							<button onclick="closeFormShortcut(event);">
								<svg version="1.1" viewBox="0 0 24 24" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
									<style type="text/css">
									.st0{opacity:0.2;fill:none;stroke:#000000;stroke-width:5.000000e-02;stroke-miterlimit:10;}
									</style>
									<g id="grid_system"/>
									<g id="_icons">
										<path d="M5.3,18.7C5.5,18.9,5.7,19,6,19s0.5-0.1,0.7-0.3l5.3-5.3l5.3,5.3c0.2,0.2,0.5,0.3,0.7,0.3s0.5-0.1,0.7-0.3   c0.4-0.4,0.4-1,0-1.4L13.4,12l5.3-5.3c0.4-0.4,0.4-1,0-1.4s-1-0.4-1.4,0L12,10.6L6.7,5.3c-0.4-0.4-1-0.4-1.4,0s-0.4,1,0,1.4   l5.3,5.3l-5.3,5.3C4.9,17.7,4.9,18.3,5.3,18.7z"/>
									</g>
								</svg>
							</button>
						</div>
					</div>
					<div class="shortcuts__content">
						<select name="select_manga" id="tom-select-it">
							<option value="" data-src="">Seleccionar</option>
							@if ($mangas->isNotEmpty())
								@foreach ($mangas as $item)
									@php
										$base64 = asset('storage/images/error-loading-image.png');
										// Check if featured_image is not null
										if ($item->featured_image && Storage::disk('public')->exists($item->featured_image)) {
											$pathImage = 'storage/'.$item->featured_image;
											$imageExtension = pathinfo($pathImage)["extension"];
											$img = ManipulateImage::cache(function($image) use ($item) {
												return $image->make('storage/'.$item->featured_image)->fit(40, 40);
											}, 10, true);
							
											$img->response($imageExtension, 70);
											$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
										}
									@endphp
									<option value="{{ $item->id }}" data-url="{{ route('manga_detail.index', ['slug' => $item->slug]) }}" data-src="{!! $base64 !!}">{{ $item->name }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<div class="shortcuts__footer">
						<button type="submit" onclick="addShortcut(event);">Agregar</button>
					</div>
				</form>
			</div>
		@endif
	</div>
	@if (Auth::check())
		@if (isset($shortcuts))
			<div class="shortcuts__list">
				@foreach ($shortcuts as $item)
					<div class="shortcuts__item" id="shortcut-{{ $item->id }}" data-tippy-placement="left" data-tippy-content="{{ $item->name }}">
						<a href="{{ $item->url() }}" class="shortcuts__link">
							@php
								$base64 = asset('storage/images/error-loading-image.png');
								if (Storage::disk('public')->exists($item->featured_image)) {
									$pathImage = 'storage/'.$item->featured_image;
									$imageExtension = pathinfo($pathImage)["extension"];
									$img = ManipulateImage::cache(function($image) use ($item) {
										return $image->make('storage/'.$item->featured_image)->fit(45, 45);
									}, 10, true);

									$img->response($imageExtension, 70);
									$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
								}
							@endphp
							<img src="{!! $base64 !!}" alt="{{ $item->name }}">
						</a>
					</div>
				@endforeach
			</div>
		@endif
	@endif
</div>
@if (Auth::check())
	<script>
		const settings = {
			placeholder: "Buscar",
			hidePlaceholder: false,
			allowEmptyOption: false,
			hideSelected: true,
			render: {
				option: function (data, escape) {
					if(data.src != ""){
						return `<div data-url="${data.url}"><img class="me-2" src="${data.src}">${data.text}</div>`;
					}
					return `<div data-url="${data.url}">${data.text}</div>`;
				},
				item: function (item, escape) {
					if(item.src != ""){
						return `<div data-url="${item.url}"><img class="me-2" src="${item.src}">${item.text}</div>`;
					}
					return `<div data-url="${item.url}">${item.text}</div>`;
				}
			}
		};
		const tom = new TomSelect('#tom-select-it',settings);
		const modal = document.querySelector('.shortcuts__modal');
		const bdy = document.querySelector('body');
		

		modal.addEventListener('click', function(e){
			e.stopPropagation();
		});
		bdy.addEventListener('click', function(e){
			modal.classList.remove('active');
			tom.clear();
		});
		function toggleFormShortcut(e){
			e.stopPropagation();

			modal.classList.toggle('active');
		}
		function closeFormShortcut(e){
			e.preventDefault();

			modal.classList.remove('active');
			tom.clear();
		}
		let shortcutSpam = false;
		async function addShortcut(e){
			e.preventDefault();

			const existsToast = document.querySelector('.toastify.error');
			const selectValue = tom.getValue();

			if(selectValue === ""){
				if(!existsToast){
					Toastify({
						text: "Error: Debes asignar un manga",
						className: "error",
						duration: 4000,
						newWindow: false,
						close: false,
						gravity: "top", // `top` or `bottom`
						position: "center", // `left`, `center` or `right`,
						offset: {
							y: 10
						},
					}).showToast();
				}
				return true;
			}

			if(shortcutSpam){
				return true;
			}
			shortcutSpam = true;

			const shortcutList = document.querySelector('.shortcuts__list');
			const form = document.querySelector('.shortcuts__modal form');
			let formData = new FormData(form);
			let fieldToken = formData.get('_token');

			const selectedOption = tom.getOption(selectValue)

			const dataSrc = selectedOption.children[0].src;

			await axios.post(form.action, {
				_token: fieldToken,
				manga_id: parseInt(selectValue),
			}, {
				headers:{
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
					'Content-Type': 'multipart/form-data'
				}
			}).then(function (response){
				console.log(response);
				if(response.data.status == "success"){
					// Creamos el DIV
					let divShortcut = document.createElement('div');
					divShortcut.classList.add('shortcuts__item');
					divShortcut.innerHTML = `
						<a href="${selectedOption.getAttribute('data-url')}" class="shortcuts__link">
							<img src="${dataSrc}">
						</a>
					`;
					shortcutList.append(divShortcut);
					Toastify({
						text: response.data.msg,
						className: "success",
						duration: 4000,
						newWindow: false,
						close: false,
						gravity: "top", // `top` or `bottom`
						position: "center", // `left`, `center` or `right`,
						offset: {
							y: 10
						},
					}).showToast();

					tom.clear();
				}
				if(response.data.status == "error"){
					Toastify({
						text: response.data.msg,
						className: "error",
						duration: 4000,
						newWindow: false,
						close: false,
						gravity: "top", // `top` or `bottom`
						position: "center", // `left`, `center` or `right`,
						offset: {
							y: 10
						},
					}).showToast();
				}
				setTimeout(() => {
					shortcutSpam = false;
				}, 1000);
			})
			.catch(function (error){
				// handle error
				console.log('error: ',error);
				setTimeout(() => {
					shortcutSpam = false;
				}, 1000);
			});
		}
	</script>	
@else

<script>
	const alertShortcut = () =>{
		const existsToast = document.querySelector('.toastify.warning');
		if(!existsToast){
			Toastify({
				text: "Alto! solo para usuarios registrados",
				className: "warning",
				duration: 4000,
				newWindow: false,
				close: false,
				gravity: "top", // `top` or `bottom`
				position: "center", // `left`, `center` or `right`,
				offset: {
					y: 10
				},
			}).showToast();
		}
	}
</script>

@endif