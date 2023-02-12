<x-admin-layout>
    <h1>editar usuario</h1>
    <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST">
        @csrf
        @method('patch')
        <div class="group">
            <label>Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}">
        </div>
        <div class="group">
            <label>email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
        </div>
        <div class="group">
            <label>Current Password</label>
            <input type="password" name="current_password">
        </div>
        <div class="group">
            <label>New Password</label>
            <input type="password" name="password">
        </div>
        <div class="group">
            <label>New Password confirmation</label>
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