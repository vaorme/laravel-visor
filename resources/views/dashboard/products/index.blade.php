<x-dashboard-layout>
	<!-- Page header -->
	<div class="page-header d-print-none text-white">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<div class="page-pretitle">Overview</div>
					<h2 class="page-title">Comics</h2>
				</div>
				<div class="col-auto ms-auto d-print-none">
						@can('products.create')
							<div class="btn-list">
								<a href="{{ route('products.create') }}" class="btn btn-primary d-sm-inline-block" >
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
							<th>Portada</th>
							<th><button class="table-sort" data-sort="sort-name">Nombre</button></th>
							<th>Precio</th>
							<th>Servicio</th>
							<th>Cantidad</th>
							<th>Acciones</th>
						</tr>
						</thead>
						<tbody class="table-tbody">
								@foreach ($loop as $item)
									<tr class="align-middle">
										<td>
											<a href="{{ route('products.edit', ['id' => $item->id]) }}" class="d-inline-block">
												@if ($item->image)
													<div class="avatar avatar-md img-responsive-1x1 rounded-3 border" style="background-image: url({{ asset("storage/".$item->image); }})"></div>
												@else
													<div class="avatar avatar-md img-responsive-1x1 rounded-3 border">
														@switch($item->product_type_id)
															@case(1)
																<svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
																	<path d="M8 0.5C12.1423 0.5 15.5 3.85775 15.5 8C15.5 12.1423 12.1423 15.5 8 15.5C3.85775 15.5 0.5 12.1423 0.5 8C0.5 3.85775 3.85775 0.5 8 0.5ZM7.46975 5.348L5.348 7.46975C5.2074 7.6104 5.12841 7.80113 5.12841 8C5.12841 8.19887 5.2074 8.3896 5.348 8.53025L7.46975 10.652C7.6104 10.7926 7.80113 10.8716 8 10.8716C8.19887 10.8716 8.3896 10.7926 8.53025 10.652L10.652 8.53025C10.7926 8.3896 10.8716 8.19887 10.8716 8C10.8716 7.80113 10.7926 7.6104 10.652 7.46975L8.53025 5.348C8.3896 5.2074 8.19887 5.12841 8 5.12841C7.80113 5.12841 7.6104 5.2074 7.46975 5.348Z" fill-opacity="0.5"/>
																</svg>
																@break
															@case(2)
																<svg viewBox="0 0 512 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
																	<path d="M496 127.1C496 381.3 309.1 512 255.1 512C204.9 512 16 385.3 16 127.1c0-19.41 11.7-36.89 29.61-44.28l191.1-80.01c4.906-2.031 13.13-3.701 18.44-3.701c5.281 0 13.58 1.67 18.46 3.701l192 80.01C484.3 91.1 496 108.6 496 127.1z" fill-opacity="0.5"/>
																</svg>
																@break
															@default
														@endswitch
													</div>
												@endif
											</a>
										</td>
										<td class="sort-name">
											<a href="{{ route('products.edit', ['id' => $item->id]) }}" class="d-inline-block">
												<h4 class="d-block m-0 text-dark" style="max-width: 300px">{{ $item->name }}</h4>
											</a>
										</td>
										<td class="sort-price">
											@php
												$currency = Number::currency($item->price, in: 'USD');
												echo $currency;
											@endphp
										</td>
										<td>
											<span class="badge bg-muted-lt p-2">{{ $item->type_name }}</span>
										</td>
										<td>
											@if (isset($item->coins) && !empty($item->coins))
												<span class="badge bg-yellow-lt p-2">{{ $item->coins }}</span>
											@elseif (isset($item->days_without_ads) && !empty($item->days_without_ads))
												<span class="badge bg-yellow-lt p-2">{{ $item->days_without_ads }}</span>
											@endif
										</td>
										<td>
											<div class="btn-list flex-nowrap">
												@can('products.edit')
													<div class="botn" title="Editar" data-bs-toggle="tooltip" data-bs-placement="top">
														<a href="{{ route('products.edit', ['id' => $item->id]) }}" class="btn btn-bitbucket w-auto btn-icon">
															<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-minus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
																<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
																<path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
																<path d="M13.5 6.5l4 4"></path>
																<path d="M16 19h6"></path>
															</svg>
														</a>
													</div>
												@endcan
												@can('products.destroy')
													<div class="botn" title="Eliminar" data-bs-toggle="tooltip" data-bs-placement="top">
														<a href="javascript:void(0)" class="btn btn-pinterest w-auto btn-icon" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#modal-destroy">
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
			<div class="modal modal-blur fade" id="modal-destroy" tabindex="-1" role="dialog" aria-hidden="true">
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
						valueNames: [ 'sort-name', 'sort-score', 'sort-chapters',
							{ attr: 'data-date', name: 'sort-date' },
							{ attr: 'data-progress', name: 'sort-progress' },
							'sort-quantity'
						]
					});
				})
			</script>
		</div>
	</div>
	</x-dashboard-layout>