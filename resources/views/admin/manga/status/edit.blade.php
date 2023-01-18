<x-admin-layout>
    <h1>editar status</h1>
    <form action="{{ route('manga_book_status.destroy', ['id' => $status->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $status->name) }}">
        </div>
        <div class="group">
            <label>Description</label>
            <textarea name="description" cols="30" rows="10">{{ old('description', $status->description) }}</textarea>
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