<x-admin-layout>
    <h1>crear demography</h1>
    <form action="{{ route('manga_demography.store') }}" method="POST">
        @csrf
        <div class="group">
            <label>Name</label>
            <input type="text" name="name">
        </div>
        <div class="group">
            <label>Description</label>
            <input type="text" name="description">
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