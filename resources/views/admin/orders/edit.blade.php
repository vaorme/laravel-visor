<x-admin-layout>
    <x-slot:title>Producto | Editar</x-slot>
    <x-admin.bar title="Editar orden" :backTo="route('orders.index')" />
    <div class="template-2 order">
        <div class="contain">
            <div class="frmo">
                <form action="{{ route('orders.update', ['id' => $edit->id]) }}" method="POST" id="formProduct" enctype="multipart/form-data">
                    @csrf
                    @method("PATCH")
                    <div class="row grid grid-cols-2 gap-10">
                        <div class="col">
                            <div class="group">
                                <label>Nombre</label>
                                <input type="text" name="name" value="{{ old('name', $edit->name) }}" disabled="disabled">
                            </div>
                            <div class="group">
                                <label>Correo</label>
                                <input type="email" name="email" value="{{ old('email', $edit->email) }}" disabled="disabled">
                            </div>
                            <div class="group">
                                <label>Estado</label>
                                <select name="status">
                                    <option value="COMPLETED" {{ ($edit->status == "COMPLETED")? 'selected': null }}>Completado</option>
                                    <option value="PENDING" {{ ($edit->status == "PENDING")? 'selected': null }}>Pendiente</option>
                                    <option value="CREATED" {{ ($edit->status == "CREATED")? 'selected': null }}>Creado</option>
                                    <option value="FAILED" {{ ($edit->status == "FAILED")? 'selected': null }}>Fallido</option>
                                    <option value="CANCELLED" {{ ($edit->status == "CANCELLED")? 'selected': null }}>Cancelado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="order__details">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="check__title">Estado:</td>
                                            <td class="check__element">{{ $edit->status }}</td>
                                        </tr>
                                        @foreach ($edit->products as $item)
                                            <tr>
                                                <td class="check__title">Producto:</td>
                                                <td class="check__element">{{ $item->product->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="check__title">Cantidad:</td>
                                                <td class="check__element">1</td>
                                            </tr>
                                            <tr>
                                                <td class="check__title">Tipo:</td>
                                                <td class="check__element">{{ $item->product->type->name }}</td>
                                            </tr>
                                            <tr>
                                                @switch($item->product->type->id)
                                                    @case(1)
                                                        <td class="check__title">Monedas:</td>
                                                        <td class="check__element">{{ $item->product->coins }}</td>
                                                        @break
                                                    @case(2)
                                                        <td class="check__title">Dias:</td>
                                                        <td class="check__element">{{ $item->product->days_without_ads }}</td>
                                                        @break
                                                    @default
                                                @endswitch
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="check__title">Usuario:</td>
                                            <td class="check__element">{{ $edit->user->username }}</td>
                                        </tr>
                                        <tr>
                                            <td class="check__title">Precio:</td>
                                            <td class="check__element">$ {{ number_format($edit->total, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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