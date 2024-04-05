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
							<a href="javascript:void(0)" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#itemModal">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
								Agregar
							</a>
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
				</div>
			</div>
		</div>
	</div>

</x-dashboard-layout>