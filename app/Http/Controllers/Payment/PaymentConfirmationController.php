<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedContentAccountService;
use App\Models\SequenceMoment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSuccessfulPaymentNotification;
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

                //dd($shopping_cart, $payment->payer->email);

                $update = ShoppingCart::where([['id', $shopping_cart->id],
                    ['payment_transaction_id', $payment->external_reference]])->
                    update(array(
                    'payment_status_id' => '3',
                    'payment_process_date' => $payment->date_approved,
                    'approval_code' => $payment->id,
                    'payment_method' => 'PSE',
                ));

                //if ($request->user('afiliadoempresa')) {

                    //$afiliado_empresa = $request->user('afiliadoempresa');
                    $afiliado_empresa = $shopping_cart->company_affiliated_id;
                    $shoppingCartsPay = ShoppingCart::
                        with('rating_plan', 'shopping_cart_product')->
                        where([
                        ['company_affiliated_id', $shopping_cart->company_affiliated_id],
                        ['payment_transaction_id', $payment->external_reference],
                        ['payment_status_id', 3],
                    ])->get();

                    foreach ($shoppingCartsPay as $shoppingCart) {
                        $ratingPlan = $shoppingCart->rating_plan;
                        if ($ratingPlan) {
                            //Iniciar el tiempo de acceso a las secuencias
                            $this->addRatingPlanPaid($shoppingCart, $ratingPlan, $afiliado_empresa);
                        }
                    }
                //}
                $transaction_date = ShoppingCart::select('payment_process_date')->where('payment_transaction_id', $payment->external_reference)->first();
                //Envío correo de pago exitoso
                //Mail::to($request->user('afiliadoempresa')->email)->send(
                Mail::to($payment->payer->email)->send(
                    new SendSuccessfulPaymentNotification($shoppingCart, $payment, $afiliado_empresa, $payment->transaction_amount, $transaction_date));
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

    public function addRatingPlanPaid($shoppingCart, $ratingPlan, $afiliado_empresa)
    {
        $affiliatedAccountService = new AffiliatedAccountService();
        $affiliatedAccountService->company_affiliated_id = $afiliado_empresa->id;
        $affiliatedAccountService->rating_plan_id = $ratingPlan->id;
        $affiliatedAccountService->rating_plan_type = $ratingPlan->type_rating_plan_id;
        $affiliatedAccountService->shopping_cart_id = $shoppingCart->id;

        $affiliatedAccountService->end_date = date('Y-m-d', strtotime('+ ' . $ratingPlan->days . ' day'));
        $affiliatedAccountService->rating_plan_type = $ratingPlan->type_rating_plan_id;
        $affiliatedAccountService->init_date = date('Y-m-d');

        $affiliatedAccountService->save();
        if ($ratingPlan->type_rating_plan_id == 1) { //sequence rating plan
            foreach ($shoppingCart->shopping_cart_product as $product) {
                $sequenceMoments = SequenceMoment::where('sequence_company_id', $product->product_id)->get();
                foreach ($sequenceMoments as $sequenceMoment) {
                    $affiliatedContentAccountService = new AffiliatedContentAccountService();
                    $affiliatedContentAccountService->affiliated_account_service_id = $affiliatedAccountService->id;
                    $affiliatedContentAccountService->type_product_id = $ratingPlan->type_rating_plan_id;
                    $affiliatedContentAccountService->sequence_id = $product->product_id;
                    $affiliatedContentAccountService->moment_id = $sequenceMoment->id;
                    $affiliatedContentAccountService->save();
                }

            }
        } else if ($ratingPlan->type_rating_plan_id == 2 || $ratingPlan->type_rating_plan_id == 3) { //moment / experiences rating plan
            foreach ($shoppingCart->shopping_cart_product as $product) {
                $affiliatedContentAccountService = new AffiliatedContentAccountService();
                $affiliatedContentAccountService->affiliated_account_service_id = $affiliatedAccountService->id;
                $affiliatedContentAccountService->type_product_id = $ratingPlan->type_rating_plan_id;
                $moment = SequenceMoment::find($product->product_id);
                $affiliatedContentAccountService->sequence_id = $moment->sequence_company_id;
                $affiliatedContentAccountService->moment_id = $product->product_id;
                $affiliatedContentAccountService->save();
            }
        }
    }
}
