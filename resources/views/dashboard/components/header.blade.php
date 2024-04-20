<div class="page">
    <!-- Navbar -->
    <header class="navbar navbar-expand-md navbar-overlap d-print-none"  data-bs-theme="dark">
        <div class="container-xl">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="{{ route('dashboard.index') }}">
                    MANGA
                </a>
            </h1>
            <div class="navbar-nav flex-row order-md-last">
                <div class="d-none d-md-flex">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <!-- Download SVG icon from http://tabler-icons.io/i/moon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <!-- Download SVG icon from http://tabler-icons.io/i/sun -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
                    </a>
                </div>
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"></span>
                        <div class="d-none d-xl-block ps-2">
                        <div>@vaor</div>
                        <div class="mt-1 small text-muted">Lector</div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <a href="./profile.html" class="dropdown-item">Perfil</a>
                        <a href="./sign-in.html" class="dropdown-item">Cerrar Sesion</a>
                    </div>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="navbar-menu">
                <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('web.index') }}" >
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-arrow-left-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M12 2l.324 .001l.318 .004l.616 .017l.299 .013l.579 .034l.553 .046c4.785 .464 6.732 2.411 7.196 7.196l.046 .553l.034 .579c.005 .098 .01 .198 .013 .299l.017 .616l.005 .642l-.005 .642l-.017 .616l-.013 .299l-.034 .579l-.046 .553c-.464 4.785 -2.411 6.732 -7.196 7.196l-.553 .046l-.579 .034c-.098 .005 -.198 .01 -.299 .013l-.616 .017l-.642 .005l-.642 -.005l-.616 -.017l-.299 -.013l-.579 -.034l-.553 -.046c-4.785 -.464 -6.732 -2.411 -7.196 -7.196l-.046 -.553l-.034 -.579a28.058 28.058 0 0 1 -.013 -.299l-.017 -.616c-.003 -.21 -.005 -.424 -.005 -.642l.001 -.324l.004 -.318l.017 -.616l.013 -.299l.034 -.579l.046 -.553c.464 -4.785 2.411 -6.732 7.196 -7.196l.553 -.046l.579 -.034c.098 -.005 .198 -.01 .299 -.013l.616 -.017c.21 -.003 .424 -.005 .642 -.005zm.707 5.293a1 1 0 0 0 -1.414 0l-4 4a1.037 1.037 0 0 0 -.2 .284l-.022 .052a.95 .95 0 0 0 -.06 .222l-.008 .067l-.002 .063v-.035v.073a1.034 1.034 0 0 0 .07 .352l.023 .052l.03 .061l.022 .037a1.2 1.2 0 0 0 .05 .074l.024 .03l.073 .082l4 4l.094 .083a1 1 0 0 0 1.32 -.083l.083 -.094a1 1 0 0 0 -.083 -1.32l-2.292 -2.293h5.585l.117 -.007a1 1 0 0 0 -.117 -1.993h-5.585l2.292 -2.293l.083 -.094a1 1 0 0 0 -.083 -1.32z" fill="currentColor" stroke-width="0"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">Volver a Nartag.com</span>
                            </a>
                        </li>
                        @can('comics.index')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="{{ route('comics.index') }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0"></path>
                                            <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0"></path>
                                            <path d="M3 6l0 13"></path>
                                            <path d="M12 6l0 13"></path>
                                            <path d="M21 6l0 13"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Comics</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('comics.index') }}" rel="noopener">Todos</a>
                                    <div class="dropdown-divider"></div>
                                    @can('comics.types.index')
                                        <a class="dropdown-item" href="{{ route('comics.types.index') }}" rel="noopener">Tipos</a>
                                    @endcan
                                    @can('comics.status.index')
                                        <a class="dropdown-item" href="{{ route('comics.status.index') }}" rel="noopener">Estados</a>
                                    @endcan
                                    @can('comics.demography.index')
                                        <a class="dropdown-item" href="{{ route('comics.demographies.index') }}" rel="noopener">Demografias</a>
                                    @endcan
                                    @can('comics.categories.index')
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('comics.categories.index') }}" rel="noopener">Categorías</a>
                                    @endcan
                                    @can('comics.tags.index')
                                        <a class="dropdown-item" href="{{ route('comics.tags.index') }}" rel="noopener">Tags</a>
                                    @endcan
                                </div>
                            </li>
                        @endcan
                        @can('products.index')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-appgallery" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 4m0 4a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z"></path>
                                        <path d="M9 8a3 3 0 0 0 6 0"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">E-commerce</span>
                            </a>
                            <div class="dropdown-menu">
                                @can('products.index')
                                    <a class="dropdown-item" href="{{ route('products.index') }}" rel="noopener">Productos</a>
                                    {{-- <a class="dropdown-item" href="{{ route('products.types.index') }}" rel="noopener">Tipos</a> --}}
                                @endcan
                                @can('orders.index')
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('orders.index') }}" rel="noopener">Ordenes</a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                        @if(auth()->user()->can('users.inex') || auth()->user()->can('roles.inex') || auth()->user()->can('permissions.inex'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-cpu" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 5m0 1a1 1 0 0 1 1 -1h12a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-12a1 1 0 0 1 -1 -1z"></path>
                                        <path d="M9 9h6v6h-6z"></path>
                                        <path d="M3 10h2"></path>
                                        <path d="M3 14h2"></path>
                                        <path d="M10 3v2"></path>
                                        <path d="M14 3v2"></path>
                                        <path d="M21 10h-2"></path>
                                        <path d="M21 14h-2"></path>
                                        <path d="M14 21v-2"></path>
                                        <path d="M10 21v-2"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">Core</span>
                            </a>
                            <div class="dropdown-menu">
                                @can('users.index')
                                    <a class="dropdown-item" href="{{ route('users.index') }}" rel="noopener">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users me-1  " width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                        </svg>
                                        Usuarios
                                    </a>
                                @endcan
                                @can('roles.index')
                                    <a class="dropdown-item" href="{{ route('roles.index') }}" rel="noopener">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category-2 me-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M14 4h6v6h-6z"></path>
                                            <path d="M4 14h6v6h-6z"></path>
                                            <path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                            <path d="M7 7m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0"></path>
                                        </svg>
                                        Roles
                                    </a>
                                @endcan
                                @can('permissions.index')
                                    <a class="dropdown-item" href="{{ route('permissions.index') }}" rel="noopener">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-category-2 me-1" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-key">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0z"></path>
                                            <path d="M15 9h.01"></path>
                                        </svg>
                                        Permisos
                                    </a>
                                @endcan
                            </div>
                        </li>
                        @endif
                        @can('settings.index')
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ route('settings.index') }}" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z"></path>
                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                    </svg>
                                </span>
                                <span class="nav-link-title">Configuración</span>
                            </a>
                            <div class="dropdown-menu">
                                @can('settings.index')
                                <a class="dropdown-item" href="{{ route('settings.index') }}" rel="noopener">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-settings-2 me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" /><path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                                    General
                                </a>
                                @endcan
                                @can('settings.ads.index')
                                <a class="dropdown-item" href="{{ route('settings.ads.index') }}" rel="noopener">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-ad me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M7 15v-4a2 2 0 0 1 4 0v4" /><path d="M7 13l4 0" /><path d="M17 9v6h-1.5a1.5 1.5 0 1 1 1.5 -1.5" /></svg>
                                    Publicidad
                                </a>
                                @endcan
                                @can('settings.seo.index')
                                <a class="dropdown-item" href="{{ route('settings.seo.index') }}" rel="noopener">
                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-seo me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 8h-3a1 1 0 0 0 -1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 1 1 1v2a1 1 0 0 1 -1 1h-3" /><path d="M14 16h-4v-8h4" /><path d="M11 12h2" /><path d="M17 8m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /></svg>
                                    SEO
                                </a>
                                @endcan
                            </div>
                        </li>
                        @endcan
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <div class="page-wrapper">