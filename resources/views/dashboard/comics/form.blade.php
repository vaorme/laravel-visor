<x-dashboard-layout>
    @php
        $isEdit = isset($comic)? true : false;
    @endphp
    <!-- Page header -->
    <div class="page-header d-print-none text-white">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Comic</div>
                    <h2 class="page-title">{{ $isEdit? 'Editar': 'Agregar' }}</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
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
    <div class="page-body comics">
        <div class="container-xl">
            <form action="{{ $isEdit? route('comics.update', ['id' => $comic->id]) : route('comics.store') }}" class="frmo{{ $isEdit? ' update' : '' }}" method="post" novalidate enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif
                @if (isset($comic))
                    <input type="hidden" name="comic_id" value="{{ $comic->id }}">
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
                                                    <input type="text" class="form-control" id="floating-input" name="title" autocomplete="off" value="{{ $isEdit? $comic->name : '' }}" required>
                                                    <label for="floating-input">Titulo</label>  
                                                    <div class="invalid-feedback">
                                                        Campo <b>Titulo</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="floating-input" name="alternative_title" autocomplete="off" value="{{ $isEdit? $comic->alternative_name : '' }}">
                                                    <label for="floating-input">Titulo alternativo</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="floating-input" name="slug" autocomplete="off" value="{{ $isEdit? $comic->slug : '' }}" required>
                                                    <label for="floating-input">Slug</label>
                                                    <div class="invalid-feedback">
                                                        Campo <b>Slug</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Descripcion</label>
                                                <textarea class="form-control" rows="12" name="description" id="tinymce-mytextarea">{{ $isEdit? $comic->description : '' }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Proximo capítulo</label>
                                                <small class="form-hint">Debes seleccionar una fecha y cada cuanto sale ese capitulo en base a la fecha ingresada.</small>
                                                <fieldset class="form-fieldset mt-2">
                                                    <div class="row row-cards">
                                                        <div class="col-6">
                                                            <label class="form-label">Fecha</label>
                                                            <div class="input-icon">
                                                                <span class="input-icon-addon">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                                                </span>
                                                                <input class="form-control" name="next_chapter_date" value="{{ $isEdit? $comic->new_chapters_date : '' }}" placeholder="Seleccionar una fecha" id="datepicker-next-chapter" value="" autocomplete="off"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label">Cada cuanto</label>
                                                            <select type="text" class="form-select" name="next_chapter_when" placeholder="Seleccionar" id="select-each-when" value="">
                                                                <option value="">Seleccionar</option>
                                                                <option value="day" {{ $isEdit && $comic->new_chapters_time == "day"? 'selected' : '' }}>Diario</option>
                                                                <option value="week" {{ $isEdit && $comic->new_chapters_time == "week"? 'selected' : '' }}>Semanal</option>
                                                                <option value="month" {{ $isEdit && $comic->new_chapters_time == "month"? 'selected' : '' }}>Mensual</option>
                                                                <option value="year" {{ $isEdit && $comic->new_chapters_time == "year"? 'selected' : '' }}>Anual</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($isEdit)
                            <div class="col-12">
                                <div class="card card-borderless chapters">
                                    <div class="card-header border-bottom">
                                        <div class="col">
                                            <h4 class="card-title">Capítulos</h4>
                                        </div>
                                        <div class="botn-group col-auto text-end gp-4">
                                            <a href="javascript:void(0)" class="botn view btn btn-lime" data-bs-toggle="modal" data-bs-target="#chapter-modal">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M12 5l0 14"></path>
                                                    <path d="M5 12l14 0"></path>
                                                </svg>
                                                Crear Capítulo
                                            </a>
                                            <a href="javascript:void(0)" class="botn view btn btn-bitbucket" data-bs-toggle="modal" data-bs-target="#modal-chapter-import">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-import" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                    <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3"></path>
                                                </svg>
                                                Importar capítulos
                                            </a>
                                        </div>
                                    </div>
                                    @if (isset($chapters) && $chapters->isNotEmpty())
                                        <div class="card-body grid g-4">
                                            @foreach ($chapters as $item)
                                                <div class="item g-col-12 border d-flex justify-content-between align-items-center rounded-2" id="m-{{ $item->id }}" data-id="{{ $item->id }}">
                                                    <div class="lft d-flex g-4 w-full">
                                                        <div class="inpt-select d-flex align-items-center">
                                                            <input class="form-check-input input-del-chapters" type="checkbox" name="delete_chapters[]" value="{{ $item->id }}">
                                                        </div>
                                                        <button class="drag">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-grip-horizontal" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                <path d="M5 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                                <path d="M5 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                                <path d="M12 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                                <path d="M12 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                                <path d="M19 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                                <path d="M19 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                                            </svg>
                                                        </button>
                                                        <div class="name col-8">{{ $item->name }}</div>
                                                    </div>
                                                    <div class="rig w-full d-flex justify-content-end">
                                                        <div class="actions">
                                                            <a href="{{ URL::route('chapter_viewer.index', [
                                                                'manga_slug' => $comic->slug,
                                                                'chapter_slug' => $item->slug
                                                            ]); }}" data-id="{{ $item->id }}" class="botn view btn btn-lime btn-icon" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M7 4v16l13 -8z"></path>
                                                                </svg>
                                                            </a>
                                                            <button data-id="{{ $item->id }}" class="botn edit btn btn-bitbucket btn-icon" data-bs-toggle="modal" data-bs-target="#chapter-modal">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                                                    <path d="M13.5 6.5l4 4"></path>
                                                                </svg>
                                                            </button>
                                                            <button data-id="{{ $item->id }}" class="botn delete btn btn-pinterest btn-icon" data-bs-toggle="modal" data-bs-target="#chapter-destroy">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                    <path d="M18 6l-12 12"></path>
                                                                    <path d="M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="card-body">
                                            <div class="card card-inactive">
                                                <div class="card-body text-center">
                                                    <p>No se han encontrado capítulos</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
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
                                                    <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">Resumen</button>
                                                </h2>
                                                <div id="collapse-1" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
                                                        <div class="row row-cards">
                                                            <div class="col-12">
                                                                <label class="form-label">Estado</label>
                                                                <select type="text" name="status" class="form-select" id="select-status" value="" required>
                                                                    <option value="published" data-custom-properties="&lt;span class=&quot;badge bg-success&quot;&gt;&lt;/span&gt;" {{ $isEdit && $comic->status == "published"? 'selected' : '' }}>Publicado</option>
                                                                    <option value="programmed" data-custom-properties="&lt;span class=&quot;badge bg-primary&quot;&gt;&lt;/span&gt;" {{ $isEdit && $comic->status == "programmed"? 'selected' : '' }}>Programado</option>
                                                                    <option value="draft" data-custom-properties="&lt;span class=&quot;badge bg-warning&quot;&gt;&lt;/span&gt;" {{ $isEdit && $comic->status == "draft"? 'selected' : '' }}>Borrador</option>
                                                                    <option value="private" data-custom-properties="&lt;span class=&quot;badge bg-purple&quot;&gt;&lt;/span&gt;" {{ $isEdit && $comic->status == "private"? 'selected' : '' }}>Privado</option>
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Campo <b>Estado</b> es requerido
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Autor</label>
                                                                <select type="text" name="author" class="form-select" id="select-author" value="" required>
                                                                    @if ($users->isNotEmpty())
                                                                        @foreach ($users as $user)
                                                                            <option value="{{ $user->id }}" data-custom-properties="&lt;span class=&quot;avatar avatar-xs&quot; style=&quot;background-image: url({{ asset('storage/'.$user->profile->avatar) }})&quot;&gt;&lt;/span&gt;" {{
                                                                            ($isEdit && $comic->user_id == $user->id) ? 'selected' :
                                                                            ((!$isEdit && $user->id === Auth::id()) ? 'selected' : '') }}>
                                                                                {{ $user->username }}
                                                                            </option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Campo <b>Autor</b> es requerido
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Fecha publicacion</label>
                                                                <div class="input-icon">
                                                                    <span class="input-icon-addon">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                                                                    </span>
                                                                    <input class="form-control" name="release_date" value="{{ $isEdit? $comic->release_date : '' }}" placeholder="Seleccionar una fecha" id="datepicker" value="" autocomplete="off"/>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <h4 class="border-top pt-3">Comic</h4>
                                                                <label class="form-label">Tipo</label>
                                                                <select type="text" name="comic_type" class="form-select" placeholder="Seleccionar" id="select-comic-type" value="" required>
                                                                    <option value="">Seleccionar</option>
                                                                    @if ($types->isNotEmpty())
                                                                        @foreach ($types as $type)
                                                                            <option value="{{ $type->id }}" {{ $isEdit && $comic->type_id == $type->id? 'selected' : '' }}>{{ $type->name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Campo <b>Tipo</b> es requerido
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Estado</label>
                                                                <select type="text" name="comic_status" class="form-select" placeholder="Seleccionar" id="select-comic-status" value="">
                                                                    <option value="">Seleccionar</option>
                                                                    @if ($comicStatus->isNotEmpty())
                                                                        @foreach ($comicStatus as $status)
                                                                            <option value="{{ $status->id }}" {{ $isEdit && $comic->book_status_id == $status->id? 'selected' : '' }}>{{ $status->name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label">Demografia</label>
                                                                <select type="text" name="comic_demography" class="form-select" placeholder="Seleccionar" id="select-comic-demography" value="" required>
                                                                    <option value="">Seleccionar</option>
                                                                    @if ($demographies->isNotEmpty())
                                                                        @foreach ($demographies as $demography)
                                                                            <option value="{{ $demography->id }}" {{ $isEdit && $comic->demography_id == $demography->id? 'selected' : '' }}>{{ $demography->name }}</option>
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                                <div class="invalid-feedback">
                                                                    Campo <b>Demografia</b> es requerido
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border-0 border-bottom">
                                                <h2 class="accordion-header" id="heading-2">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false">Portada</button>
                                                </h2>
                                                <div id="collapse-2" class="accordion-collapse collapse">
                                                    <div class="accordion-body pt-0">
                                                        <div class="own-dropzone">
                                                            <div class="dz-choose">
                                                                <div class="dz-preview">
                                                                    <img src="{{ $isEdit && isset($comic->featured_image)? $comic->cover() : '' }}" alt="" class="dz-image{{ $isEdit && isset($comic->featured_image)? ' show' : '' }}">
                                                                    <div class="dz-change{{ ($isEdit && !isset($comic->featured_image)) || (!$isEdit) ? ' visually-hidden' : '' }}">
                                                                        <a href="javascript:void(0)" class="btn btn-pinterest w-auto">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-exchange-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                <path d="M17 10h-14l4 -4"></path>
                                                                                <path d="M7 14h14l-4 4"></path>
                                                                            </svg>
                                                                            Cambiar portada
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <p class="dz-text">Elegir portada</p>
                                                            </div>
                                                            <input type="file" name="cover" class="dz-input" accept="image/*" hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border-0 border-bottom">
                                                <h2 class="accordion-header" id="heading-3">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false">Categorías</button>
                                                </h2>
                                                <div id="collapse-3" class="accordion-collapse collapse">
                                                    <div class="accordion-body pt-0">
                                                        <select type="text" name="categories[]" class="form-select" placeholder="Seleccionar" id="select-categories" value="" multiple>
                                                            @if (isset($has_categories) && $has_categories->isNotEmpty())
                                                                @foreach ($has_categories as $item)
                                                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                                                @endforeach
                                                            @endif
                                                            @if ($categories->isNotEmpty())
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item border-0">
                                                <h2 class="accordion-header" id="heading-4">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false">Tags</button>
                                                </h2>
                                                <div id="collapse-4" class="accordion-collapse collapse">
                                                    <div class="accordion-body pt-0">
                                                        @php
                                                            if (isset($has_tags)) {
                                                                $arrayTags = [];
                                                                foreach ($has_tags as $tag) {
                                                                    $arrayTags[] = $tag->name;
                                                                }
                                                            }
                                                        @endphp
                                                        <input type="text" name="tags" id="input-tags" placeholder="Tags" value="{{ (!empty($has_tags))? implode(',',$arrayTags) : '' }}">
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
            <x-dashboard.components.modal-danger id="chapter-destroy" message="¿Realmente quieres eliminar este capítulo? No se puede deshacer."/>
            <x-dashboard.components.modal-danger id="md-delete-chapters" message="¿Realmente quieres eliminar estos capítulos? No se puede deshacer."/>
            {{-- MODAL IMPORT CHAPTER --}}
            <div class="modal modal-dialog-scrollable modal-blur fade" id="modal-chapter-import" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Importar Capítulos</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="" class="frmo-import" id="frmo-import-chapters">
                                <div class="row row-cards">
                                    <div class="col-12 btn-group" role="group">
                                        <input type="radio" class="btn-check" name="import_disk" id="btn-radio-toolbar-1" autocomplete="off" checked value="public">
                                        <label for="btn-radio-toolbar-1" class="btn btn-icon">Local</label>
                                        {{-- <input type="radio" class="btn-check" name="import_disk" id="btn-radio-toolbar-2" autocomplete="off" value="ftp">
                                        <label for="btn-radio-toolbar-2" class="btn btn-icon">FTP</label> --}}
										<input type="radio" class="btn-check" name="import_disk" id="btn-radio-toolbar-3" autocomplete="off" value="bunnycdn">
                                        <label for="btn-radio-toolbar-3" class="btn btn-icon">BUNNY.NET</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="im-dropzone">
                                            <div class="dz-choose">
                                                <p class="dz-text">Arrastra o Elige el archivo</p>
                                            </div>
                                            <input type="file" name="cover" class="dz-input" accept=".zip" hidden>
                                        </div>
                                    </div>
                                    <div class="col-12 d-none" id="progress-bar">
                                        <div class="progress col-12">
                                            <div class="progress-bar bg-green">
                                                <span class="visually-hidden"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 d-none" id="list-new-chapters">
                                        <div class="list-group list-group-flush list-group-hoverable border">
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="badge bg-green"></span></div>
                                                    <div class="col-auto">
                                                        <span class="avatar">21</span>
                                                    </div>
                                                    <div class="col text-truncate">
                                                        <p class="text-reset d-block m-0">Capitulo 21</p>
                                                        <div class="d-block text-secondary text-truncate mt-n1">Agregado correctamente</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="list-group-item">
                                                <div class="row align-items-center">
                                                    <div class="col-auto"><span class="badge bg-green"></span></div>
                                                    <div class="col-auto">
                                                        <span class="avatar">21</span>
                                                    </div>
                                                    <div class="col text-truncate">
                                                        <p class="text-reset d-block m-0">Capitulo 21</p>
                                                        <div class="d-block text-secondary text-truncate mt-n1">Agregado correctamente</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="file-list d-flex flex-wrap"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" id="buttonChapterImportConfirm" class="btn btn-primary">Importar</button>
                        </div>
                    </div>
                </div>
            </div>
            <x-dashboard.components.chapter-modal />
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
                    tmSelectAuthor,
                    tmSelectCategoreis,
                    tmInputTags,
                    tmSelectComicType,
                    tmSelectComicStatus,
                    tmSelectComicDemography,
                    tmSelectComicWhenEach;

                    const tsOptions = {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
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
                        tmSelectStatus = new TomSelect(document.getElementById('select-status'), tsOptions)
                        tmSelectStatus.on("change", function(value) {
                            if(value == ""){
                                return true;
                            }
                            if(value === "programmed"){
                                const selectParent = tmSelectStatus.wrapper.parentElement;
                                const existHint = selectParent.querySelector('.form-hint');
                                if(existHint) return true;
                                tmSelectStatus.wrapper.insertAdjacentHTML('afterend', `<small class="form-hint">Recuerda seleccionar una fecha futura para programar la publicacion</small>`)
                            }else{
                                if(tmSelectStatus.wrapper.nextElementSibling.classList.contains('form-hint')){
                                    tmSelectStatus.wrapper.nextElementSibling.remove();
                                }
                            }
                        });
                        tmSelectAuthor = new TomSelect(document.getElementById('select-author'), tsOptions)
                        tmSelectCategoreis = new TomSelect(document.getElementById('select-categories'), {
                            copyClassesToDropdown: false,
                            dropdownParent: 'body',
                            controlInput: '<input>',
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
                        })
                        tmInputTags = new TomSelect(document.getElementById('input-tags'), {
                            create: true,
                            createFilter: function(input) {
                                input = input.toLowerCase();
                                return !(input in this.options);
                            },
                            render:{
                                option_create: function( data, escape ){
                                    return '<div class="create">Crear <strong>' + escape(data.input) + '</strong></div>';
                                },
                                no_results: function( data, escape ){
                                    return '<div class="no-results">Sin resultados</div>';
                                },
                            }
                        })
                        tmSelectComicType = new TomSelect(document.getElementById('select-comic-type'), tsOptions)
                        tmSelectComicStatus = new TomSelect(document.getElementById('select-comic-status'), tsOptions)
                        tmSelectComicDemography = new TomSelect(document.getElementById('select-comic-demography'), tsOptions)
                        tmSelectComicWhenEach = new TomSelect(document.getElementById('select-each-when'), tsOptions)
                    }

                    window.Litepicker && (new Litepicker({
                        element: document.getElementById('datepicker-next-chapter'),
                        buttonText: {
                            previousMonth: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>`,
                            nextMonth: `<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>`,
                        },
                    }));
                    flatpickr("#datepicker", {
                        locale: {
                            firstDayOfWeek: 1,
                            weekdays: {
                                shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                                longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                            }, 
                            months: {
                                shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
                                longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            },
                        },
                        enableTime: true,
                        dateFormat: "Y-m-d H:i:S",
                        onClose: function(selectedDates, dateStr, instance) {
                            let currentDate = new Date();
                            let selectedDate = new Date(dateStr);
                            if (selectedDate > currentDate) {
                                tmSelectStatus.setValue('programmed')
                            }else{
                                tmSelectStatus.setValue('published')
                            }
                        }
                    });
                })
            </script>
        </div>
    </div>
    </x-dashboard-layout>