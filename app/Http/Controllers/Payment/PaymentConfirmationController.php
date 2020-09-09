<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use MercadoPago;

class PaymentConfirmationController extends Controller
{

    public function payment_confirmation(Request $request)
    {
        MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

        // Consulta de pagos en estado 2 en la tabla shoppingCarts
        $shopping_carts = ShoppingCart::where([
            ['payment_status_id', 2],
        ])->get();

        //consultar el paymentStatus de cada externalReference
        foreach ($shopping_carts as $shopping_cart) {

            $payment = new MercadoPago\Payment();
            $payment->get(
                "/v1/payments/search",
                array(
                    "external_reference" => $shopping_cart->external_reference,
                )
            );
            
            if ($payment->status == "approved"){
                $update = ShoppingCart::where([['id', $shopping_cart->id]])->update(
                    array(
                        'payment_status_id' => 3
                        //se debería almacenar en log los cambios de estado por este proceso
                    )
                );
            }
            else{
                $update = ShoppingCart::where([['id', $shopping_cart->id]])->update(
                    array(
                        'payment_status_id' => 5
                        //se debería almacenar en log los cambios de estado por este proceso
                    )
                );
            }
        }
    }
}
