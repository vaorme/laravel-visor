<x-loreg-layout>
    <div class="box login">
        <div class="frmo">
            <div class="title">
                <h4 class="mb-2">Resetear contraseña</h4>
                <p>{{ __('Gracias por registrarte! Antes de comenzar, ¿podría verificar su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar? Si no recibiste el correo electrónico, con gusto te enviaremos otro.') }}</p>
            </div>
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-center text-white p-2 bg-emerald-700">
                    {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el registro.') }}
                </div>
            @endif
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
    
                <div class="group buttons text-center">
                    <button type="submit">{{ __('Resend Verification Email') }}</button>
                </div>
            </form>
    
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <div class="group buttons alt-button text-center">
                    <button type="submit">
                        {{ __('Cerrar sesión') }}
                    </button>
                </div>
            </form>
        </div>
        <div class="banner"></div>
    </div>
</x-loreg-layout>
