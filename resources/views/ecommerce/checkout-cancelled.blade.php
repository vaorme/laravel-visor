<x-ecommerce-layout>
    <div class="main__wrap">
        <section class="checkout">
            <div class="check__content">
                @if (isset($order) && !empty($order) && $order->user_id === Auth::id())
                    @if ($order->status == "CANCELLED")
                        <div class="alert alert-error">Has cancelado la transacción #{{ $order->order_id }}</div>
                    @else
                        <div class="alert alert-error">Lo sentimos, número de orden no encontrado</div>
                    @endif
                @else
                    <div class="alert alert-error">Lo sentimos, número de orden no encontrado</div>
                @endif
            </div>
        </section>
    </div>
</x-ecommerce-layout>