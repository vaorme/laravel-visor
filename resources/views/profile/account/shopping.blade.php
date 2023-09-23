<x-app-layout>
    <x-slot:title>Mis compras | Nartag</x-slot>
    @php
        $auth = Auth::user();
    @endphp
    <div class="main__wrap">
        <section class="orders">
            <div class="section__title">
                <a href="{{ route('profile.index', ['username' => $auth->username]) }}" class="view__back">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"></path>
                    </svg>
                </a>
                <div class="view__text">
                    <h2>Mis Compras</h2>
                </div>
            </div>
            <div class="section__content pt-8">
                @if ($loop->isNotEmpty())
            <div class="products">
                @php
                    $paginate = $loop->links('vendor.pagination.default');
                @endphp
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs uppercase">
                            <tr>
                                <th scope="col" class="px-6 py-3">#</th>
                                <th scope="col" class="px-6 py-3">Nombre</th>
                                <th scope="col" class="px-6 py-3">Estado</th>
                                <th scope="col" class="px-6 py-3">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loop as $item)
                                <tr>
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $item->order_id }}</th>
                                    <td class="px-6 py-4">{{ $item->products->first()->product->name }}</td>
                                    <td class="px-6 py-4">
                                        @switch($item->status)
                                            @case("COMPLETED")
                                                Completado
                                                @break
                                            @case("PENDING")
                                                Pendiente
                                                @break
                                            @case("CANCELLED")
                                                Cancelado
                                                @break
                                            @case("REFUNDED")
                                                Reembolsado
                                                @break
                                            @case("FAILED")
                                                Fallido
                                                @break
                                            @case("REVERSED")
                                                Revertido
                                                @break
                                            @default
                                                
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4">$ {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $paginate }}
            </div>
            @else
                <div class="empty">No hay elementos para mostrar</div>
            @endif
            </div>
        </section>
    </div>
</x-app-layout>