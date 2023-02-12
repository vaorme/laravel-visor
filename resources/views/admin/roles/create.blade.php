<x-admin-layout>
    <h1>crear role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="group">
            <label>Name</label>
            <input type="text" name="name">
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