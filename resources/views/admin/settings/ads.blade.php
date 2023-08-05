<x-admin-layout>
    <x-slot:title>Publicidad</x-slot>
    <x-admin.nav>
        <li class="{{ (request()->is('controller/settings/ads')) ? 'active' : '' }}"><a href="{{ route('settings.ads.index') }}">Publicidad</a></li>
        <li class="{{ (request()->is('controller/settings/seo')) ? 'active' : '' }}"><a href="{{ route('settings.seo.index') }}">SEO</a></li>
    </x-admin.nav>
    <x-admin.bar title="Publicidad"/>
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
                <form action="{{ isset($setting)? route('settings.ads.update') : route('settings.ads.store') }}" class="form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($setting))
                        @method('PATCH')
                    @else
                        @method('POST')
                    @endif
                    <div class="group">
                        <label>ADS #1</label>
                        <textarea name="ads_1" cols="30" rows="6">{{ isset($setting)? old('ads_1', $setting->ads_1) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #2</label>
                        <textarea name="ads_2" cols="30" rows="6">{{ isset($setting)? old('ads_2', $setting->ads_2) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #3</label>
                        <textarea name="ads_3" cols="30" rows="6">{{ isset($setting)? old('ads_3', $setting->ads_3) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #4</label>
                        <textarea name="ads_4" cols="30" rows="6">{{ isset($setting)? old('ads_4', $setting->ads_4) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #5</label>
                        <textarea name="ads_5" cols="30" rows="6">{{ isset($setting)? old('ads_5', $setting->ads_5) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #6</label>
                        <textarea name="ads_6" cols="30" rows="6">{{ isset($setting)? old('ads_6', $setting->ads_6) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #7</label>
                        <textarea name="ads_7" cols="30" rows="6">{{ isset($setting)? old('ads_7', $setting->ads_7) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #8</label>
                        <textarea name="ads_8" cols="30" rows="6">{{ isset($setting)? old('ads_8', $setting->ads_8) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #9</label>
                        <textarea name="ads_9" cols="30" rows="6">{{ isset($setting)? old('ads_9', $setting->ads_9) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <label>ADS #10</label>
                        <textarea name="ads_10" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_10) : '' }}</textarea>
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