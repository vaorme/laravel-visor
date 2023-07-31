<h2 class="account__title">Eliminar cuenta</h2>
<form method="post" action="{{ route('account.destroy') }}" class="form">
    @csrf
    @method('delete')
    <p>{{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán de forma permanente. Ingrese su contraseña para confirmar que desea eliminar su cuenta de forma permanente.') }}</p>
    <div class="form__item">
        <label for="account_password">Contraseña</label>
        <x-text-input
            id="account_password"
            name="password"
            type="password"
        />
        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
    </div>
    <div class="form__item form__buttons">
        <button type="submit" class="botn reg bg-vo-red py-3 px-8 text-white rounded-lg text-center hover:bg-vo-red-over transition-colors">
            {{ __('Eliminar cuenta') }}
        </button>
    </div>
</form>