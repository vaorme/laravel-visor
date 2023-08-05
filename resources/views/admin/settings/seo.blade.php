<x-admin-layout>
    <x-slot:title>SEO</x-slot>
    <x-admin.nav>
        <li class="{{ (request()->is('controller/settings/ads')) ? 'active' : '' }}"><a href="{{ route('settings.ads.index') }}">Publicidad</a></li>
        <li class="{{ (request()->is('controller/settings/seo')) ? 'active' : '' }}"><a href="{{ route('settings.seo.index') }}">SEO</a></li>
    </x-admin.nav>
    <x-admin.bar title="SEO"/>
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
                <form action="{{ isset($setting)? route('settings.seo.update') : route('settings.seo.store') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($setting))
                        @method('PATCH')
                    @else
                        @method('POST')
                    @endif
                    <div class="group">
                        <label>SEO título</label>
                        <input type="text" name="seo_title" value="{{ isset($setting)? old('seo_title', $setting->seo_title) : '' }}">
                    </div>
                    <div class="group">
                        <label>SEO descripción</label>
                        <textarea name="seo_description" cols="30" rows="4">{{ isset($setting)? old('seo_description', $setting->seo_description) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>SEO palabras clave</label>
                        <input type="text" name="seo_keywords" value="{{ isset($setting)? old('seo_keywords', $setting->seo_keywords) : '' }}">
                    </div>
                    <div class="group">
                        <label>SEO autor</label>
                        <input type="text" name="seo_author" value="{{ isset($setting)? old('seo_author', $setting->seo_author) : '' }}">
                    </div>
                    <div class="group">
                        <label>SEO asunto</label>
                        <input type="text" name="seo_subject" value="{{ isset($setting)? old('seo_subject', $setting->seo_subject) : '' }}">
                    </div>
                    <div class="group">
                        <label>SEO robots</label>
                        <input type="text" name="seo_robots" value="{{ isset($setting)? old('seo_robots', $setting->seo_robots) : '' }}">
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