<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use App\Providers\PaypalServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller{
    public function index(Request $request){
        return view('ecommerce.checkout');
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

        $response = $paypalService->createOrder($product->price, 'order-'.$product->id, $successUrl, $cancelUrl);
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->route('cancel.payment')->with('error', 'Algo salió mal.');
        } else {
            return redirect()->route('create.payment')->with('error', $response['message'] ?? 'Algo salió mal.');
        }
    }

    public function paypalSuccess(Request $request){
        $paypalService = new PaypalServiceProvider();
        $response = $paypalService->capturePaymentOrder($request->token);
        dd($response);
        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $createOrder = new Order();

            $createOrder->name = $response['payer']['name']['given_name'].' '.$response['payer']['name']['surname'];
            $createOrder->email = $response['payer']['email_address'];
            $createOrder->order_id  = $response['id'];
            $totalAmount = 0;

            foreach ($response['purchase_units'] as $unit) {
                foreach($unit['payments']['captures'] as $capture){
                    $totalAmount += $capture['amount']['value'];
                }
            }
            $createOrder->total = $totalAmount;
            // :ORDER STATUS - 1: Completed 2: Processing 3: On Hold 4: Failed 5: Canceled
            $createOrder->order_status_id = 1;
            $createOrder->user_id = Auth::id();

            if($createOrder->save()){
                foreach($response['purchase_units'] as $units){
                    $productId = explode('-', $units['reference_id']);
                    $orderItem = new OrderItems();
                    $orderItem->order_id = $createOrder->id;
                    $orderItem->product_id = $productId[1];
                    $orderItem->save();
                }
                return redirect()->route('checkout.index')->with('success', 'Transacción completada.');
            }else{
                return redirect()->route('checkout.index')->with('error', 'Algo salió mal.');
            }
        } else {
            return redirect()->route('checkout.index')->with('error', 'No puedes estar aquí.');
        }
    }

    public function paypalCancel(){
        return redirect()->route('checkout.canceled')->with('error', 'Has cancelado la transacción.');
    }
}