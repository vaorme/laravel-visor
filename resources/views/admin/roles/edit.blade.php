<x-admin-layout>
    <h1>editar Role</h1>
    @php
        $looPermissions = $edit->getAllPermissions()->toArray();
        $currentPermissions = [];
        foreach($looPermissions as $p){
            $currentPermissions[] = $p['name'];
        }
    @endphp
    <form action="{{ route('roles.update', ['id' => $edit->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $edit->name) }}">
        </div>
        <div class="group">
            <label>Asignar permisos al rol</label>
            @php
                $userPermissions = [];
                $categoriesPermissions = [];
                $tagsPermissions = [];
                $permsPermissions = [];
                $rolesPermissions = [];
                $ranksPermissions = [];
                $mangaPermissions = [];
                $mangaDemographyPermissions = [];
                $mangaTypesPermissions = [];
                $mangaBookPermissions = [];
                $chaptersPermissions = [];
                $productsPermissions = [];
                $productTypesPermissions = [];
                $ordersPermissions = [];
                $settingsPermissions = [];
            @endphp
            @foreach ($permissions as $p)
                @switch($p->name)
                    @case(str_contains($p->name, 'users.'))
                        @php
                            $userPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case(str_contains($p->name, 'categories.'))
                        @php
                            $categoriesPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'tags.'))
                        @php
                            $tagsPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'permissions.'))
                        @php
                            $permsPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'roles.'))
                        @php
                            $rolesPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'ranks.'))
                        @php
                            $ranksPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'manga.'))
                        @php
                            $mangaPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'manga_types.'))
                        @php
                            $mangaTypesPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'manga_demography.'))
                        @php
                            $mangaDemographyPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'manga_book_status.'))
                        @php
                            $mangaBookPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'chapters.'))
                        @php
                            $chaptersPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'products.'))
                        @php
                            $productsPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'product_types.'))
                        @php
                            $productTypesPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'orders.'))
                        @php
                            $ordersPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @case (str_contains($p->name, 'settings.'))
                        @php
                            $settingsPermissions[$p->id] = $p->name
                        @endphp
                        @break
                    @default
                @endswitch
            @endforeach
            <div class="table permisos">
                <div class="t-body">
                    <div class="perms user">
                        <h4>Users</h4>
                        @foreach ($userPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms categories">
                        <h4>Categories</h4>
                        @foreach ($categoriesPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms tags">
                        <h4>Tags</h4>
                        @foreach ($tagsPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms permisos">
                        <h4>Permisos</h4>
                        @foreach ($permsPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms roles">
                        <h4>Roles</h4>
                        @foreach ($rolesPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms rangos">
                        <h4>Rangos</h4>
                        @foreach ($ranksPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms manga">
                        <h4>Manga</h4>
                        @foreach ($mangaPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms manga_demography">
                        <h4>Manga Demography</h4>
                        @foreach ($mangaDemographyPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms types">
                        <h4>Manga Types</h4>
                        @foreach ($mangaTypesPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms chapters">
                        <h4>Manga Chapters</h4>
                        @foreach ($chaptersPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms products">
                        <h4>Products</h4>
                        @foreach ($productsPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms products">
                        <h4>Product types</h4>
                        @foreach ($productTypesPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms orders">
                        <h4>Orders</h4>
                        @foreach ($ordersPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="perms settings">
                        <h4>Settings</h4>
                        @foreach ($settingsPermissions as $key => $value)
                            <div class="permiso">
                                <label for="pid-{{ $key }}">
                                    <input type="checkbox" name="permisos[]" value="{{ $value }}" id="pid-{{ $key }}" @if (in_array($value, $currentPermissions)) checked @endif>
                                    <span class="name">{{ $value }}</span>
                                </label>
                            </div>
                        @endforeach
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
        <button type="submit">Enviar</button>
    </form>
    <style>
        .table .t-body{
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }
    </style>
</x-admin-layout>