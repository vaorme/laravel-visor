<?php

namespace App\Http\Controllers;

use App\Mail\PaymentPending;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Models\User;
use App\Providers\PaypalServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

class PaymentController extends Controller{
    public function index(Request $request){
        if (session()->has('error')) {
            return view('ecommerce.checkout');
        }
        return abort(404);
    }
    public function order(Request $request){
        if((isset($request->id) && !empty($request->id)) || (session()->has('error') || session()->has('success') || session()->has('processing'))){
            $data = [];
            $order = Order::where('order_id', $request->id)->first();
            if($order){
                $data['order'] = $order;
            }
            return view('ecommerce.checkout', $data);
        }
        return abort(404);
    }
    public function cancelled(Request $request){
        if((isset($request->order) && !empty($request->order)) || (session()->has('cancelled'))){
            $data = [];
            $order = Order::where('order_id', $request->order)->first();
            if($order){
                $data = ['order' => $order];
            }
            return view('ecommerce.checkout-cancelled', $data);
        }
        return abort(404);
    }
    public function paypalProcessing(Request $request){
        if(!isset($request->order)){
            return redirect()->route('checkout.index')->with('error', 'Algo salió mal.');
        }
        try{
            $id = decrypt($request->order);
        } catch (\Throwable $th) {
            return redirect()->route('checkout.index')->with('error', 'No lo intentes por favor.');
        }
        $product = Product::find($id);
        if(!$product->exists()){
            return redirect()->route('checkout.index')->with('error', 'Algo salió mal.');
        }
        $paypalService = new PaypalServiceProvider;
        $successUrl = route('paypal.success');
        $cancelUrl = route('paypal.cancel');
        $user = Auth::user();

        $response = $paypalService->createOrder($product->price, 'product-'.$product->id, $successUrl, $cancelUrl);
        if (isset($response['id']) && $response['id'] != null) {
            $createOrder = new Order();

            $createOrder->name = $user->username;
            $createOrder->email = $user->email;
            $createOrder->order_id  = $response['id'];
            $createOrder->total = $product->price;
            $createOrder->status = $response['status'];
            $createOrder->response = json_encode($response);
            $createOrder->user_id = $user->id;
            if($createOrder->save()){
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()->route('checkout.index')->with('error', 'Algo salió mal.');
            }
        } else {
            return redirect()->route('checkout.index')->with('error', $response['message'] ?? 'Algo salió mal.');
        }
    }

    public function paypalSuccess(Request $request){
        $paypalService = new PaypalServiceProvider();
        $response = $paypalService->capturePaymentOrder($request->token);
        if(isset($response['status'])){
            if ($response['status'] === 'COMPLETED'){
                $updateOrder = Order::where('order_id', $response['id'])->first();
                if($updateOrder){
                    $updateOrder->name = $response['payer']['name']['given_name'].' '.$response['payer']['name']['surname'];
                    $updateOrder->email = $response['payer']['email_address'];
                    $updateOrder->order_id  = $response['id'];
                    $totalAmount = 0;
                    $orderStatus = "";
                    $transactionId = "";
                    foreach ($response['purchase_units'] as $unit) {
                        foreach($unit['payments']['captures'] as $capture){
                            $orderStatus = $capture['status'];
                            $transactionId = $capture['id'];
                            $totalAmount += $capture['amount']['value'];
                        }
                    }
                    $updateOrder->total = $totalAmount;
                    $updateOrder->status = $orderStatus;
                    $updateOrder->transaction_id = $transactionId;
                    $updateOrder->response = json_encode($response);
                    $updateOrder->user_id = Auth::id();
                    
                    if($updateOrder->save()){
                        $user = Auth::user();
                        foreach($response['purchase_units'] as $units){
                            $productId = explode('-', $units['reference_id']);
                            $orderItem = new OrderItems();
                            $orderItem->order_id = $updateOrder->id;
                            $orderItem->product_id = $productId[1];
                            $orderItem->save();
                        }
                        if(!empty($orderStatus) && $orderStatus === "COMPLETED"){
                            $product = Product::find($productId[1]);
                            switch ($product->product_type_id) {
                                case 1:
                                    $purchaseCoins =$user->purchaseCoins($product->coins);
                                    break;
                                case 2:
                                    $purchaseDays = $user->purchaseDays($product->days_without_ads);
                                    break;
                                default:
                                    break;
                            }
                        }
                        if($updateOrder->status){
                            $product = Product::find($productId[1]);
                            switch ($updateOrder->status) {
                                case 'COMPLETED':
                                    $mailData = [
                                        'title' => 'Pedido completado',
                                        'message' => '¡Tu pago ha sido procesado con éxito! Gracias por tu transacción.',
                                        'product' => $product,
                                        'status' => 'Completado',
                                    ];
                                    Mail::to($user->email)->send(new PaymentPending($mailData, "Pago completado Orden #".$updateOrder->order_id." | Nartag"));
                                    break;
                                case 'PENDING':
                                    $mailData = [
                                        'title' => 'Pedido pendiente',
                                        'message' => 'Estamos procesando tu pago y lo estamos validando. Esto puede tomar un tiempo, así que por favor ten paciencia.',
                                        'product' => $product,
                                        'status' => 'Pendiente',
                                    ];
                                    Mail::to($user->email)->send(new PaymentPending($mailData, "Pago pendiente Orden #".$updateOrder->order_id." | Nartag"));
                                    break;
                                default:
                                    break;
                            }
                        }
                        return redirect()->route('checkout.order', ['id' => $response['id']]);
                    }else{
                        return redirect()->route('checkout.index')->with('error', 'Algo salió mal.');
                    }
                }else{
                    return redirect()->route('checkout.index')->with('error', 'No se pudo encontrar orden con ese ID: '.$response['id']);
                }
            }else{
                $user = Auth::user();
                $createOrder = new Order();
                $createOrder->name = $user->username;
                $createOrder->email = $user->email;
                $createOrder->order_id  = $response['id'];
                $totalAmount = 0;
    
                foreach ($response['purchase_units'] as $unit) {
                    foreach($unit['payments']['captures'] as $capture){
                        $totalAmount += $capture['amount']['value'];
                    }
                }
                $createOrder->total = $totalAmount;
                $createOrder->status = $response['status'];
                $createOrder->response = json_encode($response);
                $createOrder->user_id = $user->id;
                if($createOrder->save()){
                    return redirect()->route('checkout.index');
                }else{
                    return redirect()->route('checkout.index')->with('error', 'No puedes estar aquí. 1');
                }
            }
        }
        return redirect()->route('checkout.index')->with('error', 'No puedes estar aquí. 2');
    }

    public function paypalCancel(Request $request){
        if(isset($request->token) && !empty($request->token)){
            $order = Order::where('order_id', $request->token)->first();
            $order->status = "CANCELLED";
            if($order->save()){
                return redirect()->route('checkout.cancelled', ['order' => $request->token]);
            }
            return redirect()->route('checkout.cancelled');
        }else{
            return redirect()->route('checkout.cancelled');
        }
    }
}