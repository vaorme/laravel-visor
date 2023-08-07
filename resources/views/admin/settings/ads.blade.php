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
                        <div class="group__label">
                            <label>ADS #1</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-1.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_1" cols="30" rows="6">{{ isset($setting)? old('ads_1', $setting->ads_1) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #2</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-2.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_2" cols="30" rows="6">{{ isset($setting)? old('ads_2', $setting->ads_2) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #3</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-3.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_3" cols="30" rows="6">{{ isset($setting)? old('ads_3', $setting->ads_3) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #4</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-4.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_4" cols="30" rows="6">{{ isset($setting)? old('ads_4', $setting->ads_4) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #5</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-5.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_5" cols="30" rows="6">{{ isset($setting)? old('ads_5', $setting->ads_5) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #6</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-6.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_6" cols="30" rows="6">{{ isset($setting)? old('ads_6', $setting->ads_6) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #7</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-7.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_7" cols="30" rows="6">{{ isset($setting)? old('ads_7', $setting->ads_7) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #8</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-8.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_8" cols="30" rows="6">{{ isset($setting)? old('ads_8', $setting->ads_8) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #9</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-9.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_9" cols="30" rows="6">{{ isset($setting)? old('ads_9', $setting->ads_9) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #10</label>
                            <p>Ubicación <a href="{{ asset('storage/images/ads/ad-10.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_10" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_10) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #11</label>
                            <p>Ubicación abajo<a href="{{ asset('storage/images/ads/ad-11.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_11" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_11) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #12</label>
                            <p>Ubicación arriba <a href="{{ asset('storage/images/ads/ad-11.jpg') }}" target="_blank">EJEMPLO</a></p>
                        </div>
                        <textarea name="ads_12" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_12) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #13</label>
                            <p>Sin ubicación</p>
                        </div>
                        <textarea name="ads_13" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_13) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #14</label>
                            <p>Sin ubicación</p>
                        </div>
                        <textarea name="ads_10" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_14) : '' }}</textarea>
                    </div>
                    <div class="group">
                        <div class="group__label">
                            <label>ADS #15</label>
                            <p>Sin ubicación</p>
                        </div>
                        <textarea name="ads_15" cols="30" rows="6">{{ isset($setting)? old('ads_10', $setting->ads_15) : '' }}</textarea>
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