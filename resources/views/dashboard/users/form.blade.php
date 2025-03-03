<x-dashboard-layout>
    @php
        $isEdit = isset($user)? true : false;
    @endphp
    <!-- Page header -->
    <div class="page-header d-print-none text-white">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">User</div>
                    <h2 class="page-title">{{ $isEdit? 'Editar': 'Agregar' }}</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
							@if ($isEdit)
								<a href="javascript:void(0);" class="btn-delete btn btn-danger d-sm-inline-block" data-id="{{ $user->id }}" data-bs-toggle="modal" data-bs-target="#modal-destroy">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
									Eliminar
								</a>
							@endif
                            <button class="btn-submit btn btn-{{ $isEdit? 'primary' : 'success' }} d-sm-inline-block" >
                                {!! $isEdit? '
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brackets" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 4h-3v16h3"></path>
                                    <path d="M16 4h3v16h-3"></path>
                                </svg>
                                Actualizar': '
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                                Crear' !!}
                            </button>
                        </div>
                  </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body users">
        <div class="container-xl">
            <form action="{{ $isEdit? route('users.update', ['id' => $user->id]) : route('users.store') }}" class="frmo{{ $isEdit? ' update' : '' }}" method="post" novalidate enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif
                @if (isset($user))
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                @endif
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
                    <div class="col-lg-8">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card card-borderless">
                                    <div class="card-body p-3">
                                        <div class="row row-cards">
                                            <div class="col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="name" autocomplete="off" value="{{ old('name', (isset($user))? $user->profile->name : '') }}" required>
                                                    <label for="floating-input">Nombre</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Nombre</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" name="username" autocomplete="off" value="{{ old('name', (isset($user))? $user->username : '') }}" required>
                                                    <label for="floating-input">Nombre de Usuario</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Nombre de Usuario</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-12">
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" name="email" autocomplete="off" value="{{ old('name', (isset($user))? $user->email : '') }}" required>
                                                    <label for="floating-input">Correo</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Correo</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											@if (!$isEdit)
												<div class="col-6">
													<div class="form-floating">
														<input type="password" class="form-control" name="password" autocomplete="off" value="" required>
														<label for="floating-input">Contraseña</label>  
														<div class="invalid-feedback">
															Campo <b>Contraseña</b> es requerido
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="form-floating">
														<input type="password" class="form-control" name="password_confirmation" autocomplete="off" value="" required>
														<label for="floating-input">Confirmar contraseña</label>  
														<div class="invalid-feedback">
															Campo <b>Confirmar contraseña</b> es requerido
														</div>
													</div>
												</div>
												<div class="col-12 password-validation">
													<fieldset class="form-fieldset m-0">
														<small class="form-hint mb-2">La contraseña debe contener lo siguiente:</small>
														<div class="security-description">
															<p id="letter" class="m-0 invalid small">Una letra <strong>minúscula</strong></p>
															<p id="capital" class="m-0 invalid small">Una letra en <strong>mayuscula</strong></p>
															<p id="number" class="m-0 invalid small">Un <b>número</b></p>
															<p id="length" class="m-0 invalid small">Minimo <b>8 caracteres</b></p>
														</div>
													</fieldset>
												</div>
											@endif
											<div class="col-12">
                                                <div class="form-floating">
                                                    <select name="country" class="form-select" id="select-country">
														<option value="" selected>Seleccionar País</option>
														@foreach ($countries as $item)
															<option value="{{ $item->id }}" @if (isset($user) && $user->profile->country_id === $item->id)
																selected
															@endif>{{ $item->name }}</option>
														@endforeach
													</select>
                                                    <label for="floating-input">País</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Correo</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-12">
                                                <div class="form-floating">
                                                    <select name="roles" class="form-select" id="select-roles">
														<option value="" selected>Seleccionar Rol</option>
														@foreach ($roles as $item)
															<option value="{{ $item->name }}" @if (isset($user) && !empty($user->roles) && $user->roles->first()->id === $item->id)
																selected
															@endif>{{ ucfirst($item->name) }}</option>
														@endforeach
													</select>
                                                    <label for="floating-input">Rol</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Correo</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-12">
                                                <div class="form-floating">
													<textarea name="message" class="form-control">{{ old('name', (isset($user))? $user->profile->message : '') }}</textarea>
                                                    <label for="floating-input">Mensaje</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Correo</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-12">
                                                <div class="form-floating mb-2">
													<input type="text" class="form-control" name="cover_url" autocomplete="off" value="{{ old('cover', (isset($user))? $user->profile->cover : '') }}">
                                                    <label for="floating-input">URL Portada</label>  
                                                </div>
												<div class="cover-preview dropzone rounded-2">
													<img src="{{ old('cover', (isset($user))? $user->profile->cover : '') }}" alt="cover" @if (isset($user) && !empty($user->profile->cover)) class="added" @endif>
												</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							@if ($isEdit)
							<div class="col-12">
								<div class="card card-borderless">
									<div class="card-header border-bottom">
                                        <div class="col">
                                            <h4 class="card-title">Contraseña</h4>
                                        </div>
                                    </div>
									<div class="card-body p-3">
										<div class="row row-cards">
											<div class="col-6">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" name="password" autocomplete="off" value="">
                                                    <label for="floating-input">Contraseña</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Contraseña</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-6">
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" name="password_confirmation" autocomplete="off" value="">
                                                    <label for="floating-input">Confirmar contraseña</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Confirmar contraseña</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
											<div class="col-12 password-validation">
												<fieldset class="form-fieldset m-0">
													<small class="form-hint mb-2">La contraseña debe contener lo siguiente:</small>
													<div class="security-description">
														<p id="letter" class="m-0 invalid small">Una letra <strong>minúscula</strong></p>
														<p id="capital" class="m-0 invalid small">Una letra en <strong>mayuscula</strong></p>
														<p id="number" class="m-0 invalid small">Un <b>número</b></p>
														<p id="length" class="m-0 invalid small">Minimo <b>8 caracteres</b></p>
													</div>
												</fieldset>
											</div>
										</div>
									</div>
									<div class="card-footer border-top">
										<div class="col">
											<a href="javascript:void(0);" class="btn-submit btn btn-green d-sm-inline-block m-0" id="btnChangePassword">
												<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit-circle" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 15l8.385 -8.415a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3z" /><path d="M16 5l3 3" /><path d="M9 7.07a7 7 0 0 0 1 13.93a7 7 0 0 0 6.929 -6" /></svg>
												Cambiar
											</a>
										</div>
									</div>
								</div>
							</div>
							@endif
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card card-borderless">
                                    <div class="card-body p-0">
                                        <div class="accordion border-0" id="sidebar-accordion">
                                            <div class="accordion-item border-0 border-bottom">
                                                <h2 class="accordion-header" id="heading-1">
                                                    <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">Preferencias</button>
                                                </h2>
                                                <div id="collapse-1" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
														<div class="row row-cards">
															@if ($isEdit)
															<div class="col-12">
																@if (isset($user->email_verified_at))
																	<a href="javascript:void(0);" class="btn btn-danger w-100" id="deactivateAccount">
																		<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rotated-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.793 2.893l-6.9 6.9c-1.172 1.171 -1.172 3.243 0 4.414l6.9 6.9c1.171 1.172 3.243 1.172 4.414 0l6.9 -6.9c1.172 -1.171 1.172 -3.243 0 -4.414l-6.9 -6.9c-1.171 -1.172 -3.243 -1.172 -4.414 0z" stroke-width="0" fill="currentColor" /></svg>
																		Desactivar cuenta
																	</a>
																@else
																	<a href="javascript:void(0);" class="btn btn-green w-100" id="activateAccount">
																		<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rotated" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13.446 2.6l7.955 7.954a2.045 2.045 0 0 1 0 2.892l-7.955 7.955a2.045 2.045 0 0 1 -2.892 0l-7.955 -7.955a2.045 2.045 0 0 1 0 -2.892l7.955 -7.955a2.045 2.045 0 0 1 2.892 0z" /></svg>
																		Activar cuenta
																	</a>
																@endif
															</div>
															<div class="col-12 d-flex gap-3">
																<a href="javascript:void(0);" class="btn btn-yellow w-100" data-type="coins" data-bs-toggle="modal" data-bs-target="#modalCoinsAds">
																	<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-coins" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14c0 1.657 2.686 3 6 3s6 -1.343 6 -3s-2.686 -3 -6 -3s-6 1.343 -6 3z" /><path d="M9 14v4c0 1.656 2.686 3 6 3s6 -1.344 6 -3v-4" /><path d="M3 6c0 1.072 1.144 2.062 3 2.598s4.144 .536 6 0c1.856 -.536 3 -1.526 3 -2.598c0 -1.072 -1.144 -2.062 -3 -2.598s-4.144 -.536 -6 0c-1.856 .536 -3 1.526 -3 2.598z" /><path d="M3 6v10c0 .888 .772 1.45 2 2" /><path d="M3 11c0 .888 .772 1.45 2 2" /></svg>
																	Monedas
																</a>
																<a href="javascript:void(0);" class="btn btn-primary w-100" data-type="days" data-bs-toggle="modal" data-bs-target="#modalCoinsAds">
																	<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ad" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 15v-4a2 2 0 0 1 4 0v4" /><path d="M7 13l4 0" /><path d="M17 9v6h-1.5a1.5 1.5 0 1 1 1.5 -1.5" /></svg>
																	Días sin ADS
																</a>
															</div>
															@endif
															<div class="col-8">
																<span>Perfil público</span>
															</div>
															<div class="col-4">
																<span>
																	<label class="form-check form-check-single form-switch p-0 d-flex justify-content-end">
																		<input class="form-check-input" name="public_profile" type="checkbox" @if (isset($user) && $user->profile->public_profile === 1)
																		checked
																		@endif>
																	</label>
																</span>
															</div>
														</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border-0 border-bottom">
                                                <h2 class="accordion-header" id="heading-3">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false">Avatar</button>
                                                </h2>
                                                <div id="collapse-3" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
                                                        <div class="card">
															<div class="card-body p-4 text-center">
																<div class="own-dropzone avatar avatar-xl rounded">
																	<div class="dz-choose">
																		<div class="dz-preview">
																			@if (isset($user) && isset($user->profile))
																				<img src="{{ asset('storage/'.$user->profile->avatar) }}" alt="" class="dz-image show">
																			@else
																				<img src="" alt="" class="dz-image">
																			@endif
																		</div>
																		<p class="dz-text">Elegir Imagen</p>
																	</div>
																	<input type="file" name="avatar" class="dz-input" accept="image/*" hidden>
																</div>
																@if (isset($user) && isset($user->profile) && !empty($user->profile->avatar))
																	<input type="text" name="current_avatar" hidden value="{{ $user->profile->avatar }}">
																@endif
																{{-- <span class="avatar avatar-xl mb-3 rounded" style="background-image: url(./static/avatars/000m.jpg)"></span> --}}
															</div>
															<div class="d-flex avatar-actions">
																@if ($isEdit && isset($user->profile) && !empty($user->profile->avatar))
																	<a href="javascript:void(0);" class="card-btn btn-primary action-change">
																		<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-edit me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M11 20h-4a3 3 0 0 1 -3 -3v-10a3 3 0 0 1 3 -3h10a3 3 0 0 1 3 3v4" /><path d="M4 15l4 -4c.928 -.893 2.072 -.893 3 0l3 3" /><path d="M14 14l1 -1c.31 -.298 .644 -.497 .987 -.596" /><path d="M18.42 15.61a2.1 2.1 0 0 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39z" /></svg>
																		Cambiar
																	</a>
																	<a href="javascript:void(0);" class="card-btn action-remove">
																		<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-minus me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 12h6" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
																		Eliminar
																	</a>
																@endif
															</div>
														</div>
                                                    </div>
                                                </div>
                                            </div>
											<div class="accordion-item border-0 border-bottom">
                                                <h2 class="accordion-header" id="heading-3">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false">Redes</button>
                                                </h2>
                                                <div id="collapse-4" class="accordion-collapse collapse">
                                                    <div class="social-links accordion-body pt-0">
                                                        <div class="row row-cards">
															<div class="col-12 row g-2">
																<div class="col">
																	<input type="text" name="add_social" class="form-control" placeholder="Enlace">
																</div>
																<div class="col-auto">
																	<a href="#" class="btn btn-add btn-icon btn-green" aria-label="Button">
																		<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
																	</a>
																</div>
															</div>
                                                            @php
                                                            $redes = json_decode(isset($user)? $user->profile->redes : "");
                                                        @endphp
															@if (isset($redes) && !empty($redes))
																@foreach ($redes as $key => $value)
																	<div class="social-item col-12 row g-2" id="item-{{ $key }}">
																		<div class="col">
																			<input type="text" name="social[]" class="form-control" value="{{ $value }}">
																		</div>
																		<div class="col-auto">
																			<a href="#item-{{ $key }}" class="btn btn-remove btn-icon btn-danger">
																				<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
																			</a>
																		</div>
																	</div>
																@endforeach
															@endif
														</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
			{{-- ? MODAL COINS/ADS --}}
			<div class="modal modal-blur fade" id="modalCoinsAds" tabindex="-1" role="dialog" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
					<div class="modal-content"></div>
				</div>
			</div>
            {{-- ? MODAL DESTORY --}}
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
                    // :TINYMCE
                    let options = {
                        selector: '#tinymce-mytextarea',
                        height: 300,
                        menubar: false,
                        statusbar: false,
                        plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen","insertdatetime","media", "table", "code", "help", "wordcount"],
                        toolbar: 'undo redo | formatselect | ' +
                            'bold italic forecolor backcolor | alignleft aligncenter ' +
                            'alignright alignjustify | bullist numlist outdent indent | ' +
                            'removeformat',
                        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
                    }
                    if (localStorage.getItem("tablerTheme") === 'dark') {
                        options.skin = 'oxide-dark';
                        options.content_css = 'dark';
                    }
                    // COMIC DESCRIPTION
                    tinyMCE.init(options);

                    // :SELECT STATUS
                    let tmSelectStatus,
                    tmSelectCountry,
					tmSelectRoles;

                    const tsOptions = {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
						maxOptions: null,
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
                    };
                    const tsOptionsWithRemove = {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
                        plugins: {
                            remove_button:{
                                title:'Remove',
                            }
                        },
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
                    };

                    if(window.TomSelect){
                        tmSelectCountry = new TomSelect(document.getElementById('select-country'), tsOptions);
						tmSelectRoles = new TomSelect(document.getElementById('select-roles'), tsOptions);
                    }
                })
            </script>
        </div>
    </div>
    </x-dashboard-layout>