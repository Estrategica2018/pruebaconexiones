<?php

namespace App\Http\Controllers\Shopping;

use App\Http\Controllers\Controller;
use App\Mail\SendRejectedPaymentNotification;
use App\Mail\SendSuccessfulPaymentNotification;
use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedContentAccountService;
use App\Models\SequenceMoment;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use MercadoPago;
use File;

class NotifyCallbackController extends Controller
{
    public function notificacion_callback(Request $request)
    {
        ///LOG
        $log_path = public_path(). '/payments-logs/';
        File::isDirectory($log_path) or File::makeDirectory($log_path, 0777, true, true);
        $file = 'payment-'.date('Ymd His').'.txt';
        if(isset($request->isSimulador)) {
            $file = 'simulator-'.$file;
        }
        ///LOG
        
        $this->writeLog($log_path.'/'.$file,'--------------User-------------------');    
        if(auth("afiliadoempresa") != null ) {
            $this->writeLog($log_path.'/'.$file,auth("afiliadoempresa")->user());    
        }
        else {
            $this->writeLog($log_path.'/'.$file,'User Anonimous');    
        }
        $this->writeLog($log_path.'/'.$file,'--------------User-------------------');    
        $this->writeLog($log_path.'/'.$file,'--------------Request-------------------');    
        $this->writeLog($log_path.'/'.$file,$request);
        ///LOG


        $price_callback = $request->session()->pull('shopping_cart_notify_price'); //remove cache to session
        if(!isset($request->isSimulador))
        MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));
        $shoppingCart = null;
        $ratingPlan = null;
        $afiliado_empresa = null;

        if ($request->collection_status == 'approved') {

            $transaction_date = date("Y-m-d H:i:s");
            $update = ShoppingCart::where([["company_affiliated_id", auth("afiliadoempresa")->user()->id],
                ['payment_status_id', 2],
                ['payment_transaction_id', $request->external_reference]])->
            update(array(
                'payment_status_id' => '3',
                'payment_process_date' => $transaction_date,
                'approval_code' => $request->collection_id,
                'payment_method' => 'TC'
            ));

            if ($request->user('afiliadoempresa')) {

                $afiliado_empresa = $request->user('afiliadoempresa');
                $shoppingCarts = ShoppingCart::
                with('rating_plan', 'shopping_cart_product')->
                where([
                    ['company_affiliated_id', $request->user('afiliadoempresa')->id],
                    ['payment_transaction_id', $request->external_reference],
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

        } else if ($request->collection_status == 'rejected' || isset($request->collection_status)) {

            $update = ShoppingCart::where([["company_affiliated_id", auth("afiliadoempresa")->user()->id],
                ['payment_status_id', 2],
                ['payment_transaction_id', $request->external_reference]])->
            update(array(
                'payment_status_id' => '4',
                'payment_process_date' => date("Y-m-d H:i:s"),
                'approval_code' => $request->collection_id,
                'payment_method' => 'TC'
            ));
            // Generando registro nuevo para shoppingCart
            $shoppingCarts = ShoppingCart::with('shopping_cart_product')->
            where([["company_affiliated_id", auth("afiliadoempresa")->user()->id],
                ['payment_status_id', 4],
                ['payment_transaction_id', $request->external_reference]])->get();

            foreach ($shoppingCarts as $shoppingCart) {
                $shoppingCart_n = new ShoppingCart();
                $shoppingCart_n->company_affiliated_id = $shoppingCart->company_affiliated_id;
                $shoppingCart_n->rating_plan_id = $shoppingCart->rating_plan_id;
                $shoppingCart_n->type_product_id = $shoppingCart->type_product_id;
                $shoppingCart_n->payment_status_id = 1;
                $shoppingCart_n->payment_transaction_id = $shoppingCart->payment_transaction_id;
                $shoppingCart_n->payment_init_date = $shoppingCart->payment_process_date;

                $shoppingCart_n->save();

                foreach ($shoppingCart->shopping_cart_product as $shopping_cart_product) {
                    $shopping_cart_product_n = new ShoppingCartProduct();
                    $shopping_cart_product_n->shopping_cart_id = $shoppingCart_n->id;
                    $shopping_cart_product_n->product_id = $shopping_cart_product->product_id;
                    $shopping_cart_product_n->save();
                }

            }
            $transaction_date = ShoppingCart::select('payment_process_date')->where('payment_transaction_id', $request->external_reference)->first();
            //Envío correo de pago rechazado
            Mail::to($request->user('afiliadoempresa')->email)->send(
                new SendRejectedPaymentNotification($shoppingCart, $request, $request->user('afiliadoempresa'), $price_callback, $transaction_date));
            return redirect()->route('shoppingCart');
        } else {

            // Flujo PSE
            ///LOG
            $this->writeLog($log_path.'/'.$file,'--------------Pago por PSE-------------------');
            $this->writeLog($log_path.'/'.$file,'--------------> headers');
            $this->writeLog($log_path.'/'.$file, request()->headers);
            ///LOG
            

            //obtiene el referer
            $referer = request()->headers->get('referer');
            $this->writeLog($log_path.'/'.$file,'--------------> referer');
            $this->writeLog($log_path.'/'.$file, $referer);
            
            //Se realiza proceso de split para obtener el paymentId
            parse_str(parse_url($referer, PHP_URL_QUERY), $queries);
            
            ///LOG
            $this->writeLog($log_path.'/'.$file,'--------------> queries');
            $this->writeLog($log_path.'/'.$file, $queries);
            ///LOG
            
            $payment_id = $queries['payment_id'];
            $request->request->add(['variable' => 'value']);
            $request->request->add(['collection_id' => $payment_id]);
            //Se genera el cliente para consumir el API de MP, se obtiene el estado del pago
            $client = new \GuzzleHttp\Client([
                // 'headers' => $headers,
            ]);

            $res = $client->request(
                'GET',
                'https://api.mercadopago.com/v1/payments/'.$payment_id,
                ['query'=>["access_token" => env('MERCADOPAGO_ACCESS_TOKEN')
                ]]

            );
            $data = json_decode($res->getBody()->getContents());
            
            
            ///LOG
            $this->writeLog($log_path.'/'.$file,'--------------> data');
            $this->writeLog($log_path.'/'.$file, $data);
            ///LOG
            
            //Logica diferencial entre respuesta de pagos PSE
            if ($data->status === 'approved') {
                // Pago exitoso
                $update = ShoppingCart::where([["company_affiliated_id", auth("afiliadoempresa")->user()->id],
                    ['payment_status_id', 2],
                    ['payment_transaction_id', $data->external_reference]])->
                update(array(
                    'payment_status_id' => '3',
                    'payment_process_date' => date("Y-m-d H:i:s"),
                    'approval_code' => $payment_id,
                    'payment_method' => 'PSE'
                ));
                $transaction_date = ShoppingCart::select('payment_process_date')->where('payment_transaction_id', $data->external_reference)->first();
                if ($request->user('afiliadoempresa')) {

                    $afiliado_empresa = $request->user('afiliadoempresa');
                    $shoppingCarts = ShoppingCart::
                    with('rating_plan', 'shopping_cart_product')->
                    where([
                        ['company_affiliated_id', $request->user('afiliadoempresa')->id],
                        ['payment_transaction_id', $data->external_reference],
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
                //Envío correo de pago exitoso
                Mail::to($request->user('afiliadoempresa')->email)->send(
                    new SendSuccessfulPaymentNotification($shoppingCart, $request, $afiliado_empresa, $price_callback, $transaction_date));
                return redirect()->route('tutor.products', ['empresa' => 'conexiones']);

            } else {
                $update = ShoppingCart::where([["company_affiliated_id", auth("afiliadoempresa")->user()->id],
                    ['payment_status_id', 2],
                    ['payment_transaction_id', $data->external_reference]])->
                update(array(
                    'payment_status_id' => '4',
                    'payment_process_date' => date("Y-m-d H:i:s"),
                    'approval_code' => $payment_id,
                    'payment_method' => 'PSE'
                ));
                // Generando registro nuevo para shoppingCart
                $shoppingCarts = ShoppingCart::with('shopping_cart_product')->
                where([["company_affiliated_id", auth("afiliadoempresa")->user()->id],
                    ['payment_status_id', 4],
                    ['payment_transaction_id', $data->external_reference]])->get();

                foreach ($shoppingCarts as $shoppingCart) {
                    $shoppingCart_n = new ShoppingCart();
                    $shoppingCart_n->company_affiliated_id = $shoppingCart->company_affiliated_id;
                    $shoppingCart_n->rating_plan_id = $shoppingCart->rating_plan_id;
                    $shoppingCart_n->type_product_id = $shoppingCart->type_product_id;
                    $shoppingCart_n->payment_status_id = 1;
                    $shoppingCart_n->payment_transaction_id = $shoppingCart->payment_transaction_id;
                    $shoppingCart_n->payment_init_date = $shoppingCart->payment_process_date;

                    $shoppingCart_n->save();

                    foreach ($shoppingCart->shopping_cart_product as $shopping_cart_product) {
                        $shopping_cart_product_n = new ShoppingCartProduct();
                        $shopping_cart_product_n->shopping_cart_id = $shoppingCart_n->id;
                        $shopping_cart_product_n->product_id = $shopping_cart_product->product_id;
                        $shopping_cart_product_n->save();
                    }

                }
                $transaction_date = ShoppingCart::select('payment_process_date')->where('payment_transaction_id', $data->external_reference)->first();
                //Envío correo de pago rechazado
                Mail::to($request->user('afiliadoempresa')->email)->send(
                    new SendRejectedPaymentNotification($shoppingCart, $request, $request->user('afiliadoempresa'), $price_callback, $transaction_date));
                return redirect()->route('shoppingCart');
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

    public function writeLog($filename, $string) {

        if (!file_exists($filename)) {
            touch($filename, strtotime('-1 days'));
        }
        
        try {
            if(gettype ($string) == 'array') {
                file_put_contents($filename, 'Error escribiendo en el archivo: Tipo de variable array'  . PHP_EOL, FILE_APPEND);
            }
            else {    
                file_put_contents($filename, $string. PHP_EOL, FILE_APPEND );
            }
        } catch (Exception $e) {
            file_put_contents($filename, 'Error escribiendo en el archivo: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }
}
