<aside class="aside">
    <ul class="m-ul">
        <li class="m-item {{ (request()->is('controller')) ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-grid" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <rect x="4" y="4" width="6" height="6" rx="1" />
                        <rect x="14" y="4" width="6" height="6" rx="1" />
                        <rect x="4" y="14" width="6" height="6" rx="1" />
                        <rect x="14" y="14" width="6" height="6" rx="1" />
                    </svg>
                </div>
            </a>
        </li>
        <div class="divider"></div>
        <li class="m-item {{ (request()->is('controller/slider*')) ? 'active' : '' }}">
            <a href="{{ route('slider.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-carousel-horizontal" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M7 5m0 1a1 1 0 0 1 1 -1h8a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1z" />
                        <path d="M22 17h-1a1 1 0 0 1 -1 -1v-8a1 1 0 0 1 1 -1h1" />
                        <path d="M2 17h1a1 1 0 0 0 1 -1v-8a1 1 0 0 0 -1 -1h-1" />
                    </svg>
                </div>
            </a>
        </li>
        <li class="m-item m-item-dropdown {{ (request()->is('controller/manga*')) ? 'active' : '' }}">
            <a href="{{ route('manga.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-book" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M3 19a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                        <path d="M3 6a9 9 0 0 1 9 0a9 9 0 0 1 9 0" />
                        <line x1="3" y1="6" x2="3" y2="19" />
                        <line x1="12" y1="6" x2="12" y2="19" />
                        <line x1="21" y1="6" x2="21" y2="19" />
                    </svg>
                </div>
            </a>
        </li>
        <li class="m-item {{ (request()->is('controller/categories*')) ? 'active' : '' }}">
            <a href="{{ route('categories.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-hierarchy" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="5" r="2" />
                        <circle cx="5" cy="19" r="2" />
                        <circle cx="19" cy="19" r="2" />
                        <path d="M6.5 17.5l5.5 -4.5l5.5 4.5" />
                        <line x1="12" y1="7" x2="12" y2="13" />
                    </svg>
                </div>
            </a>
        </li>
        <li class="m-item {{ (request()->is('controller/tags*')) ? 'active' : '' }}">
            <a href="{{ route('tags.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tags" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M7.859 6h-2.834a2.025 2.025 0 0 0 -2.025 2.025v2.834c0 .537 .213 1.052 .593 1.432l6.116 6.116a2.025 2.025 0 0 0 2.864 0l2.834 -2.834a2.025 2.025 0 0 0 0 -2.864l-6.117 -6.116a2.025 2.025 0 0 0 -1.431 -.593z" />
                        <path d="M17.573 18.407l2.834 -2.834a2.025 2.025 0 0 0 0 -2.864l-7.117 -7.116" />
                        <path d="M6 9h-.01" />
                    </svg>
                </div>
            </a>
        </li>
        <li class="m-item {{ (request()->is('controller/users*')) ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="9" cy="7" r="4" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                    </svg>
                </div>
            </a>
        </li>
        <li class="m-item m-item-dropdown {{ (request()->is('controller/products*')) ? 'active' : '' }}">
            <a href="#" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-basket" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#fff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <polyline points="7 10 12 4 17 10" />
                        <path d="M21 10l-2 8a2 2.5 0 0 1 -2 2h-10a2 2.5 0 0 1 -2 -2l-2 -8z" />
                        <circle cx="12" cy="15" r="2" />
                    </svg>
                </div>
            </a>
        </li>
        <div class="divider"></div>
        <li class="m-item m-item-dropdown{{ (request()->is('controller/settings*')) ? ' active' : '' }}">
            <a href="{{ route('settings.index') }}" class="m-link">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-settings" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
            </a>
        </li>
    </ul>
</aside>