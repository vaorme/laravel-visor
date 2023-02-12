<x-admin-layout>
    <h1>crear usuario</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="group">
            <label>Username</label>
            <input type="text" name="username">
        </div>
        <div class="group">
            <label>email</label>
            <input type="email" name="email">
        </div>
        <div class="group">
            <label>Password</label>
            <input type="password" name="password">
        </div>
        <div class="group">
            <label>Password confirmation</label>
            <input type="password" name="password_confirmation">
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
        <button type="submit">Enviar</button>
    </form>
</x-admin-layout>