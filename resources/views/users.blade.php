<x-app-layout>
    <x-slot:title>Usuarios | Nartag</x-slot>
	<div class="main__wrap members">
        @if (showAds())
            @php
                $ad_2 = config('app.ads_2');
            @endphp
            @if ($ad_2)
                <div class="vealo">
                    {!! $ad_2 !!}
                </div>
            @endif
        @endif
		<div class="main__content">
            <div class="members__top">
                <div class="members__title">
                    <a href="{{ route('web.index') }}" class="view__back">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"></path>
                        </svg>
                    </a>
                    <div class="view__text">
                        <h2>Usuarios</h2>
                    </div>
                </div>
                <div class="members__search">
                    <form action="{{ (Route::is('members.index'))? url()->full() : route('library.index'); }}" method="GET">
                        <input type="text" name="s" class="outline-none text-white pl-12 text-base" placeholder="Buscar..." value="{{ (isset(request()->s) && !empty(request()->s))? request()->s : null }}">
                        <button class="flex items-center justify-center">
                            <svg width="24px" height="24px" viewBox="0 0 24 24" stroke-width="1.5" fill="none" xmlns="http://www.w3.org/2000/svg" color="#fff"><path d="M17 17l4 4M3 11a8 8 0 1016 0 8 8 0 00-16 0z" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
            @if ($loop->isNotEmpty())
                @php
                    $pagination = $loop->links('vendor.pagination.default');
                @endphp
                <div class="members__list">
                    @foreach ($loop as $item)
                        <x-user-loop-item :item="$item" />
                    @endforeach
                </div>
                {{ $pagination }}
            @else
                <div class="empty">No hay elementos para mostrar</div>
            @endif
        </div>
        @if (showAds())
            @php
                $ad_9 = config('app.ads_9');
            @endphp
            @if ($ad_9)
                <div class="vealo">
                    {!! $ad_9 !!}
                </div>
            @endif
        @endif
    </div>
</x-app-layout>