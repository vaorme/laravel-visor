<x-loreg-layout>
    <x-slot:title>Iniciar sesión</x-slot>
    <div class="box login">
        <div class="frmo">
            <div class="title">
                <h4>Iniciar sesión</h4>
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="group">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="group cks remember">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember">
                        <div class="checkbox"></div>
                        <span class="ml-2">{{ __('Recuerdame') }}</span>
                    </label>
                </div>

                <div class="group buttons flex items-center justify-end">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            {{ __('Olvidates tu contraseña?') }}
                        </a>
                    @endif

                    <button type="submit">{{ __('Ingresar') }}</button>
                </div>
            </form>
        </div>
        <div class="banner"></div>
    </div>
</x-loreg-layout>
