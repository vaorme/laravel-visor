<x-admin-layout>
    <x-slot:title>Producto | Crear</x-slot>
    <x-admin.bar title="Crear producto" :backTo="route('products.index')" />
    <div class="template-2">
        <div class="contain">
            <div class="frmo">
                <form action="{{ route('products.store') }}" method="POST" id="formProduct" enctype="multipart/form-data">
                    @csrf
                    <div class="row grid grid-cols-2 gap-10">
                        <div class="col">
                            <div class="group">
                                <label>Nombre</label>
                                <input type="text" name="name" value="{{ old('name') }}">
                            </div>
                            <div class="group">
                                <label>Tipo</label>
                                <select name="product_type">
                                    <option value="">Seleccionar tipo</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="group hidden" id="option-1">
                                <label>Cantidad de monedas</label>
                                <input type="number" name="amount_coins" value="{{ old('amount_coins') }}">
                            </div>
                            <div class="group hidden" id="option-2">
                                <label>Cantidad de dias sin publicidad</label>
                                <input type="number" name="days_without_ads" value="{{ old('days_without_ads') }}">
                            </div>
                            <div class="group">
                                <label>Precio</label>
                                <input type="number" name="price" value="{{ old('price') }}" step=".01">
                            </div>
                        </div>
                        <div class="col">
                            <div class="group">
                                <label for="">Elegir imagen</label>
                                <div class="dropzone">
                                    <div id="choose">
                                        <div class="preview">
                                            <img src="" alt="" class="image-preview">
                                        </div>
                                        <p class="text-drop">Elegir imagen</p>
                                    </div>
                                    <input type="file" name="image" id="m-image" accept="image/*" hidden>
                                </div>
                            </div>
                        </div>
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
                    <button type="submit" class="botn success">Crear</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>