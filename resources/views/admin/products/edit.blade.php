<x-admin-layout>
    <h1>Producto: {{ $edit->name }}</h1>
    <form action="{{ route('products.update', ['id' => $edit->id]) }}" method="POST">
        @csrf
        @method('PATCH')
        <div class="group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $edit->name) }}">
        </div>
        <div class="group">
            <label>Price</label>
            <input type="number" name="price" value="{{ old('price', $edit->price) }}">
        </div>
        <div class="group">
            <label>Coins</label>
            <input type="number" name="coins" value="{{ old('coins', $edit->coins) }}">
        </div>
        <div class="group">
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" max="100" value="{{ old('quantity', $edit->quantity) }}">
        </div>
        <div class="group">
            <select name="product_type_id">
                @foreach ($types as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
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