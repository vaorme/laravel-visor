<x-admin-layout>
    <h1>editar Permiso</h1>
    <form action="{{ route('permissions.update', ['id' => $edit->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $edit->name) }}">
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