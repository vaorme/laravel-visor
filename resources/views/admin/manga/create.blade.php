<x-admin-layout>
    <x-admin.bar :backTo="route('manga.index')" />
    <div class="frmo fm-manga fm-create">
        <form action="{{ route('manga.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                        <input type="text" name="name" id="m-name">
                    </div>
                    <div class="item slug">
                        <label for="m-slug">Slug</label>
                        <input type="text" name="slug" id="m-slug">
                    </div>
                    <div class="item alt-name">
                        <label for="m-altname">Alternative Name</label>
                        <input type="text" name="alternative_name" id="m-altname">
                    </div>
                </div>
                <div class="section description">
                    <div class="item">
                        <label>Description</label>
                        <textarea name="description" id="m-description" cols="30" rows="5"></textarea>
                    </div>
                </div>
                
            </div>
            <div class="sidebar">
                <div class="module options grid grid-cols-5 gap-4">
                    <div class="item col-span-3">
                        <select name="status" id="m-status">
                            <option value="published" selected>Publicado</option>
                            <option value="draft">Borrador</option>
                            <option value="private">Privado</option>
                        </select>
                    </div>
                    <div class="item delete col-span-2">
                        <a href="{{ route('manga.index') }}">Volver</a>
                    </div>
                    <div class="item save col-span-5">
                        <button type="submit">Crear</button>
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
                        <input type="file" name="featured_image" id="m-image" accept="image/*" hidden>
                    </div>
                </div>
                <div class="module published">
                    <div class="group">
                        <label class="group-label">Publicado por</label>
                        <select name="user_id" id="m-published">
                            <option value="" selected disabled>Seleccionar usuario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ ($user->id == Auth::id())? 'selected': ''; }}>{{ $user->username }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="module categories">
                    <div class="group">
                        <label class="group-label">Categorías</label>
                        <div class="in-field">
                            <select name="categories[]" id="m-categories" multiple>
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
                            <input type="text" name="tags" id="m-tags">
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
                        <select name="type_id" id="m-mangatype">
                            <option value="" selected disabled>Seleccionar</option>
                            @foreach ($manga_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
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
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>