<x-admin-layout>
    <x-admin.bar title="Crear usuario" :backTo="route('users.index')" />
    <div class="template-2">
        <div class="contain">
            <div class="frmo">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="row grid grid-cols-2 gap-10">
                        <div class="col">
                            <div class="group">
                                <label>Nombre</label>
                                <input type="text" name="name">
                            </div>
                            <div class="group">
                                <label>Nombre de usuario</label>
                                <input type="text" name="username">
                            </div>
                            <div class="group">
                                <label>Correo</label>
                                <input type="email" name="email">
                            </div>
                            <div class="group">
                                <label>País</label>
                                <select name="country">
                                    <option value="" selected>Seleccionar país</option>
                                    @foreach ($countries as $item)
                                        <option value="{{ $item->code }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="group">
                                <label>Contraseña</label>
                                <input type="password" name="password">
                            </div>
                            <div class="group">
                                <label>Confirmar contraseña</label>
                                <input type="password" name="password_confirmation">
                            </div>
                        </div>
                        <div class="col">
                            <div class="group">
                                <label>Avatar</label>
                                <input type="file" name="avatar">
                            </div>
                            <div class="group">
                                <label>Portada</label>
                                <input type="file" name="cover">
                            </div>
                            <div class="group">
                                <label>Mensaje</label>
                                <textarea name="message" cols="30" rows="4"></textarea>
                            </div>
                            <div class="group perfil-publico">
                                <label>Perfil publico</label>
                                <div class="rdos">
                                    <label for="op-1">
                                        <input type="radio" id="op-1" name="public_profile" value="1" checked>
                                        <div class="rd-input">
                                            <div class="rd-option">
                                                Si
                                            </div>
                                        </div>
                                    </label>
                                    <label for="op-2">
                                        <input type="radio" id="op-2" name="public_profile" value="0">
                                        <div class="rd-input">
                                            <div class="rd-option">
                                                No
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="fields redes">
                                <h4>Redes</h4>
                                <div class="group">
                                    <div class="icon"></div>
                                    <input type="text" name="red_1">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="28" height="28" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                    </button>
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