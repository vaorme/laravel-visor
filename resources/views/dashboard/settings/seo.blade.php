<x-dashboard-layout>
	<!-- Page header -->
	<div class="page-header d-print-none text-white">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<div class="page-pretitle">Overview</div>
					<h2 class="page-title">SEO</h2>
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
					<form action="{{ route('settings.seo.update') }}" class="frmo" method="post" novalidate enctype="multipart/form-data">
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
						</div>
						<div class="row row-cards">
							<div class="col-2">
								<label class="form-label">SEO título</label>
							</div>
							<div class="col-10">
								<input type="text" class="form-control" name="seo_title" value="{{ isset($setting)? old('seo_title', $setting->seo_title) : '' }}">
							</div>
							<div class="col-2">
								<label class="form-label">SEO descripción</label>
							</div>
							<div class="col-10">
								<textarea class="form-control" rows="6" name="seo_description" id="tinymce-mytextarea">{{ isset($setting)? old('seo_description', $setting->seo_description) : '' }}</textarea>
							</div>
							<div class="col-2">
								<label class="form-label">SEO palabras clave</label>
							</div>
							<div class="col-10">
								<input type="text" class="form-control" name="seo_keywords" value="{{ isset($setting)? old('seo_keywords', $setting->seo_keywords) : '' }}">
							</div>
							<div class="col-2">
								<label class="form-label">SEO autor</label>
							</div>
							<div class="col-10">
								<input type="text" class="form-control" name="seo_author" value="{{ isset($setting)? old('seo_author', $setting->seo_author) : '' }}">
							</div>
							<div class="col-2">
								<label class="form-label">SEO asunto</label>
							</div>
							<div class="col-10">
								<input type="text" class="form-control" name="seo_subject" value="{{ isset($setting)? old('seo_subject', $setting->seo_subject) : '' }}">
							</div>
							<div class="col-2">
								<label class="form-label">SEO robots</label>
							</div>
							<div class="col-10">
								<input type="text" class="form-control" name="seo_robots" value="{{ isset($setting)? old('seo_robots', $setting->seo_robots) : '' }}">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

</x-dashboard-layout>