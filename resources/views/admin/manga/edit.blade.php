<x-admin-layout>
    <x-admin.bar :title="$manga->name" :backTo="route('manga.index')" />
    <div class="frmo fm-manga">
        <form action="{{ route('manga.update', ['id' => $manga->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="errores">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="main">
                <div class="section head">
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
                <div class="section description">
                    <div class="item">
                        <label>Description</label>
                        <textarea name="description" id="" cols="30" rows="5">{{ old('description', $manga['description']) }}</textarea>
                    </div>
                </div>
                <div class="section chapters">
                    <div class="buttons">
                        <a href="#" id="ct-chapter">
                            Crear capítulo
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </a>
                        <div class="upload">
                            <div id="upload-chapter">
                                <div class="preview">
                                    <img src="" alt="" class="image-preview">
                                </div>
                                <a id="up-chapter">
                                    Subir capítulo
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="12" y1="5" x2="12" y2="19" />
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                    </svg>
                                </a>
                            </div>
                            <input type="file" name="upload_chapter" id="inpt-chapter" data-id="{{ $manga->id }}" accept="zip" hidden>
                        </div>
                    </div>
                    <div class="list" data-simplebar data-simplebar-auto-hide="false">
                        @if ($chapters->isNotEmpty())
                            @foreach ($chapters as $item)
                                <div class="item" id="m-{{ $item->id }}">
                                    <div class="name">{{ $item->name }}</div>
                                    <div class="actions">
                                        <a href="#" data-id="{{ $item->id }}" class="botn view" onclick="test()">
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
                        @else
                            no hay elementos
                        @endif
                    </div>
                </div>
                {{-- @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror --}}
                
            </div>
            <div class="sidebar">
                <div class="module options grid grid-cols-5 gap-4">
                    <div class="item col-span-3">
                        <select name="status">
                            <option value="published" selected>Publicado</option>
                            <option value="draft">Borrador</option>
                            <option value="private">Privado</option>
                        </select>
                    </div>
                    <div class="item delete col-span-2">
                        <a href="{{ route('manga.destroy', ['id' => $manga['id']]) }}">Eliminar</a>
                    </div>
                    <div class="item save col-span-5">
                        <button type="submit">Guardar</button>
                    </div>
                </div>
                <div class="module cover">
                    <div class="dropzone">
                        <div id="choose">
                            <div class="preview">
                                <img src="" alt="" class="image-preview">
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
                    <div class="group in-choices in-selects">
                        <label class="group-label">Categoría</label>
                        <div class="in-field">
                            <select name="categories[]" class="select-categories" multiple>
                                @foreach ($categories as $item)
                                    <option value="{{ $item->slug }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="module tags">
                    <div class="group in-choices">
                        <label class="group-label">Tags</label>
                        <div class="in-field">
                            <input type="text" name="tags" class="input-tags" value="uno, dos, tes">
                        </div>
                    </div>
                </div>
                <div class="module release_date">
                    <div class="group">
                        <label class="group-label">Fecha de lanzamiento</label>
                        <input type="text" name="release_date" id="field-date">
                    </div>
                </div>
                <div class="module manga_type">
                    <div class="group">
                        <label class="group-label">Tipo de manga</label>
                        <select name="type_id">
                            <option value="" selected disabled>Seleccionar tipo</option>
                            
                            @foreach ($manga_types as $type)
                                <option value="{{ $type->id }}" {{ ($type->id == $manga->type_id)? 'selected': ''; }}>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="module manga_status">
                    <div class="group">
                        <label class="group-label">Manga estado</label>
                        <select name="book_status_id">
                            <option value="" selected disabled>Seleccionar manga estado</option>
                            @foreach ($manga_book_status as $item)
                                <option value="{{ $item->id }}" {{ ($item->id == $manga->book_status_id)? 'selected': ''; }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="module manga_demography">
                    <div class="group">
                        <label class="group-label">Manga demografia</label>
                        <select name="demography_id">
                            <option value="" selected disabled>Seleccionar manga estado</option>
                            @foreach ($manga_demographies as $item)
                                <option value="{{ $item->id }}" {{ ($item->id == $manga->demography_id)? 'selected': ''; }}>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <!-- Main modal -->
        <div id="create-chapter" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 w-full pt-20 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">
            <div class="overlay absolute left-0 top-0 w-full h-full bg-black opacity-50"></div>
            <div class="content relative w-full h-full max-w-2xl m-auto">
                <!-- Modal content -->
                <div class="box relative rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="bx-title flex items-start justify-between p-4 rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Crear capítulo</h3>
                        <button type="button" id="close-btn" class="text-white bg-transparent hover:bg-zinc-800 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="defaultModal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="bx-body p-6 space-y-6">
                        <form action="{{ route('chapters.store', ['mangaid' => $manga->id]) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="group">
                                <label for="ct-name">Name</label>
                                <input type="text" name="name" id="ct-name" required>
                            </div>
                            <div class="group">
                                <label for="ct-slug">Slug</label>
                                <input type="text" name="slug" id="ct-slug" required>
                            </div>
                            <div class="group radios">
                                <label for="type-0">
                                    <input type="radio" name="chaptertype" value="0" id="type-0">
                                    <div class="rdo">
                                        <div class="name">Novela</div>
                                        <div class="inpt"></div>
                                    </div>
                                </label>
                                <label for="type-1">
                                    <input type="radio" name="chaptertype" value="1" id="type-1" checked>
                                    <div class="rdo">
                                        <div class="name">Manga</div>
                                        <div class="inpt"></div>
                                    </div>
                                </label>
                            </div>
                            <div class="group hidden" id="t-novel">
                                <label for="ct-content">Contenido</label>
                                <textarea name="content" id="" cols="30" rows="6" id="ct-content"></textarea>
                            </div>
                            <div class="group" id="t-manga">
                                <label for="ct-iamges">Images</label>
                                <input type="file" name="images[]" multiple id="ct-images" required/>
                            </div>
                            <div class="group range">
                                <label>Price</label>
                                <input type="number" name="price" id="ct-range">
                            </div>
                            <!-- Modal footer -->
                            <div class="buttons flex items-center space-x-2">
                                <button data-modal-hide="defaultModal" type="submit" class="text-white bg-vo-green font-medium rounded-lg text-sm px-5 py-2.5 text-center hover:bg-vo-green-over" id="crear">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>