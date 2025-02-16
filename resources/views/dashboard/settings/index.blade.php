<x-dashboard-layout>
    <!-- Page header -->
    <div class="page-header d-print-none text-white">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Overview</div>
                    <h2 class="page-title">Settings</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <button class="btn-submit btn btn-success d-sm-inline-block" >
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brackets" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M8 4h-3v16h3"></path>
                                    <path d="M16 4h3v16h-3"></path>
                                </svg>
                                Guardar
                            </button>
                        </div>
                  </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body settings">
        <div class="container-xl">
            <form action="{{ route('settings.update') }}" class="frmo" method="post" novalidate enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                                    <input type="text" class="form-control" id="floating-input" name="title" autocomplete="off" value="{{ isset($setting)? old('title', $setting->title) : '' }}" required>
                                                    <label for="floating-input">Titulo</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="email" class="form-control" id="floating-input" name="email" autocomplete="off" value="{{ isset($setting)? old('email', $setting->email) : '' }}">
                                                    <label for="floating-input">Email</label>
                                                </div>
                                            </div>
											<div class="col-12">
                                                <div class="form-floating">
                                                    <textarea name="message" class="form-control" cols="30" rows="10">{{ isset($setting)? old('message', $setting->global_message) : '' }}</textarea>
                                                    <label for="floating-input">Mensaje</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="floating-input" name="chat_id" autocomplete="off" value="{{ isset($setting)? old('chat_id', $setting->chat_id) : '' }}">
                                                    <label for="floating-input">Chat ID</label>
                                                </div>
                                            </div>
											<div class="col-12">
                                                <label class="form-label">Head</label>
                                                <textarea class="form-control" rows="12" name="insert_head">{{ isset($setting)? old('insert_head', $setting->insert_head) : '' }}</textarea>
                                            </div>
											<div class="col-12">
                                                <label class="form-label">Body</label>
                                                <textarea class="form-control" rows="12" name="insert_body">{{ isset($setting)? old('insert_body', $setting->insert_body) : '' }}</textarea>
                                            </div>
											<div class="col-12">
                                                <label class="form-label">Footer</label>
                                                <textarea class="form-control" rows="12" name="insert_footer">{{ isset($setting)? old('insert_footer', $setting->insert_footer) : '' }}</textarea>
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
                                                    <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true">Resumen</button>
                                                </h2>
                                                <div id="collapse-1" class="accordion-collapse collapse show">
                                                    <div class="accordion-body pt-0">
                                                        <div class="row row-cards">
                                                            <div class="col-12">
                                                                <label for="logo" class="mb-3">Logo</label>
                                                                <div class="own-dropzone logo">
                                                                    <div class="dz-choose">
                                                                        <div class="dz-preview">
                                                                            <img src="{{ (isset($setting) && $setting->logo != "")? asset('storage/'.old('logo', $setting->logo)) : '' }}" alt="" class="dz-image{{ (isset($setting) && $setting->logo != "")? ' show' : '' }}">
                                                                            <div class="dz-change{{ (isset($setting) && $setting->logo == "") ? ' visually-hidden' : '' }}">
                                                                                <a href="javascript:void(0)" class="btn btn-pinterest w-auto">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-exchange-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                        <path d="M17 10h-14l4 -4"></path>
                                                                                        <path d="M7 14h14l-4 4"></path>
                                                                                    </svg>
                                                                                    Cambiar logo
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <p class="dz-text">Elegir logo</p>
                                                                    </div>
                                                                    <input type="file" name="logo" class="dz-input" accept="image/*" hidden>
                                                                </div>
                                                            </div>
                                                            <div class="col-8">
                                                                <label for="favicon">Favicon</label>
                                                            </div>
                                                            <div class="col-4">
                                                                <div class="own-dropzone favicon">
                                                                    <div class="dz-choose">
                                                                        <div class="dz-preview">
                                                                            <img src="{{ (isset($setting) && $setting->favicon != "")? asset('storage/'.old('favicon', $setting->favicon)) : '' }}" alt="" class="dz-image{{ (isset($setting) && $setting->favicon != "")? ' show' : '' }}">
                                                                            <div class="dz-change{{ (isset($setting) && $setting->favicon == "") ? ' visually-hidden' : '' }}">
                                                                                <a href="javascript:void(0)" class="btn btn-pinterest w-auto">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-exchange-2 m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                        <path d="M17 10h-14l4 -4"></path>
                                                                                        <path d="M7 14h14l-4 4"></path>
                                                                                    </svg>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <p class="dz-text">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-exchange-2 m-0" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                <path d="M17 10h-14l4 -4"></path>
                                                                                <path d="M7 14h14l-4 4"></path>
                                                                            </svg>
                                                                        </p>
                                                                    </div>
                                                                    <input type="file" name="favicon" class="dz-input" accept="image/*" hidden>
                                                                </div>
                                                            </div>
															<div class="col-8">
																<label for="maintenance_mode">Modo mantenimiento</label>
															</div>
															<div class="col-4">
																<span>
																	<label class="form-check form-check-single form-switch p-0 d-flex justify-content-end">
																		<input class="form-check-input" id="maintenance_mode" name="maintenance" type="checkbox" {{ (isset($setting) && $setting->maintenance)? 'checked': '' }}>
																	</label>
																</span>
															</div>
															<div class="col-8">
																<label for="allow_new_users">Permitir registro de usuarios</label>
															</div>
															<div class="col-4">
																<span>
																	<label class="form-check form-check-single form-switch p-0 d-flex justify-content-end">
																		<input class="form-check-input" id="allow_new_users" name="allow_new_users" type="checkbox" {{ (isset($setting) && $setting->allow_new_users)? 'checked': '' }}>
																	</label>
																</span>
															</div>
															<div class="col-12">
																<label for="images_server">Elegir servidor de imagenes</label>
															</div>
															<div class="col-12">
																<div class="form-selectgroup">
																	<label class="form-selectgroup-item">
																		<input type="radio" name="disk" value="public" class="form-selectgroup-input" {{ (isset($setting) && $setting->disk == 'public')? 'checked': '' }}>
																		<span class="form-selectgroup-label">Local</span>
																	</label>
																	@if (env('FTP_HOST') && env('FTP_HOST') != "")
																		<label class="form-selectgroup-item">
																			<input type="radio" name="disk" value="ftp" class="form-selectgroup-input" {{ (isset($setting) && $setting->disk == 'ftp')? 'checked': '' }}>
																			<span class="form-selectgroup-label">FTP</span>
																		</label>
																	@endif
																	@if (env('SFTP_HOST') && env('SFTP_HOST') != "")
																		<label class="form-selectgroup-item">
																			<input type="radio" name="disk" value="sftp" class="form-selectgroup-input" {{ (isset($setting) && $setting->disk == 'sftp')? 'checked': '' }}>
																			<span class="form-selectgroup-label">S3</span>
																		</label>
																	@endif
																	@if (env('S3_HOST') && env('S3_HOST') != "")
																		<label class="form-selectgroup-item">
																			<input type="radio" name="disk" value="s3" class="form-selectgroup-input" {{ (isset($setting) && $setting->disk == 's3')? 'checked': '' }}>
																			<span class="form-selectgroup-label">S3</span>
																		</label>
																	@endif
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
                    </div>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // :TINYMCE
                    let options = {
                        selector: '#tinymce-mytextarea',
                        height: 300,
                        menubar: false,
                        statusbar: false,
                        plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen","insertdatetime","media", "table", "code", "help", "wordcount"],
                        toolbar: 'undo redo | formatselect | ' +
                            'bold italic forecolor backcolor | alignleft aligncenter ' +
                            'alignright alignjustify | bullist numlist outdent indent | ' +
                            'removeformat',
                        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
                    }
                    if (localStorage.getItem("tablerTheme") === 'dark') {
                        options.skin = 'oxide-dark';
                        options.content_css = 'dark';
                    }
                    // COMIC DESCRIPTION
                    tinyMCE.init(options);

                    // :SELECT STATUS
                    let tmSelectStatus,
                    tmSelectAuthor,
                    tmSelectCategoreis,
                    tmInputTags,
                    tmSelectComicType,
                    tmSelectComicStatus,
                    tmSelectComicDemography,
                    tmSelectComicWhenEach;

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
                    };
                    const tsOptionsWithRemove = {
                        copyClassesToDropdown: false,
                        dropdownParent: 'body',
                        plugins: {
                            remove_button:{
                                title:'Remove',
                            }
                        },
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
                    };
                })
            </script>
        </div>
    </div>
</x-dashboard-layout>