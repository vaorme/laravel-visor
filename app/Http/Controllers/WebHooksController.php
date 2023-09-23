<?php

namespace App\Http\Controllers;

use App\Mail\PaymentPending;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebHooksController extends Controller{
    const EVENT_PAYMENT_CAPTURE_PENDING = "PAYMENT.CAPTURE.PENDING";
    const EVENT_PAYMENT_CAPTURE_REFUNDED = "PAYMENT.CAPTURE.REFUNDED";
    const EVENT_PAYMENT_CAPTURE_COMPLETED = "PAYMENT.CAPTURE.COMPLETED";
    public function paypalWebhook(Request $request){
        $eventType = $request->input('event_type');
        $resource = $request->input('resource');
        if($eventType === self::EVENT_PAYMENT_CAPTURE_COMPLETED){
            $order = Order::where('transaction_id', $resource['id'])->first();
            if($order && $order->status !== "COMPLETED"){
                $order->status = "COMPLETED";
                $orderItem = OrderItems::where('order_id', $order->id)->first();
                $user = User::find($order->user_id);
                $product = Product::find($orderItem->product_id);
                if($orderItem){
                    switch ($product->product_type_id) {
                        case 1:
                            $user->purchaseCoins($product->coins);
                            break;
                        case 2:
                            $user->purchaseDays($product->days_without_ads);
                            break;
                        default:
                            break;
                    }
                }
                $mailData = [
                    'title' => 'Pedido completado',
                    'message' => '¡Tu pago ha sido procesado con éxito! Gracias por tu transacción.',
                    'product' => $product,
                    'status' => 'Completado',
                ];
                Mail::to($user->email)->send(new PaymentPending($mailData, "Pago completado Orden #".$order->order_id." | Nartag"));
                $order->save();
            }
            Log::channel('webhook_errors')->error("Completed orden #".$resource['id']." no existe");
        }
        if($eventType === self::EVENT_PAYMENT_CAPTURE_REFUNDED){
            $order = Order::where('transaction_id', $resource['id'])->first();
            if($order && $order->status !== "REFUNDED"){
                $oldStatus = $order->status;
                $order->status = "REFUNDED";
                $orderItem = OrderItems::where('order_id', $order->id)->first();
                $user = User::find($order->user_id);
                $product = Product::find($orderItem->product_id);
                if($orderItem){
                    switch ($product->product_type_id) {
                        case 1:
                            if($oldStatus === "COMPLETED"){
                                $user->removeCoins($product->coins);
                            }
                            break;
                        case 2:
                            if($oldStatus === "COMPLETED"){
                                $user->removeDays($product->days_without_ads);
                            }
                            break;
                        default:
                            break;
                    }
                }
                $mailData = [
                    'title' => 'Pedido reembolsado',
                    'message' => 'Hemos procesado tu solicitud de reembolso y los fondos están en proceso de ser devueltos a tu cuenta.',
                    'product' => $product,
                    'status' => 'Reembolsado',
                ];
                Mail::to($user->email)->send(new PaymentPending($mailData, "Pago reembolsado Orden #".$order->order_id." | Nartag"));
                $order->save();
            }
            Log::channel('webhook_errors')->error("Refund orden #".$resource['id']." no existe");
        }
        if($eventType === self::EVENT_PAYMENT_CAPTURE_PENDING){
            $order = Order::where('transaction_id', $resource['id'])->first();
            if($order && $order->status !== "PENDING"){
                $order->status = "PENDING";
                $orderItem = OrderItems::where('order_id', $order->id)->first();
                $user = User::find($order->user_id);
                $product = Product::find($orderItem->product_id);
                $mailData = [
                    'title' => 'Pedido pendiente',
                    'message' => 'Estamos procesando tu pago y lo estamos validando. Esto puede tomar un tiempo, así que por favor ten paciencia.',
                    'product' => $product,
                    'status' => 'Pendiente',
                ];
                Mail::to($user->email)->send(new PaymentPending($mailData, "Pago pendiente Orden #".$order->order_id." | Nartag"));
                $order->save();
            }
            Log::channel('webhook_errors')->error("Pending orden #".$resource['id']." no existe");
        }
    }
}
