<x-app-layout>
	<div class="main__wrap members">
		<div class="main__content">
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
    </div>
</x-app-layout>