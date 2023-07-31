<x-loreg-layout>
    <x-slot:title>Confirmar contraseña</x-slot>
    <div class="box login">
        <div class="frmo">
            <div class="title">
                <h4 class="mb-2">Confirmar contraseña</h4>
                <p>{{ __('Esta es un área segura de la aplicación. Por favor, confirme su contraseña antes de continuar.') }}</p>
            </div>
        
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf
        
                <!-- Password -->
                <div class="group">
                    <x-input-label for="password" :value="__('Contraseña')" />
        
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />
        
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <div class="group buttons flex justify-end mt-4">
                    <button type="submit">
                        {{ __('Confirmar') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="banner"></div>
    </div>
</x-loreg-layout>
