<x-admin-layout>
    <h1>crear manga</h1>
    <form action="{{ route('manga.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="group">
            <label>Imagen destacada</label>
            <input type="file" name="featured_image">
        </div>
        <div class="group">
            <label>Name</label>
            <input type="text" name="name">
        </div>
        <div class="group">
            <label>Alternative Name</label>
            <input type="text" name="alternative_name">
        </div>
        <div class="group">
            <label>Slug</label>
            <input type="text" name="slug">
        </div>
        <div class="group">
            <label>Description</label>
            <input type="text" name="description">
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
            <label>Estado</label>
            <select name="status">
                <option value="published" selected>Publicado</option>
                <option value="draft">Borrador</option>
                <option value="private">Privado</option>
            </select>
        </div>
        <div class="group">
            <label>Publicado por</label>
            <select name="user_id">
                <option value="" selected disabled>Seleccionar usuario</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ ($user->id == Auth::id())? 'selected': ''; }}>{{ $user->username }}</option>
                @endforeach
            </select>
        </div>
        <div class="group">
            <label>Tipo de manga</label>
            <select name="type_id">
                <option value="" selected disabled>Seleccionar tipo</option>
                
                @foreach ($manga_types as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="group">
            <label>Manga estado</label>
            <select name="book_status_id">
                <option value="" selected disabled>Seleccionar manga estado</option>
                @foreach ($manga_book_status as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="group">
            <label>Manga demografia</label>
            <select name="demography_id">
                <option value="" selected disabled>Seleccionar manga estado</option>
                @foreach ($manga_demographies as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
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
    </form>
</x-admin-layout>