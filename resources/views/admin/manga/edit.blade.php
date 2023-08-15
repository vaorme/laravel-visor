<x-admin-layout>
    <x-slot:title>Editar | {{ $manga->name }}</x-slot>
    <x-admin.bar :title="$manga->name" :backTo="route('manga.index')" />
    <div class="frmo fm-manga fm-update">
        <form action="{{ route('manga.update', ['id' => $manga->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
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
            <div class="main">
                <div class="section cols head">
                    <div class="item name">
                        <label for="m-name">Name</label>
                        <input type="text" name="name" id="m-name" value="{{ old('name', $manga['name']) }}">
                    </div>
                    <div class="item slug">
                        <label for="m-slug">Slug</label>
                        <input type="text" name="slug" id="m-slug" value="{{ old('slug', $manga['slug']) }}">
                    </div>
                    <div class="item alt-name">
                        <label for="m-altname">Alternative Name</label>
                        <input type="text" name="alternative_name" id="m-altname" value="{{ old('alternative_name', $manga['alternative_name']) }}">
                    </div>
                </div>
                <div class="section cols description">
                    <div class="item">
                        <label>Description</label>
                        <textarea name="description" id="" cols="30" rows="5">{{ old('description', $manga['description']) }}</textarea>
                    </div>
                </div>
                <div class="section cols dates">
                    <div class="item">
                        <label>Capítulo nuevo</label>
                        <div class="dates__select">
                            <select name="new_chapters_time" id="ch-chapter-time">
                                <option value="">Seleccionar</option>
                                <option value="day" {{ ($manga->new_chapters_time == "day")? 'selected': null }}>Diario</option>
                                <option value="week" {{ ($manga->new_chapters_time == "week")? 'selected': null }}>Semanal</option>
                                <option value="month" {{ ($manga->new_chapters_time == "month")? 'selected': null }}>Mensual</option>
                            </select>
                        </div>
                    </div>
                    <div class="item">
                        <label for="ch-date">Fecha</label>
                        <input type="text" name="new_chapters_date" id="ch-date" autocomplete="off" value="{{ old('new_chapters_date', $manga['new_chapters_date']) }}">
                    </div>
                </div>
                <div class="section chapters">
                    <div class="buttons">
                        <a href="#" id="ct-chapter">
                            Crear capítulo
                            <i class="fa-solid fa-plus"></i>
                        </a>
                        <a href="#" id="popup-chapter">
                            Subir capítulo/s
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                    <div class="chapter__upload">
                        <div class="upload">
                            <div class="group upload">
                                <label>Subir a</label>
                                <div class="disks rdos">
                                    <label for="cup-1">
                                        <input type="radio" name="disk" value="public" id="cup-1" checked>
                                        <div class="rdo">
                                            <div class="inpt"></div>
                                            <div class="name">Local</div>
                                        </div>
                                    </label>
                                    @if (env('FTP_HOST') && env('FTP_HOST') != "")
                                        <label for="cup-2">
                                            <input type="radio" name="disk" value="ftp" id="cup-2">
                                            <div class="rdo">
                                                <div class="inpt"></div>
                                                <div class="name">FTP</div>
                                            </div>
                                        </label>
                                    @endif
                                    @if (env('SFTP_HOST') && env('SFTP_HOST') != "")
                                    <label for="cup-3">
                                        <input type="radio" name="disk" value="sftp" id="cup-3">
                                        <div class="rdo">
                                            <div class="inpt"></div>
                                            <div class="name">FTP</div>
                                        </div>
                                    </label>
                                    @endif
                                    @if (env('S3_HOST') && env('S3_HOST') != "")
                                        <label for="cup-4">
                                            <input type="radio" name="disk" value="s3" id="cup-4">
                                            <div class="rdo">
                                                <div class="inpt"></div>
                                                <div class="name">Amazon S3</div>
                                            </div>
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div id="upload-chapter">
                                <div class="preview">
                                    <img src="" alt="" class="image-preview">
                                </div>
                                <a id="up-chapter">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    Selecionar archivo/s
                                </a>
                            </div>
                            <input type="file" name="upload_chapter" id="inpt-chapter" data-id="{{ $manga->id }}" accept="zip" hidden>
                        </div>
                        <div class="u__bar">
                            <div class="u__progress"></div>
                        </div>
                    </div>
                    <div class="list" data-simplebar data-simplebar-auto-hide="false">
                        @if ($chapters->isNotEmpty())
                            @foreach ($chapters as $item)
                                <div class="item" id="m-{{ $item->id }}">
                                    <div class="name">{{ $item->name }}</div>
                                    <div class="actions">
                                        <a href="{{ URL::route('chapter_viewer.index', [
                                            'manga_slug' => $manga->slug,
                                            'chapter_slug' => $item->slug
                                        ]); }}" data-id="{{ $item->id }}" class="botn view" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-play" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 4v16l13 -8z" />
                                            </svg>
                                        </a>
                                        <a href="#" data-id="{{ $item->id }}" class="botn edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                <line x1="13.5" y1="6.5" x2="17.5" y2="10.5" />
                                            </svg>
                                        </a>
                                        <a href="#" data-id="{{ $item->id }}" class="botn delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <line x1="4" y1="7" x2="20" y2="7" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="sidebar">
                <div class="module options grid grid-cols-5 gap-4">
                    <div class="item col-span-3">
                        <select name="status">
                            <option value="published" {{ ($manga->status == "published")? 'selected': null }}>Publicado</option>
                            <option value="draft" {{ ($manga->status == "draft")? 'selected': null }}>Borrador</option>
                            <option value="private" {{ ($manga->status == "private")? 'selected': null }}>Privado</option>
                        </select>
                    </div>
                    <div class="item delete col-span-2">
                        <a href="#" class="mangaDelete" data-id="{{ $manga['id'] }}">Eliminar</a>
                    </div>
                    <div class="item save col-span-5">
                        <button type="submit">Guardar</button>
                    </div>
                </div>
                <div class="module cover">
                    <div class="dropzone">
                        <div id="choose">
                            <div class="preview">
                                <img src="{{ $manga->cover() }}" alt="" class="image-preview added">
                            </div>
                            <p class="text-drop">Elegir portada</p>
                        </div>
                        <input type="file" name="featured_image" id="image" accept="image/*" hidden>
                    </div>
                </div>
                <div class="module published">
                    <div class="group">
                        <label class="group-label">Publicado por</label>
                        <select name="user_id">
                            <option value="" selected disabled>Seleccionar usuario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ ($user->id == $manga->user_id)? 'selected': ''; }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="module categories">
                    <div class="group">
                        <label class="group-label">Categoría</label>
                        <div class="in-field">
                            <select name="categories[]" id="m-categories" multiple>
                                @foreach ($manga_categories as $item)
                                    <option value="{{ $item->id }}" selected>{{ $item->name }}</option>
                                @endforeach
                                @foreach ($categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="module tags">
                    <div class="group">
                        <label class="group-label">Tags</label>
                        <div class="in-field">
                            @php
                                if ($manga_tags) {
                                    $tags = [];
                                    foreach ($manga_tags as $tag) {
                                        $tags[] = $tag->name;
                                    }
                                }
                            @endphp
                            <input type="text" name="tags" id="m-tags" value="@php if($manga_tags){ echo implode(",", $tags); } @endphp">
                        </div>
                    </div>
                </div>
                <div class="module release_date">
                    <div class="group">
                        <label class="group-label">Fecha de lanzamiento</label>
                        <input type="text" name="release_date" id="field-date" value="{{ $manga->release_date }}" autocomplete="off">
                    </div>
                </div>
                <div class="module manga_type">
                    <div class="group">
                        <label class="group-label">Tipo de manga</label>
                        <select name="type_id" id="m-mangatype">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($manga_types as $type)
                                <option value="{{ $type->id }}" {{ ($type->id == $manga->type_id)? 'selected': ''; }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="module manga_status">
                    <div class="group">
                        <label class="group-label">Manga estado</label>
                        <select name="book_status_id" id="m-bookstatus">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($manga_book_status as $item)
                                <option value="{{ $item->id }}" {{ ($item->id == $manga->book_status_id)? 'selected': ''; }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="module manga_demography">
                    <div class="group">
                        <label class="group-label">Manga demografia</label>
                        <select name="demography_id" id="m-demography">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($manga_demographies as $item)
                                <option value="{{ $item->id }}" {{ ($item->id == $manga->demography_id)? 'selected': ''; }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div id="modalChapter" class="modal-chapter">
        <div class="md-title">
            <h4>Crear capítulo</h4>
            <div class="md-close">
                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </div>
        </div>
        <div class="md-content">
            <form action="{{ route('chapters.store', ['mangaid' => $manga->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="manga_id" value="{{ $manga->id }}">
                <div class="group">
                    <label for="ct-name">Name</label>
                    <input type="text" name="name" id="ct-name">
                </div>
                <div class="group">
                    <label for="ct-slug">Slug</label>
                    <input type="text" name="slug" id="ct-slug">
                </div>
                <div class="group range">
                    <label>Price</label>
                    <input type="number" name="price" id="ct-range">
                </div>
                <div class="group type">
                    <label>Tipo</label>
                    <div class="radios rdos">
                        <label for="type-1">
                            <input type="radio" name="chaptertype" value="manga" id="type-1" checked>
                            <div class="rdo">
                                <div class="inpt"></div>
                                <div class="name">Manga</div>
                            </div>
                        </label>
                        <label for="type-0">
                            <input type="radio" name="chaptertype" value="novel" id="type-0">
                            <div class="rdo">
                                <div class="inpt"></div>
                                <div class="name">Novela</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="group upload">
                    <label>Subir a</label>
                    <div class="disks rdos">
                        <label for="rdo-1">
                            <input type="radio" name="disk" value="public" id="rdo-1" checked>
                            <div class="rdo">
                                <div class="inpt"></div>
                                <div class="name">Local</div>
                            </div>
                        </label>
                        @if (env('FTP_HOST') && env('FTP_HOST') != "")
                            <label for="rdo-2">
                                <input type="radio" name="disk" value="ftp" id="rdo-2">
                                <div class="rdo">
                                    <div class="inpt"></div>
                                    <div class="name">FTP</div>
                                </div>
                            </label>
                        @endif
                        @if (env('SFTP_HOST') && env('SFTP_HOST') != "")
                        <label for="rdo-3">
                            <input type="radio" name="disk" value="sftp" id="rdo-3">
                            <div class="rdo">
                                <div class="inpt"></div>
                                <div class="name">FTP</div>
                            </div>
                        </label>
                        @endif
                        @if (env('S3_HOST') && env('S3_HOST') != "")
                            <label for="rdo-4">
                                <input type="radio" name="disk" value="s3" id="rdo-4">
                                <div class="rdo">
                                    <div class="inpt"></div>
                                    <div class="name">Amazon S3</div>
                                </div>
                            </label>
                        @endif
                    </div>
                </div>
                <div class="group hidden" id="t-novel">
                    <label for="ct-content">Contenido</label>
                    <div id="createChapterContent"></div>
                </div>
                <div class="group" id="t-manga">
                    <label for="ct-iamges">Images</label>
                    <div id="t-preview">
                        <div class="file">
                            <div class="choose">Agregar</div>
                            <input type="file" name="images[]" accept="image/*" multiple id="ct-images"/>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="md-buttons flex items-center space-x-2">
                    <button data-modal-hide="defaultModal" type="submit" class="text-white bg-vo-green font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-vo-green-over" id="crear">Crear</button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>