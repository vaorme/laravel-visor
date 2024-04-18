<x-dashboard-layout>
	<!-- Page header -->
	<div class="page-header d-print-none text-white">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<div class="page-pretitle">Overview</div>
					<h2 class="page-title">Ads</h2>
				</div>
				<div class="col-auto ms-auto d-print-none">
						<div class="btn-list">
							<button class="btn-submit btn btn-success d-sm-inline-block" >
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brackets" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 4h-3v16h3"></path>
                                    <path d="M16 4h3v16h-3"></path>
                                </svg>
                                Guardar
                            </button>
						</div>
				  </div>
			</div>
		</div>
	</div>
	<!-- Page body -->
	<div class="page-body">
		<div class="container-xl">
			<div class="card card-borderless">
				<div class="card-body">
					<form action="{{ route('settings.ads.update') }}" class="frmo" method="post" novalidate enctype="multipart/form-data">
					@csrf
					@method('PUT')
						<div class="card-alerts">
							@if (Session::has('success'))
								<div class="space-alert alert alert-success">
									<ul>
										<li>{!! \Session::get('success') !!}</li>
									</ul>
								</div>
								<script>
									let alerta = document.querySelector('.space-alert');
									setTimeout(() => {
										alerta.remove();
									}, 4000);
								</script>
							@endif
							@if (Session::has('error'))
								<div class="space-alert alert alert-danger">
									<ul>
										<li>{!! \Session::get('error') !!}</li>
									</ul>
								</div>
								<script>
									let alerta = document.querySelector('.space-alert');
									setTimeout(() => {
										alerta.remove();
									}, 4000);
								</script>
							@endif
							@if ($errors->any())
								<div class="errores">
									<div class="alert alert-danger">
										<ul>
											@foreach ($errors->all() as $error)
												<li>{{ $error }}</li>
											@endforeach
										</ul>
									</div>
								</div>
							@endif
							<div class="row row-cards">
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #1
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-1.jpg') }}" data-bs-title="Arriba de top mensual">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_1" id="tinymce-mytextarea">{{ isset($setting)? old('ads_1', $setting->ads_1) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #2
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-2.jpg') }}" data-bs-title="Arriba de Actualizaciones">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_2" id="tinymce-mytextarea">{{ isset($setting)? old('ads_2', $setting->ads_2) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #3
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-3.jpg') }}" data-bs-title="Debajo de top mensual">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_3" id="tinymce-mytextarea">{{ isset($setting)? old('ads_3', $setting->ads_3) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #4
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-4.jpg') }}" data-bs-title="Arriba de comentarios">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_4" id="tinymce-mytextarea">{{ isset($setting)? old('ads_4', $setting->ads_4) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #5
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-5.jpg') }}" data-bs-title="Arriba del detalle comic">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_5" id="tinymce-mytextarea">{{ isset($setting)? old('ads_5', $setting->ads_5) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #6
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-6.jpg') }}" data-bs-title="Arriba de capitulos">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_6" id="tinymce-mytextarea">{{ isset($setting)? old('ads_6', $setting->ads_6) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #7
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-7.jpg') }}" data-bs-title="Debajo sidebar perfil">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_7" id="tinymce-mytextarea">{{ isset($setting)? old('ads_7', $setting->ads_7) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #8
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-8.jpg') }}" data-bs-title="Arriba de ultimos capitulos perfil">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_8" id="tinymce-mytextarea">{{ isset($setting)? old('ads_8', $setting->ads_8) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #9
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-9.jpg') }}" data-bs-title="Debajo de controlees Viewer">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_9" id="tinymce-mytextarea">{{ isset($setting)? old('ads_9', $setting->ads_9) : '' }}</textarea>
								</div>
								<div class="col-6">
									<label class="form-label">
										<span class="me-2">
											Publicidad #10
										</span>
										<span class="btn btn-outline-warning btn-pill p-2" data-bs-toggle="popover" data-bs-image="{{ asset('storage/images/ads/ad-10.jpg') }}" data-bs-title="Final viewer">
											<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-question-mark m-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" /><path d="M12 19l0 .01" /></svg>
										</span>
									</label>
									<textarea class="form-control" rows="6" name="ads_10" id="tinymce-mytextarea">{{ isset($setting)? old('ads_10', $setting->ads_10) : '' }}</textarea>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</x-dashboard-layout>