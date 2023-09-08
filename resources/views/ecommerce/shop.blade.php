<x-ecommerce-layout>
    <x-slot:title>Tienda | Nartag</x-slot>
    <div class="main__wrap">
        <section class="shop">
            <div class="shop__title">
                <a href="{{ route('web.index') }}" class="view__back">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.6668 6.16671H3.52516L8.1835 1.50837L7.00016 0.333374L0.333496 7.00004L7.00016 13.6667L8.17516 12.4917L3.52516 7.83337H13.6668V6.16671Z" fill="white"></path>
                    </svg>
                </a>
                <div class="view__text">
                    <h2>Shop</h2>
                </div>
            </div>
            <div class="products">
                @if ($loop->isNotEmpty())
                    @php
                        $paginate = $loop->links('vendor.pagination.default');
                    @endphp
                    @foreach ($loop as $item)
                        <div class="product__item">
                            <div class="product__card">
                                <h4 class="card__title">{{ $item->name }}</h4>
                                <div class="card__cover">
                                    @if ($item->image)
                                        <img src="{{ asset("storage/".$item->image); }}" alt="{{ $item->name }}">
                                    @else
                                        <div class="card__icon">
                                            @switch($item->product_type_id)
                                                @case(1)
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8 0.5C12.1423 0.5 15.5 3.85775 15.5 8C15.5 12.1423 12.1423 15.5 8 15.5C3.85775 15.5 0.5 12.1423 0.5 8C0.5 3.85775 3.85775 0.5 8 0.5ZM7.46975 5.348L5.348 7.46975C5.2074 7.6104 5.12841 7.80113 5.12841 8C5.12841 8.19887 5.2074 8.3896 5.348 8.53025L7.46975 10.652C7.6104 10.7926 7.80113 10.8716 8 10.8716C8.19887 10.8716 8.3896 10.7926 8.53025 10.652L10.652 8.53025C10.7926 8.3896 10.8716 8.19887 10.8716 8C10.8716 7.80113 10.7926 7.6104 10.652 7.46975L8.53025 5.348C8.3896 5.2074 8.19887 5.12841 8 5.12841C7.80113 5.12841 7.6104 5.2074 7.46975 5.348Z" fill="white" fill-opacity="0.5"/>
                                                    </svg>
                                                    @break
                                                @case(2)
                                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M496 127.1C496 381.3 309.1 512 255.1 512C204.9 512 16 385.3 16 127.1c0-19.41 11.7-36.89 29.61-44.28l191.1-80.01c4.906-2.031 13.13-3.701 18.44-3.701c5.281 0 13.58 1.67 18.46 3.701l192 80.01C484.3 91.1 496 108.6 496 127.1z" fill="white" fill-opacity="0.5"/>
                                                    </svg>
                                                    @break
                                                @default
                                            @endswitch
                                        </div>
                                    @endif
                                </div>
                                <div class="card__body">
                                    <div class="card__price">
                                        <div class="price__text">Precio:</div>
                                        <div class="price__value">$ {{ number_format($item->price, 2) }}</div>
                                    </div>
                                    <form action="{{ route('paypal.processing') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="order" value="{{ encrypt($item->id) }}">
                                        <button type="submit" class="botn w-full reg bg-vo-red py-3 px-8 text-white rounded-lg text-center hover:bg-vo-red-over transition-colors">Comprar ahora <i class="fa-solid fa-arrow-right-long"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    {{ $paginate }}
                @else
                    <div class="empty">No hay elementos para mostrar</div>
                @endif
            </div>
        </section>
    </div>
</x-ecommerce-layout>