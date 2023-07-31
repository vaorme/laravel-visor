<x-loreg-layout>
    <x-slot:title>Crear cuenta</x-slot>
    <div class="box login">
        <div class="frmo">
            <div class="title">
                <h4>Regístrate</h4>
            </div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('register') }}">
                @csrf
        
                <!-- Name -->
                <div class="group">
                    <x-input-label for="name" :value="__('Nombre de usuario')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autofocus />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>
        
                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
        
                <!-- Password -->
                <div class="group">
                    <x-input-label for="password" :value="__('Contraseña')" />
        
                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
        
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <!-- Confirm Password -->
                <div class="group">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
        
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
        
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
        
                <div class="group buttons flex items-center justify-end">
                    <a href="{{ route('login') }}">
                        {{ __('Ya estás registrado?') }}
                    </a>
        
                    <button type="submit">
                        {{ __('Registrarme') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="banner"></div>
    </div>
    
</x-loreg-layout>
