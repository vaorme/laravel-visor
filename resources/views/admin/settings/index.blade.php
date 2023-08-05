<x-admin-layout>
    <x-slot:title>Configuración</x-slot>
    <x-admin.nav>
        <li class="{{ (request()->is('controller/settings/ads')) ? 'active' : '' }}"><a href="{{ route('settings.ads.index') }}">Publicidad</a></li>
        <li class="{{ (request()->is('controller/settings/seo')) ? 'active' : '' }}"><a href="{{ route('settings.seo.index') }}">SEO</a></li>
    </x-admin.nav>
    <x-admin.bar title="Configuración"/>
    <div class="settings flex flex-wrap">
        <div class="contain w-full">
            @if (Session::has('success'))
                <div class="alertas success">
                    <div class="box">
                        <p>{!! \Session::get('success') !!}</p>
                    </div>
                </div>
                <script>
                    let alerta = document.querySelector('.alertas');
                    setTimeout(() => {
                        alerta.remove();
                    }, 2000);
                </script>
            @endif
            @if (Session::has('error'))
                <div class="alertas error">
                    <div class="box">
                        <p>{!! \Session::get('error') !!}</p>
                    </div>
                </div>
                <script>
                    let alerta = document.querySelector('.alertas');
                    setTimeout(() => {
                        alerta.remove();
                    }, 2000);
                </script>
            @endif
        </div>
        <section class="settings__contain w-full">
            <div class="frmo">
                <form action="{{ route('settings.update') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="group">
                        <label>Título</label>
                        <input type="text" name="title" value="{{ isset($setting)? old('title', $setting->title) : '' }}">
                    </div>
                    <div class="group logo">
                        <label>Logo</label>
                        <div class="dropzone">
                            <div id="choose">
                                <div class="preview">
                                    <img src="{{ (isset($setting) && $setting->logo != "")? asset('storage/'.old('logo', $setting->logo)) : '' }}" alt="" class="image-preview{{ (isset($setting) && $setting->logo != "")? ' added' : '' }}">
                                </div>
                                <p class="text-drop">Elegir Logo</p>
                            </div>
                            <input type="file" name="logo" id="logo" accept="image/*" hidden>
                        </div>
                    </div>
                    <div class="group favicon">
                        <label>Favicon</label>
                        <div class="dropzone">
                            <div id="choose">
                                <div class="preview">
                                    <img src="{{ (isset($setting) && $setting->favicon != "")? asset('storage/'.old('favicon', $setting->favicon)) : '' }}" alt="" class="image-preview{{ (isset($setting) && $setting->favicon != "")? ' added' : '' }}">
                                </div>
                                <p class="text-drop">Elegir favicon</p>
                            </div>
                            <input type="file" name="favicon" id="favicon" accept="image/*" hidden>
                        </div>
                    </div>
                    <div class="group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ isset($setting)? old('email', $setting->email) : '' }}">
                    </div>
                    <div class="group">
                        <label>Chat ID</label>
                        <input type="text" name="chat_id" value="{{ isset($setting)? old('chat_id', $setting->chat_id) : '' }}">
                    </div>
                    <div class="group">
                        <label>Mensaje</label>
                        <textarea name="message" cols="30" rows="4">{{ isset($setting)? old('message', $setting->global_message) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>Modo mantenimiento</label>
                        <div class="rdos">
                            <label for="op-1">
                                <input type="radio" id="op-1" name="maintenance" value="1" {{ (isset($setting) && $setting->maintenance)? 'checked': '' }}>
                                <div class="rd-input">
                                    <div class="rd-option">
                                        Si
                                    </div>
                                </div>
                            </label>
                            <label for="op-2">
                                <input type="radio" id="op-2" name="maintenance" value="0" {{ (isset($setting) && !$setting->maintenance)? 'checked': '' }}>
                                <div class="rd-input">
                                    <div class="rd-option">
                                        No
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="group">
                        <label>Permitir registro de usuarios</label>
                        <div class="rdos">
                            <label for="op-3">
                                <input type="radio" id="op-3" name="allow_new_users" value="1" {{ (isset($setting) && $setting->allow_new_users)? 'checked': '' }}>
                                <div class="rd-input">
                                    <div class="rd-option">
                                        Si
                                    </div>
                                </div>
                            </label>
                            <label for="op-4">
                                <input type="radio" id="op-4" name="allow_new_users" value="0" {{ (isset($setting) && !$setting->allow_new_users)? 'checked': '' }}>
                                <div class="rd-input">
                                    <div class="rd-option">
                                        No
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="group">
                        <label>Elegir servidor de subida de capítulos</label>
                        <div class="rdos">
                            <label for="disk-1">
                                <input type="radio" id="disk-1" name="disk" value="public" {{ (isset($setting) && $setting->disk == 'public')? 'checked': '' }}>
                                <div class="rd-input">
                                    <div class="rd-option">
                                        Local
                                    </div>
                                </div>
                            </label>
                            @if (env('FTP_HOST') && env('FTP_HOST') != "")
                                <label for="disk-2">
                                    <input type="radio" id="disk-2" name="disk" value="ftp" {{ (isset($setting) && $setting->disk == 'ftp')? 'checked': '' }}>
                                    <div class="rd-input">
                                        <div class="rd-option">
                                            FTP
                                        </div>
                                    </div>
                                </label>
                            @endif
                            @if (env('SFTP_HOST') && env('SFTP_HOST') != "")
                                <label for="disk-3">
                                    <input type="radio" id="disk-3" name="disk" value="sftp" {{ (isset($setting) && $setting->disk == 'sftp')? 'checked': '' }}>
                                    <div class="rd-input">
                                        <div class="rd-option">
                                            SFTP
                                        </div>
                                    </div>
                                </label>
                            @endif
                            @if (env('S3_HOST') && env('S3_HOST') != "")
                                <label for="disk-4">
                                    <input type="radio" id="disk-4" name="disk" value="s3" {{ (isset($setting) && $setting->disk == 's3')? 'checked': '' }}>
                                    <div class="rd-input">
                                        <div class="rd-option">
                                            Amazon S3
                                        </div>
                                    </div>
                                </label>
                            @endif
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
                    <button type="submit" class="botn success">Guardar</button>
                </form>
            </div>
        </section>
    </div>

</x-admin-layout>