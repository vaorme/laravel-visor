<x-dashboard-layout>
    @php
        $isEdit = isset($product)? true : false;
    @endphp
    <!-- Page header -->
    <div class="page-header d-print-none text-white">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Product</div>
                    <h2 class="page-title">{{ $isEdit? 'Editar': 'Agregar' }}</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <button class="btn-submit btn btn-{{ $isEdit? 'primary' : 'success' }} d-sm-inline-block" >
                                {!! $isEdit? '
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brackets" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 4h-3v16h3"></path>
                                    <path d="M16 4h3v16h-3"></path>
                                </svg>
                                Actualizar': '
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                                Crear' !!}
                            </button>
                        </div>
                  </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body products">
        <div class="container-xl">
            <form action="{{ $isEdit? route('products.update', ['id' => $product->id]) : route('products.store') }}" class="frmo{{ $isEdit? ' update' : '' }}" method="post" novalidate enctype="multipart/form-data">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif
                @if (isset($product))
                    <input type="hidden" name="comic_id" value="{{ $product->id }}">
                @endif
                @if (Session::has('success'))
                    <div class="space-alert alert alert-success">
                        <ul>
                            <li>{!! \Session::get('success') !!}</li>
                        </ul>
                    </div>
                    <script>
                        let alerta = document.querySelector('.space-alert');
                        setTimeout(() => {
                            alerta.remove();
                        }, 4000);
                    </script>
                @endif
                @if (Session::has('error'))
                    <div class="space-alert alert alert-danger">
                        <ul>
                            <li>{!! \Session::get('error') !!}</li>
                        </ul>
                    </div>
                    <script>
                        let alerta = document.querySelector('.space-alert');
                        setTimeout(() => {
                            alerta.remove();
                        }, 4000);
                    </script>
                @endif
                @if ($errors->any())
                    <div class="errores">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                <div class="row row-cards">
                    <div class="col-lg-8">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card card-borderless">
                                    <div class="card-body p-3">
                                        <div class="row row-cards">
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="floating-input" name="name" autocomplete="off" value="{{ $isEdit? $product->name : '' }}" required>
                                                    <label for="floating-input">Nombre</label>
                                                    <div class="invalid-feedback">
                                                        Campo <b>Nombre</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="floating-input" name="price" autocomplete="off" value="{{ $isEdit? $product->price : '' }}" required>
                                                    <label for="floating-input">Precio</label>
                                                    <div class="invalid-feedback">
                                                        Campo <b>Precio</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <select name="product_type" class="form-control" id="select-types">
                                                        <option value="">Seleccionar tipo</option>
                                                        @foreach ($types as $type)
                                                            <option value="{{ $type->id }}" {{ ($isEdit)? ($product->product_type_id == $type->id)? 'selected': null : '' }}>{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="floating-input">Precio</label>
                                                    <div class="invalid-feedback">
                                                        Campo <b>Precio</b> es requerido
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-none" id="tab-coins">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="floating-input" name="coins" autocomplete="off" value="{{ $isEdit? $product->coins : '' }}">
                                                    <label for="floating-input">Monedas</label>
                                                </div>
                                            </div>
                                            <div class="col-12 d-none" id="tab-days">
                                                <div class="form-floating">
                                                    <input type="number" class="form-control" id="floating-input" name="days_without_ads" autocomplete="off" value="{{ $isEdit? $product->days_without_ads : '' }}">
                                                    <label for="floating-input">Días sin Publicidad</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card card-borderless">
                                    <div class="card-body p-0">
                                        <div class="accordion border-0" id="sidebar-accordion">
                                            <div class="accordion-item border-0 border-bottom">
                                                <h2 class="accordion-header" id="heading-1">
                                                    <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">Portada</button>
                                                </h2>
                                                <div id="collapse-1" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
                                                        <div class="own-dropzone">
                                                            <div class="dz-choose">
                                                                <div class="dz-preview">
                                                                    <img src="{{ $isEdit && isset($product->image)? asset('storage/'.$product->image) : '' }}" alt="" class="dz-image{{ $isEdit && isset($product->image)? ' show' : '' }}">
                                                                    <div class="dz-change{{ ($isEdit && !isset($product->image)) || (!$isEdit) ? ' visually-hidden' : '' }}">
                                                                        <a href="javascript:void(0)" class="btn btn-pinterest w-auto">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-exchange-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                <path d="M17 10h-14l4 -4"></path>
                                                                                <path d="M7 14h14l-4 4"></path>
                                                                            </svg>
                                                                            Cambiar portada
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <p class="dz-text">Elegir portada</p>
                                                            </div>
                                                            <input type="file" name="image" class="dz-input" accept="image/*" hidden>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // * TOMSELECT
                    let tmSelectTypes;
                    const detectChange = (value) => {
                        typeValue = value;
                        if(typeValue == 1){
                            const tabCoins = document.getElementById('tab-coins');
                            tabCoins?.classList.remove('d-none');

                            const tabDays = document.getElementById('tab-days');
                            tabDays?.classList.add('d-none');
                        }else if(typeValue == 2){
                            const tabDays = document.getElementById('tab-days');
                            tabDays?.classList.remove('d-none');

                            const tabCoins = document.getElementById('tab-coins');
                            tabCoins?.classList.add('d-none');
                        }
                    };
                    const tsOptions = {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
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
                        onChange: function(value, label, selectedItems) {
                            detectChange(value);
                        }
                    };
                    if(window.TomSelect){
                        tmSelectTypes = new TomSelect(document.getElementById('select-types'), tsOptions);
                        const typeValue = tmSelectTypes.getValue();
                        detectChange(typeValue);
                    }
                })
            </script>
        </div>
    </div>
    </x-dashboard-layout>