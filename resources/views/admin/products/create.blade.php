<x-admin-layout>
    <h1>crear producto</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="group">
            <label>Name</label>
            <input type="text" name="name">
        </div>
        <div class="group">
            <label>Price</label>
            <input type="number" name="price">
        </div>
        <div class="group">
            <label>Coins</label>
            <input type="number" name="coins">
        </div>
        <div class="group">
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" max="100">
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