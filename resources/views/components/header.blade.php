@if (Auth::check())
    @php
        $auth = Auth::user();
        $controllerRoles = ['administrador', 'developer', 'moderador'];
    @endphp
@endif

<header class="header">
    <nav class="navbar">
        <div class="movil">
            <div class="burger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="lft">
            <div class="logo">
                <a href="{{ URL::to('/') }}"class="block">
                    <img src="{{ config('app.logo')? asset('storage/'.config('app.logo')) : asset('storage/images/logo-site.png') }}" alt="">
                </a>            
            </div>
        </div>
        <div class="rth">
            <div class="menu">
                <ul class="flex flex-wrap gap-6">
                    <li class="{{ (Route::is('library.index'))? 'active': null }}">
                        <a href="{{ url('/library') }}" class="block font-medium">
                        <div class="item__icon">
                            <i class="fa-solid fa-book-open-reader"></i>
                        </div>
                        <span class="item__text">Explorar</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://novelas.nartag.com/" target="_blank" class="block font-medium">
                            <div class="item__icon">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <span class="item__text">Novelas</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://discord.com/invite/Q56zq4MfHd" target="_blank" class="block font-medium">
                        <div class="item__icon">
                            <i class="fa-brands fa-discord"></i>
                        </div>
                        <span class="item__text">Discord</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://nartag.com/donacione/" target="_blank" class="block font-medium">
                            <div class="item__icon">
                                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                            </div>
                            <span class="item__text">Donar</span>
                        </a>
                    </li>
                    @if (Auth::check() && in_array($auth->profile->getRole(), $controllerRoles))
                        <li>
                            <a href="{{ URL::to('/controller') }}" class="block font-medium">
                                <div class="item__icon">
                                    <i class="fa-solid fa-user-secret"></i>
                                </div>
                                <span class="item__text">Controller</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="rth__access">
                @if (Auth::check())
                    @php
                        $profile = $auth->profile;
                    @endphp
                    <div class="panel">
                        <a href="{{ route('profile.index', ['username' => $auth->username]) }}">
                            <div class="user flex align-center">
                                <div class="name">
                                    <span>Bienvenid@</span>
                                    <h4>{{ $auth->username }}</h4>
                                </div>
                                <div class="avatar">
                                    <img src="{{ asset('storage/'.$profile->avatar) }}" alt="{{ $auth->username }}"/>
                                </div>
                            </div>
                        </a>
                        <div class="logout">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">
                                    <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M19 12L15 8M19 12L15 16M19 12H9" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/><path d="M14 21C9.02944 21 5 16.9706 5 12C5 7.02944 9.02944 3 14 3" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="buttons flex gap-4">
                        <a href="{{ route('login') }}" class="botn log bg-neutral-800 py-3 text-white px-8 rounded-lg text-center hover:bg-neutral-700 transition-colors">Iniciar sesion</a>
                        @if (config('app.allow_new_users'))
                            <a href="{{ route('register') }}" class="botn reg bg-vo-red py-3 px-8 text-white rounded-lg text-center hover:bg-vo-red-over transition-colors">Registrate</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </nav>
    @if (!Auth::check())
        <div class="buttons flex gap-4">
            <a href="{{ route('login') }}" class="botn log bg-neutral-800 py-3 text-white px-8 rounded-lg text-center hover:bg-neutral-700 transition-colors"><i class="fa-solid fa-arrow-right-to-bracket"></i> Iniciar sesion</a>
            @if (config('app.allow_new_users'))
                <a href="{{ route('register') }}" class="botn reg bg-vo-red py-3 px-8 text-white rounded-lg text-center hover:bg-vo-red-over transition-colors"><i class="fa-solid fa-user-plus"></i> Registrate</a>
            @endif
        </div>
    @endif
    <div class="search__bar">
        <form action="{{ (Route::is('library.index'))? url()->full() : url('/library'); }}" method="GET">
            <input type="text" name="s" class="outline-none text-white pl-12 text-base" placeholder="Buscar..." value="{{ (isset(request()->s) && !empty(request()->s))? request()->s : null }}">
            <button class="flex items-center justify-center">
                <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="#fff"><path d="M17 17l4 4M3 11a8 8 0 1016 0 8 8 0 00-16 0z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
            </button>
        </form>
    </div>
</header>