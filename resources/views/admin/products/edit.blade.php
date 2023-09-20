<x-admin-layout>
    <x-slot:title>Producto | Editar</x-slot>
    <x-admin.bar title="Editar producto" :backTo="route('products.index')" />
    <div class="template-2">
        <div class="contain">
            <div class="frmo">
                <form action="{{ route('products.update', ['id' => $edit->id]) }}" method="POST" id="formProduct" enctype="multipart/form-data">
                    @csrf
                    @method("PATCH")
                    <div class="row grid grid-cols-2 gap-10">
                        <div class="col">
                            <div class="group">
                                <label>Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $edit->name) }}">
                            </div>
                            <div class="group">
                                <label>Tipo</label>
                                <select name="product_type">
                                    <option value="">Seleccionar tipo</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}" {{ ($edit->product_type_id == $type->id)? 'selected': null }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="group {{ ($edit->product_type_id == 1)? '' : ' hidden' }}" id="option-1">
                                <label>Cantidad de monedas</label>
                                <input type="number" name="amount_coins" value="{{ old('amount_coins', $edit->coins) }}">
                            </div>
                            <div class="group {{ ($edit->product_type_id == 2)? '' : ' hidden' }}" id="option-2">
                                <label>Cantidad de dias sin publicidad</label>
                                <input type="number" name="days_without_ads" value="{{ old('days_without_ads', $edit->days_without_ads) }}">
                            </div>
                            <div class="group">
                                <label>Precio</label>
                                <input type="number" name="price" value="{{ old('price', $edit->price) }}" step=".01">
                            </div>
                        </div>
                        <div class="col">
                            <div class="group">
                                <label for="">Elegir imagen</label>
                                <div class="dropzone">
                                    <div id="choose">
                                        <div class="preview">
                                            <img src="{{ ($edit->image && !empty($edit->image))? asset("storage/$edit->image") : "" }}" alt="" class="image-preview{{ ($edit->image && !empty($edit->image))? ' added': null }}">
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
                    <button type="submit" class="botn success">Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>