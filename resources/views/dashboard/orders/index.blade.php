<x-dashboard-layout>
	<!-- Page header -->
	<div class="page-header d-print-none text-white">
		<div class="container-xl">
			<div class="row g-2 align-items-center">
				<div class="col">
					<div class="page-pretitle">Overview</div>
					<h2 class="page-title">Orders</h2>
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
							<select type="text" class="form-select" id="select-labels" value="">
								<option value="" data-custom-properties="&lt;span class=&quot;badge bg-muted&quot;&gt;&lt;/span&gt;" selected>Estado</option>
								<option value="completed" data-custom-properties="&lt;span class=&quot;badge bg-green&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'completed')? 'selected' : null }}>Completado</option>
								<option value="cancelled" data-custom-properties="&lt;span class=&quot;badge bg-red&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'cancelled')? 'selected' : null }}>Cancelado</option>
								<option value="created" data-custom-properties="&lt;span class=&quot;badge bg-yellow&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'created')? 'selected' : null }}>Creado</option>
								<option value="pending" data-custom-properties="&lt;span class=&quot;badge bg-blue&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'pending')? 'selected' : null }}>Pendiente</option>
							</select>
						</div>
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
							<th>Orden #</th>
							<th><button class="table-sort" data-sort="sort-name">Nombre</button></th>
							<th>Slug</th>
							<th>Estado</th>
							<th>Fecha</th>
							<th>Acciones</th>
						</tr>
						</thead>
						<tbody class="table-tbody">
								@foreach ($newLoop as $item)
									<tr class="align-middle">
										<td><span class="badge bg-muted-lt p-2">{{ $item->order_id }}</span></td>
										<td class="sort-name">
											<a href="javascript:void(0)" data-id="{{ $item->id }}" class="d-inline-block" data-bs-toggle="modal" data-bs-target="#itemModal">
												<h4 class="d-block m-0 text-dark" style="max-width: 300px">{{ $item->name }}</h4>
											</a>
										</td>
										<td>
											{{ $item->email }}
										</td>
										<td>
											@switch($item->status)
												@case("CREATED")
													<span class="badge bg-yellow-lt p-2">{{ $item->status }}</span>
													@break
												@case("COMPLETED")
													<span class="badge bg-green-lt p-2">{{ $item->status }}</span>
													@break
												@case("CANCELLED")
													<span class="badge bg-red-lt p-2">{{ $item->status }}</span>
													@break
												@case("PENDING")
													<span class="badge bg-pending-lt p-2">{{ $item->status }}</span>
													@break
												@default
													<span class="badge bg-muted-lt p-2">SIN ESTADO</span>
											@endswitch
										</td>
										<td>
											{{ $item->created_at->diffForHumans() }}
										</td>
										<td>
											<div class="btn-list flex-nowrap">
												<div class="botn" title="Ver detalles" data-bs-toggle="tooltip" data-bs-placement="top">
													<a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-cyan w-auto btn-icon" data-bs-toggle="modal" data-bs-target="#itemModal">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="#fff" fill="none">
															<path d="M21.544 11.045C21.848 11.4713 22 11.6845 22 12C22 12.3155 21.848 12.5287 21.544 12.955C20.1779 14.8706 16.6892 19 12 19C7.31078 19 3.8221 14.8706 2.45604 12.955C2.15201 12.5287 2 12.3155 2 12C2 11.6845 2.15201 11.4713 2.45604 11.045C3.8221 9.12944 7.31078 5 12 5C16.6892 5 20.1779 9.12944 21.544 11.045Z" stroke="currentColor" stroke-width="1.5" />
															<path d="M15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15C13.6569 15 15 13.6569 15 12Z" stroke="currentColor" stroke-width="1.5" />
														</svg>
													</a>
												</div>
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
						valueNames: [ 'sort-name', 'sort-score', 'sort-chapters',
							{ attr: 'data-date', name: 'sort-date' },
							{ attr: 'data-progress', name: 'sort-progress' },
							'sort-quantity'
						]
					});
					// :SELECT STATUS
					let tmSelect;
					if(window.TomSelect){
						tmSelect = new TomSelect(el = document.getElementById('select-labels'), {
							copyClassesToDropdown: false,
							dropdownParent: 'body',
							controlInput: '<input>',
							render:{
								item: function(data,escape) {
									if( data.customProperties ){
										return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
									}
									return '<div>' + escape(data.text) + '</div>';
								},
								option: function(data,escape){
									if( data.customProperties ){
										return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
									}
									return '<div>' + escape(data.text) + '</div>';
								},
							},
						})
					}
					
					tmSelect.on('change', function(value){
						if(value == ""){
							return true;
						}
	
						let newUrl = new URL(document.location);
						let s = newUrl.searchParams.get('s');
						let status = value;
						if(s && s != ""){
							newUrl.searchParams.delete("s");
							newUrl.searchParams.append("s", s);
						}else{
							newUrl.searchParams.delete("s");
						}
						if(status && status != ""){
							newUrl.searchParams.delete("status");
							newUrl.searchParams.append("status", status);
						}else{
							newUrl.searchParams.delete("status");
						}
	
						window.location.href = newUrl.href;
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