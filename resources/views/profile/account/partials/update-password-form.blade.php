<h2 class="account__title">Actualizar contraseña</h2>
<form method="post" action="{{ route('password.update') }}" class="form form__password">
    @csrf
    @method('put')
    <p>{{ __('Asegúrese de que su cuenta esté usando una contraseña larga y aleatoria para mantenerse seguro.') }}</p>
    <div class="form__item">
        <x-input-label for="current_password" :value="__('Actual contraseña')" />
        <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
    </div>
    <div class="form__item">
        <x-input-label for="password" :value="__('Nueva contraseña')" />
        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        <div class="password__validation">
            <h3>La contraseña debe contener lo siguiente:</h3>
            <p id="letter" class="invalid">Una letra <strong>minúscula</strong></p>
            <p id="capital" class="invalid">Una letra en <strong>mayuscula</strong></p>
            <p id="number" class="invalid">Un <b>número</b></p>
            <p id="length" class="invalid">Minimo <b>8 caracteres</b></p>
        </div>
    </div>
    <div class="form__item">
        <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="flex items-center gap-4">
        <button type="submit" class="botn reg bg-vo-green py-3 px-8 text-white rounded-lg text-center hover:bg-vo-green-over transition-colors">{{ __('Cambiar') }}</button>

        @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600"
            >{{ __('Saved.') }}</p>
        @endif
    </div>
</form>
