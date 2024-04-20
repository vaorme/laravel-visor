<x-dashboard-layout>
	<!-- Page header -->
	<div class="page-header d-print-none text-white">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<div class="page-pretitle">Overview</div>
					<h2 class="page-title">Tags</h2>
				</div>
				<div class="col-auto ms-auto d-print-none">
					@can('comics.tags.create')
						<div class="btn-list">
							<a href="#" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#itemModal">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
								Agregar
							</a>
						</div>
					@endcan
				</div>
			</div>
		</div>
	</div>
	<!-- Page body -->
	<div class="page-body">
		<div class="container-xl">
			<div class="card card-borderless">
				<div class="card-body">
					<div class="row">
						<div class="col-auto">
							<form action="{{ url()->full() }}" method="GET">
								<div class="input-icon mb-3">
									<input type="text" name="s" class="form-control" placeholder="Buscar..." value="{{ (isset(request()->s) && !empty(request()->s))? request()->s : null }}">
									<span class="input-icon-addon">
									  <!-- Download SVG icon from http://tabler-icons.io/i/search -->
									  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
									</span>
								</div>
							</form>
						</div>
					</div>
				</div>
				@if ($loop->isNotEmpty())
				@php
					$newLoop = $loop;
				@endphp
				<div id="table-default" class="table-responsive">
					<table class="table">
						<thead>
						<tr>
							<th>#</th>
							<th><button class="table-sort" data-sort="sort-name">Nombre</button></th>
							<th>Slug</th>
							<th>Descripción</th>
							<th>Acciones</th>
						</tr>
						</thead>
						<tbody class="table-tbody">
								@foreach ($newLoop as $item)
									<tr class="align-middle">
										<td>
											<span class="avatar">{{ $item->id }}</span>
										</td>
										<td class="sort-name">
											<a href="javascript:void(0)" data-id="{{ $item->id }}" class="d-inline-block" data-bs-toggle="modal" data-bs-target="#itemModal">
												<h4 class="d-block m-0 text-dark" style="max-width: 300px">{{ $item->name }}</h4>
											</a>
										</td>
										<td>
											{{ $item->slug }}
										</td>
										<td>
											{{ $item->description }}
										</td>
										<td>
											<div class="btn-list flex-nowrap">
												@can('comics.tags.edit')
													<div class="botn" title="Editar" data-bs-toggle="tooltip" data-bs-placement="top">
														<a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-bitbucket w-auto btn-icon" data-bs-toggle="modal" data-bs-target="#itemModal">
															<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-minus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
																<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
																<path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
																<path d="M13.5 6.5l4 4"></path>
																<path d="M16 19h6"></path>
															</svg>
														</a>
													</div>
												@endcan
												@can('comics.tags.destroy')
													<div class="botn" title="Eliminar" data-bs-toggle="tooltip" data-bs-placement="top">
														<a href="javascript:void(0)" class="btn btn-pinterest w-auto btn-icon" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#modalDestroy">
															<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
																<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
																<path d="M4 7l16 0"></path>
																<path d="M10 11l0 6"></path>
																<path d="M14 11l0 6"></path>
																<path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
																<path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
															</svg>
														</a>
													</div>
												@endcan
											</div>
										</td>
									</tr>
								@endforeach
							
						</tbody>
					</table>
				</div>
				@php
					$newLoop->appends(request()->input())->links();
				@endphp
				{{ $newLoop->links('dashboard.components.pagination') }}
				@else
					<div class="row">
						<div class="col-md-12">
							<div class="border-top p-4">
								<div class="card card-inactive">
									<div class="card-body text-center">
										<p>No se han encontrado elementos</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
			</div>
			<div class="modal modal-blur fade" id="modalDestroy" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-sm modal-dialog-centered" role="document">
					<div class="modal-content">
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						<div class="modal-status bg-danger"></div>
						<div class="modal-body text-center py-4">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
							<h3>¿Está seguro?</h3>
							<div class="text-muted">¿Realmente quieres eliminar este elemento? No se puede deshacer.</div>
						</div>
						<div class="modal-footer">
							<div class="w-100">
								<div class="row">
									<div class="col">
										<button class="btn w-100" data-bs-dismiss="modal">Cancelar</button>
									</div>
									<div class="col">
										<button class="position-relative btn btn-danger w-100" id="buttonConfirm">
											Sí, eliminar
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script>
				document.addEventListener("DOMContentLoaded", function() {
					const list = new List('table-default', {
						sortClass: 'table-sort',
						listClass: 'table-tbody',
						valueNames: [ 'sort-name',
							{ attr: 'data-date', name: 'sort-date' },
							{ attr: 'data-progress', name: 'sort-progress' }
						]
					});
				})
			</script>
		</div>
	</div>
	{{-- ? CREATE/EDIT TYPES --}}
		<div class="modal modal-blur fade" id="itemModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content"></div>
			</div>
	  	</div>

</x-dashboard-layout>