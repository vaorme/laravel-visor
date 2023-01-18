<x-app-layout>
    @if (isset($error))
        usuario no existe
    @endif

    @if (isset($user))
        @php
            echo "<pre>";
                var_dump($user);
            echo "</pre>";
        @endphp
    @endif
</x-app-layout>
