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
        $shopping_carts = ShoppingCart::with("affiliate")->where([
            ['payment_status_id', 2],
        ])->get();

        foreach ($shopping_carts as $shopping_cart) {

            $filters = array(
                "external_reference" => $shopping_cart->payment_transaction_id,
            );
            $payments = MercadoPago\Payment::search($filters);
            $payment = end($payments);

            if ($payment->status == "approved") {

                dd($shopping_cart, $payment, $shopping_cart->affiliate()->user('afiliadoempresa'));

                $update = ShoppingCart::where([['id', $shopping_cart->id],
                    ['payment_transaction_id', $payment->external_reference]])->
                    update(array(
                    'payment_status_id' => '3',
                    'payment_process_date' => $payment->date_approved,
                    'approval_code' => $payment->id,
                    'payment_method' => 'PSE',
                ));

                if ($request->user('afiliadoempresa')) {

                    $afiliado_empresa = $request->user('afiliadoempresa');
                    $shoppingCarts = ShoppingCart::
                        with('rating_plan', 'shopping_cart_product')->
                        where([
                        ['company_affiliated_id', $request->user('afiliadoempresa')->id],
                        ['payment_transaction_id', $payment->external_reference],
                        ['payment_status_id', 3],
                    ])->get();

                    foreach ($shoppingCarts as $shoppingCart) {
                        $ratingPlan = $shoppingCart->rating_plan;
                        if ($ratingPlan) {
                            //Iniciar el tiempo de acceso a las secuencias
                            $this->addRatingPlanPaid($shoppingCart, $ratingPlan, $afiliado_empresa);
                        }
                    }
                }
                $transaction_date = ShoppingCart::select('payment_process_date')->where('payment_transaction_id', $request->external_reference)->first();
                //Envío correo de pago exitoso
                Mail::to($request->user('afiliadoempresa')->email)->send(
                    new SendSuccessfulPaymentNotification($shoppingCart, $request, $afiliado_empresa, $price_callback, $transaction_date));
                return redirect()->route('tutor.products', ['empresa' => 'conexiones']);
            } else {
                $update = ShoppingCart::where([['id', $shopping_cart->id]])->update(
                    array(
                        'payment_status_id' => 5,
                        //se debería almacenar en log los cambios de estado por este proceso
                    )
                );
            }
        }
    }
}
