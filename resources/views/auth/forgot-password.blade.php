<x-loreg-layout>
    <div class="box login">
        <div class="frmo">
            <div class="title">
                <h4 class="mb-2">Recuperar contraseña</h4>
                <p class="text-sm">{{ __('Ingresa tu dirección de correo electrónico y le enviaremos un enlace de restablecimiento de contraseña que le permitirá elegir una nueva..') }}</p>
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Email')" class="mb-2" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="group buttons flex items-center justify-end mt-4">
                    <button type="submit">
                        {{ __('Enviar enlace') }}
                    </button>
                </div>
                </form>
        </div>
        <div class="banner"></div>
    </div>
</x-loreg-layout>
