<x-admin-layout>
    <x-slot:title>Slider</x-slot>
    <x-admin.bar title="Slider"/>
    <div class="template-3 flex flex-wrap slider">
        <div class="contain w-3/5">
            @if (Session::has('success'))
                <div class="alertas success">
                    <div class="box">
                        <p>{!! \Session::get('success') !!}</p>
                    </div>
                </div>
                <script>
                    let alerta = document.querySelector('.alertas');
                    setTimeout(() => {
                        alerta.remove();
                    }, 2000);
                </script>
            @endif
            @if (Session::has('error'))
                <div class="alertas error">
                    <div class="box">
                        <p>{!! \Session::get('error') !!}</p>
                    </div>
                </div>
                <script>
                    let alerta = document.querySelector('.alertas');
                    setTimeout(() => {
                        alerta.remove();
                    }, 2000);
                </script>
            @endif
            @if ($loop->isNotEmpty())
            <div class="table">
                <table id="tablr">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($loop as $i)
                            <tr>
                                <td>{{ $i->manga->name }}</td>
                                <td>
                                    <div class="buttons">
                                        @can('slider.destroy')
                                            <a href="{{ route('slider.index') }}" class="elementDelete" data-id="{{ $i->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('slider.edit')
                                            <a href="{{ route('slider.index', ['id' => $i->id]) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                    <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                                </svg>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="empty">
                    <p>No hay resultados</p>
                </div>
            @endif
        </div>
        <aside class="side w-2/5">
            <div class="frmo">
                 <h2>{{ isset($edit)? "Editar" : 'Añadir' }} elemento</h2>
                <form action="{{ isset($edit)? route('slider.update', ['id' => $edit->id]) : route('slider.store') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($edit))
                        @method('PATCH')
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
                    <div class="group logo">
                        <label>Logo</label>
                        <div class="dropzone">
                            <div id="choose">
                                <div class="preview">
                                    <img src="{{ isset($edit)? asset('storage/'.old('background', $edit->logo)) : '' }}" alt="" class="image-preview {{ (isset($edit) && $edit->logo)? 'added' : '' }}">
                                </div>
                                <p class="text-drop">Elegir imagen</p>
                            </div>
                            <input type="file" name="logo" id="image" accept="image/*" hidden>
                        </div>
                        <div class="field__info">
                            <ul>
                                <li>Ancho maximo: <b>512px</b></li>
                                <li>Peso maximo: <b>400kb</b></li>
                            </ul>
                        </div>
                    </div>
                    <div class="group background">
                        <label>Background</label>
                        <div class="dropzone">
                            <div id="choose">
                                <div class="preview">
                                    <img src="{{ isset($edit)? asset('storage/'.old('background', $edit->background)) : '' }}" alt="" class="image-preview {{ (isset($edit) && $edit->background)? 'added' : '' }}">
                                </div>
                                <p class="text-drop">Elegir imagen</p>
                            </div>
                            <input type="file" name="background" id="image" accept="image/*" hidden>
                        </div>
                        <div class="field__info">
                            <ul>
                                <li>Ancho maximo: <b>1920px</b></li>
                                <li>Peso maximo: <b>680kb</b></li>
                            </ul>
                        </div>
                    </div>
                    <div class="group">
                        <label>Descripción</label>
                        <textarea name="description" id="" cols="30" rows="4">{{ isset($edit)? old('description', $edit->description) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>Manga</label>
                        <select name="manga_id" id="tom-select-it">
							<option value="" data-src="">Seleccionar</option>
							@if ($mangas->isNotEmpty())
								@foreach ($mangas as $item)
									@php
										$pathImage = 'storage/'.$item->featured_image;
										$imageExtension = pathinfo($pathImage)["extension"];
										$img = ManipulateImage::cache(function($image) use ($item) {
											return $image->make('storage/'.$item->featured_image)->fit(40, 40);
										}, 10, true);

										$img->response($imageExtension, 70);
										$base64 = 'data:image/' . $imageExtension . ';base64,' . base64_encode($img);
										
									@endphp
									<option value="{{ $item->id }}" data-url="{{ route('manga_detail.index', ['slug' => $item->slug]) }}" data-src="{!! $base64 !!}" {{ (isset($edit) && $edit->manga_id == $item->id)? 'selected': null }}>{{ $item->name }}</option>
								@endforeach
							@endif
						</select>
                    </div>
                    <button type="submit" class="botn success">{{ isset($edit)? "Actualizar" : 'Crear' }}</button>
                </form>
            </div>
        </aside>
    </div>

</x-admin-layout>