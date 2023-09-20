<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WebHooksController extends Controller{
    const EVENT_PAYMENT_CAPTURE_PENDING = "PAYMENT.CAPTURE.PENDING";
    const EVENT_PAYMENT_CAPTURE_COMPLETED = "PAYMENT.CAPTURE.COMPLETED";
    public function paypalWebhook(Request $request){
        $eventType = $request->input('event_type');
        $resource = $request->input('resource');
        Log::debug($resource['id']." - ".$eventType);
        if($eventType === self::EVENT_PAYMENT_CAPTURE_COMPLETED){
            $order = Order::where('transaction_id', $resource['id'])->first();
            if($order && $order->status !== "COMPLETED"){
                $order->status = "COMPLETED";
                $orderItem = OrderItems::where('order_id', $order->id)->first();
                $user = User::find($order->user_id);
                if($orderItem){
                    $product = Product::find($orderItem->product_id);
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
                $order->save();
            }
            Log::debug("Orden #".$resource['id']." no existe");
        }
    }
}
