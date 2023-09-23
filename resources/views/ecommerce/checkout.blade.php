<x-ecommerce-layout>
    <div class="main__wrap">
        <section class="checkout">
            <div class="check__content">
                @if (session()->has('error'))
                    <div class="alert alert-error">
                        {{ session()->get('error') }}
                    </div>
                @endif
                @if (isset($order) && !empty($order) && $order->user_id === Auth::id())
                    <div class="check__head">
                        <h1>Detalles del pedido #{{ $order->order_id }}</h1>
                        @switch($order->status)
                            @case("COMPLETED")
                                <div class="alert alert-success">¡Tu pago ha sido procesado con éxito! Gracias por tu transacción.</div>
                                @break
                            @case("PENDING")
                                <div class="alert alert-gray">Estamos procesando tu pago y lo estamos validando. Esto puede tomar un tiempo, así que por favor ten paciencia.</div>
                                @break
                            @case("CANCELLED")
                                <div class="alert alert-error">Tu pago ha sido cancelado a solicitud tuya o debido a un problema técnico. Si tienes alguna pregunta, contáctanos.</div>
                                @break
                            @case("REFUNDED")
                                <div class="alert alert-error">Hemos procesado tu solicitud de reembolso y los fondos están en proceso de ser devueltos a tu cuenta.</div>
                                @break
                            @case("FAILED")
                                <div class="alert alert-error">Lamentablemente, tu pago no pudo ser procesado. Por favor, verifica la información de pago y vuelve a intentarlo.</div>
                                @break
                            @default
                                
                        @endswitch
                    </div>
                    <div class="check__body">
                        <div class="check__details">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="check__title">Estado:</td>
                                        <td class="check__element">
                                            @switch($order->status)
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
                                    </tr>
                                    @foreach ($order->products as $item)
                                        <tr>
                                            <td class="check__title">Producto:</td>
                                            <td class="check__element">{{ $item->product->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="check__title">Cantidad:</td>
                                            <td class="check__element">1</td>
                                        </tr>
                                        <tr>
                                            <td class="check__title">Tipo:</td>
                                            <td class="check__element">{{ $item->product->type->name }}</td>
                                        </tr>
                                        <tr>
                                            @switch($item->product->type->id)
                                                @case(1)
                                                    <td class="check__title">Monedas:</td>
                                                    <td class="check__element">{{ $item->product->coins }}</td>
                                                    @break
                                                @case(2)
                                                    <td class="check__title">Dias:</td>
                                                    <td class="check__element">{{ $item->product->days_without_ads }}</td>
                                                    @break
                                                @default
                                            @endswitch
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="check__title">Precio:</td>
                                        <td class="check__element">$ {{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="check__footer">
                        <a href="{{ route('shop.index') }}" class="botn w-full block reg bg-vo-red py-3 px-8 text-white rounded-lg text-center hover:bg-vo-red-over transition-colors">Volver a la tienda</a>
                    </div>
                @else
                    <div class="alert alert-error">Lo sentimos, número de orden no encontrado</div>
                @endif
            </div>
        </section>
    </div>
</x-ecommerce-layout>