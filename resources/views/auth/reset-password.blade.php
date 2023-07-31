<x-loreg-layout>
    <x-slot:title>Resetear contraseña</x-slot>
    <div class="box login">
        <div class="frmo">
            <div class="title">
                <h4 class="mb-2">Resetear contraseña</h4>
            </div>
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
        
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
        
                <!-- Email Address -->
                <div class="group">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
        
                <!-- Password -->
                <div class="group">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
        
                <!-- Confirm Password -->
                <div class="group">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
        
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                        type="password"
                                        name="password_confirmation" required />
        
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
        
                <div class="group buttons flex items-center justify-end mt-4">
                    <button type="submit">
                        {{ __('Resetear') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="banner"></div>
    </div>
</x-loreg-layout>
