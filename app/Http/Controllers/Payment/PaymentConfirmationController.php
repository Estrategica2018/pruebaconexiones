<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Mail\SendSuccessfulPaymentNotification;
use App\Models\AffiliatedAccountService;
use App\Models\AffiliatedContentAccountService;
use App\Models\AfiliadoEmpresa;
use App\Models\SequenceMoment;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use MercadoPago;
use File;
use Carbon\Carbon;

class PaymentConfirmationController extends Controller
{
   
    public function payment_confirmation_test(Request $request) {
        $this->payment_confirmation();
    }

    public function payment_confirmation()
    {
        ///LOG
        $log_path = public_path(). '/payments-logs/';
        File::isDirectory($log_path) or File::makeDirectory($log_path, 0777, true, true);
        $file = 'payment-bash-'.date('Ymd His').'.txt';
 
        MercadoPago\SDK::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

        // Consulta de pagos en estado 2 en la tabla shoppingCarts
        $shopping_carts = ShoppingCart::with("affiliate", "rating_plan", "shopping_cart_product")->where([
            ['payment_status_id', 2],
        ])->get();

        //$this->writeLog($log_path.'/'.$file,'-- Transacciones pendientes:' . count($shopping_carts));   
        
        foreach ($shopping_carts as $shoppingCart) {
            //$this->writeLog($log_path.'/'.$file,'');
            //$this->writeLog($log_path.'/'.$file,'* Buscando en el external references en mercadopago [ payment_transaction_id: '.$shoppingCart->payment_transaction_id.' ]');
            
            $filters = array(
                "external_reference" => $shoppingCart->payment_transaction_id,
            );
            
            $payments = MercadoPago\Payment::search($filters);
            $payment = end($payments); 
            
            if ($payment && $payment->status == "approved") {
                // LOG
                $this->writeLog($log_path.'/'.$file,'---- Transacción Aprobada: payment_transaction_id ['.$shoppingCart->payment_transaction_id.']');  
                $this->writeLog($log_path.'/'.$file,$payment);
                $this->writeLog($log_path.'/'.$file,'--- Datos del afiliado');
                $this->writeLog($log_path.'/'.$file,$shoppingCart->affiliate);
                // LOG

                $update = ShoppingCart::where([['id', $shoppingCart->id],
                    ['id', $payment->external_reference]])->
                    update(array(
                    'payment_status_id' => '3',
                    'payment_process_date' => $payment->date_approved,
                    'approval_code' => $payment->id,
                    'payment_method' => 'PSE',
                ));

                $this->writeLog($log_path.'/'.$file,'----- Modificando ['.count($update).'] registros para el shopping cart');  

                $afiliado_empresa = AfiliadoEmpresa::find($shoppingCart->affiliate->id);
                $ratingPlan = $shoppingCart->rating_plan;
                if ($ratingPlan) {
                    //Iniciar el tiempo de acceso a las secuencias
                    $this->writeLog($log_path.'/'.$file, '----- Agregando plan '.$ratingPlan->name);
                    $this->writeLog($log_path.'/'.$file, $ratingPlan);  
                    $this->addRatingPlanPaid($shoppingCart, $ratingPlan, $afiliado_empresa);
                }
                
                //Envío correo de pago exitoso
                Mail::to($afiliado_empresa->email)->send(
                    new SendSuccessfulPaymentNotification($shoppingCart, $payment, $afiliado_empresa, $payment->transaction_amount, $payment->date_approved));
                return redirect()->route('tutor.products', ['empresa' => 'conexiones']);
            }
            else {
                $date = Carbon::now()->subMinutes(20);
                if($shoppingCart->updated_at->lt($date)){
                    $this->writeLog($log_path.'/'.$file,'*  No se encontro aprobación para el ID ' . $shoppingCart->payment_transaction_id );   
                    $this->writeLog($log_path.'/'.$file,'-------  Shopping cart ID ['.$shoppingCart->id.']'   );  
                    $this->writeLog($log_path.'/'.$file,$shoppingCart); 
                    $this->writeLog($log_path.'/'.$file,'-------  Datos del afiliado');
                    $this->writeLog($log_path.'/'.$file,$shoppingCart->affiliate); 
                
                    $this->writeLog($log_path.'/'.$file,'-------  Fecha de inicio de transaccion fuera de Vigencia:  '. $shoppingCart->updated_at);
                    $this->writeLog($log_path.'/'.$file,'-------  Se marcaran como rechazados los registro  del shoppingCartId ' . $shoppingCart->id);
                 
                    $update = ShoppingCart::where('id',$shoppingCart->id)
                    -> update(array(
                        'payment_status_id' => '5'
                    )); 
                }
                
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
        if(gettype($string) == "array") {
            $string = json_encode($string);
        }
        file_put_contents($filename, $string . PHP_EOL, FILE_APPEND);
    }
}
