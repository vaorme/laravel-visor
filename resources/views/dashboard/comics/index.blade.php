<x-dashboard-layout>
<!-- Page header -->
<div class="page-header d-print-none text-white">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Overview</div>
                <h2 class="page-title">Comics</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('comics.create') }}" class="btn btn-primary d-sm-inline-block" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                            Agregar
                        </a>
                    </div>
              </div>
        </div>
    </div>
</div>
<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card card-borderless">
            <div class="card-body">
                <div class="row">
                    <div class="col-auto">
                        <select type="text" class="form-select" id="select-labels" value="">
							<option value="" data-custom-properties="&lt;span class=&quot;badge bg-muted&quot;&gt;&lt;/span&gt;" selected>Estado</option>
                            <option value="published" data-custom-properties="&lt;span class=&quot;badge bg-success&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'published')? 'selected' : null }}>Publicado</option>
							<option value="programmed" data-custom-properties="&lt;span class=&quot;badge bg-primary&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'programmed')? 'selected' : null }}>Programado</option>
                            <option value="draft" data-custom-properties="&lt;span class=&quot;badge bg-warning&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'draft')? 'selected' : null }}>Borrador</option>
                            <option value="private" data-custom-properties="&lt;span class=&quot;badge bg-purple&quot;&gt;&lt;/span&gt;" {{ (request()->status == 'private')? 'selected' : null }}>Privado</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <form action="{{ url()->full() }}" method="GET">
                            <div class="input-icon mb-3">
                                <input type="text" name="s" class="form-control" placeholder="Buscar..." value="{{ (isset(request()->s) && !empty(request()->s))? request()->s : null }}">
                                <span class="input-icon-addon">
                                  <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @if ($comics->isNotEmpty())
            <div id="table-default" class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Portada</th>
                        <th><button class="table-sort" data-sort="sort-name">Nombre</button></th>
                        <th>Estado</th>
                        <th>Tipo</th>
                        <th><button class="table-sort" data-sort="sort-score">Puntaje</button></th>
                        <th><button class="table-sort" data-sort="sort-chapters">Capítulos</button></th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="table-tbody">
                            @foreach ($comics as $comic)
                                <tr class="align-middle">
                                    <td>
                                        <a href="{{ route('comics.edit', ['id' => $comic->id]) }}" class="d-inline-block">
                                            <div class="avatar avatar-md img-responsive-1x1 rounded-3 border" style="background-image: url({{ $comic->cover() }})"></div>
                                        </a>
                                    </td>
                                    <td class="sort-name">
                                        <a href="{{ route('comics.edit', ['id' => $comic->id]) }}" class="d-inline-block">
                                            <h4 class="d-block m-0 text-dark" style="max-width: 300px">{{ $comic->name }}</h4>
                                        </a>
                                    </td>
                                    <td>
                                        @switch($comic->status)
                                            @case("published")
                                                <span class="badge bg-green-lt p-2">Publicado</span>
                                                @break
                                            @case("draft")
                                                <span class="badge bg-orange-lt p-2">Borrador</span>
                                                @break
                                            @case("private")
                                                <span class="badge bg-blue-lt p-2">Privado</span>
                                                @break
                                            @default
                                                
                                        @endswitch
                                    </td>
                                    @php
                                        $colors = ["blue", "indigo", "purple", "pink", "muted"];
                                        $random_key = array_rand($colors);
                                        $random_item = $colors[$random_key];
                                    @endphp
                                    <td class="sort-type">
                                        @if (isset($comic->type))
                                            <span class="badge bg-{{ $random_item }}-lt p-2">{{ $comic->type->name }}</span>
                                        @endif
                                    </td>
                                    <td class="sort-score">{{ round($comic->rating->avg('rating'), 1, PHP_ROUND_HALF_DOWN) }}</td>
                                    <td class="sort-chapters">{{ $comic->chapters->count() }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <div class="botn" title="Editar" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <a href="{{ route('comics.edit', ['id' => $comic->id]) }}" class="btn btn-bitbucket w-auto btn-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-minus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                                        <path d="M13.5 6.5l4 4"></path>
                                                        <path d="M16 19h6"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="botn" title="Eliminar" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <a href="javascript:void(0)" class="btn btn-pinterest w-auto btn-icon" data-id="{{ $comic->id }}" data-bs-toggle="modal" data-bs-target="#modal-destroy">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                        <path d="M4 7l16 0"></path>
                                                        <path d="M10 11l0 6"></path>
                                                        <path d="M14 11l0 6"></path>
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        
                    </tbody>
                </table>
            </div>
            @php
                $comics->appends(request()->input())->links();
            @endphp
            {{ $comics->links('dashboard.components.pagination') }}
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="border-top p-4">
                            <div class="card card-inactive">
                                <div class="card-body text-center">
                                    <p>No se han encontrado elementos</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="modal modal-blur fade" id="modal-destroy" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="modal-status bg-danger"></div>
                    <div class="modal-body text-center py-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" /><path d="M12 9v4" /><path d="M12 17h.01" /></svg>
                        <h3>¿Está seguro?</h3>
                        <div class="text-muted">¿Realmente quieres eliminar este elemento? No se puede deshacer.</div>
                    </div>
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                <div class="col">
                                    <button class="btn w-100" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                                <div class="col">
                                    <button class="position-relative btn btn-danger w-100" id="buttonConfirm">
                                        Sí, eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const list = new List('table-default', {
                    sortClass: 'table-sort',
                    listClass: 'table-tbody',
                    valueNames: [ 'sort-name', 'sort-score', 'sort-chapters',
                        { attr: 'data-date', name: 'sort-date' },
                        { attr: 'data-progress', name: 'sort-progress' },
                        'sort-quantity'
                    ]
                });
                // :SELECT STATUS
                let tmSelect;
                if(window.TomSelect){
                    tmSelect = new TomSelect(el = document.getElementById('select-labels'), {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
                        controlInput: '<input>',
                        render:{
                            item: function(data,escape) {
                                if( data.customProperties ){
                                    return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                                }
                                return '<div>' + escape(data.text) + '</div>';
                            },
                            option: function(data,escape){
                                if( data.customProperties ){
                                    return '<div><span class="dropdown-item-indicator">' + data.customProperties + '</span>' + escape(data.text) + '</div>';
                                }
                                return '<div>' + escape(data.text) + '</div>';
                            },
                        },
                    })
                }
                
                tmSelect.on('change', function(value){
                    if(value == ""){
                        return true;
                    }

                    let newUrl = new URL(document.location);
                    let s = newUrl.searchParams.get('s');
                    let status = value;
                    if(s && s != ""){
                        newUrl.searchParams.delete("s");
                        newUrl.searchParams.append("s", s);
                    }else{
                        newUrl.searchParams.delete("s");
                    }
                    if(status && status != ""){
                        newUrl.searchParams.delete("status");
                        newUrl.searchParams.append("status", status);
                    }else{
                        newUrl.searchParams.delete("status");
                    }

                    window.location.href = newUrl.href;
                });
            })
        </script>
    </div>
</div>
</x-dashboard-layout>