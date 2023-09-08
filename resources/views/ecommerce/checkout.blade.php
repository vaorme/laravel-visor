<x-ecommerce-layout>
    <div class="main__wrap">
        <section class="checkout">
            <div class="check__content">
                <div class="check__head">
                    <h1>Detalles de la compra</h1>
                </div>
                <div class="check__body">
                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-error">
                            {{ session()->get('error') }}
                        </div>
                    @endif
                    {{-- <div class="check__details">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="check__title">Producto:</td>
                                        <td class="check__element">{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="check__title">Cantidad:</td>
                                        <td class="check__element">1</td>
                                    </tr>
                                    <tr>
                                        <td class="check__title">Tipo:</td>
                                        <td class="check__element">{{ $product->type->name }}</td>
                                    </tr>
                                    <tr>
                                        @switch($product->type->id)
                                            @case(1)
                                                <td class="check__title">Monedas:</td>
                                                <td class="check__element">{{ $product->coins }}</td>
                                                @break
                                            @case(2)
                                                <td class="check__title">Dias:</td>
                                                <td class="check__element">{{ $product->days_without_ads }}</td>
                                                @break
                                            @default
                                                
                                        @endswitch
                                    </tr>
                                    <tr>
                                        <td class="check__title">Precio:</td>
                                        <td class="check__element">$ {{ number_format($product->price, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                    </div> --}}
                </div>
                <div class="check__footer">
                    <button class="botn w-full block reg bg-vo-red py-3 px-8 text-white rounded-lg text-center hover:bg-vo-red-over transition-colors">Pagar ahora</button>
                </div>
            </div>
        </section>
    </div>
</x-ecommerce-layout>