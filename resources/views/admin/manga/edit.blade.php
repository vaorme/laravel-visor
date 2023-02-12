<x-admin-layout>
    <x-admin.bar :title="$manga->name" :backTo="route('manga.index')"/>
    <div class="frmo fm-manga">
        <form action="{{ route('manga.update', ['id' => $manga->id]) }}" method="POST" enctype="multipart/form-data">
            <div class="main">
                @csrf
                @method('PATCH')
                <div class="section head">
                    <div class="item name">
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name', $manga['name']) }}">
                    </div>
                    <div class="item slug">
                        <label>Slug</label>
                        <input type="text" name="slug">
                    </div>
                    <div class="item alt-name">
                        <label>Alternative Name</label>
                        <input type="text" name="alternative_name">
                    </div>
                </div>
                <div class="section description">
                    <div class="item">
                        <label>Description</label>
                        <input type="text" name="description">
                    </div>
                </div>
                <div class="group">
                    <label>Imagen destacada</label>
                    <input type="file" name="featured_image">
                </div>
                <div class="group">
                    <label>Tags</label>
                    <input type="text" name="tags">
                </div>
                <div class="group">
                    <label>Fecha de lanzamiento</label>
                    <input type="date" name="release_date">
                </div>
                
                <div class="group">
                    <label>Publicado por</label>
                    <select name="user_id">
                        <option value="" selected disabled>Seleccionar usuario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ ($user->id == $manga->user_id)? 'selected': ''; }}>{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="group">
                    <label>Tipo de manga</label>
                    <select name="type_id">
                        <option value="" selected disabled>Seleccionar tipo</option>
                        
                        @foreach ($manga_types as $type)
                            <option value="{{ $type->id }}" {{ ($type->id == $manga->type_id)? 'selected': ''; }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="group">
                    <label>Manga estado</label>
                    <select name="book_status_id">
                        <option value="" selected disabled>Seleccionar manga estado</option>
                        @foreach ($manga_book_status as $item)
                            <option value="{{ $item->id }}" {{ ($item->id == $manga->book_status_id)? 'selected': ''; }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="group">
                    <label>Manga demografia</label>
                    <select name="demography_id">
                        <option value="" selected disabled>Seleccionar manga estado</option>
                        @foreach ($manga_demographies as $item)
                            <option value="{{ $item->id }}" {{ ($item->id == $manga->demography_id)? 'selected': ''; }}>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- @error('title')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror --}}
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
                <button type="submit">Enviar</button>
            </div>
            <div class="sidebar">
                <div class="options grid grid-cols-5 gap-4">
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
                        <button type="submit">Enviar</button>
                    </div>
                </div>
            </div>
        </form>
        <h2>Upload chapter</h2>
        <form action="{{ route('uploadChapter.store', ['mangaid' => $manga['id']]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h4>Capitulos</h4>
            <div class="group">
                <label>Subir capitulo/s</label>
                <input type="file" name="chapters">
                <span>ejemplo imagen con estructura del zip</span>
            </div>
            <button type="submit">Subir</button>
        </form>
        <h2>Create chapter</h2>
        <form action="{{ route('chapters.store', ['mangaid' => $manga['id']]) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="group">
                <label>Name</label>
                <input type="text" name="name">
            </div>
            <div class="group">
                <label>Slug</label>
                <input type="text" name="slug">
            </div>
            <div class="group">
                <label>content</label>
                <textarea name="content" id="" cols="30" rows="10"></textarea>
            </div>
            <div class="group">
                <label>Price</label>
                <input type="text" name="price">
            </div>
            <div class="group">
                <label>Images</label>
                <input type="file" name="images[]" multiple/>
            </div>
            {{-- @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror --}}
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
        </form>
    </div>
</x-admin-layout>