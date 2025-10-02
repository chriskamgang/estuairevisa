<?php

namespace App\Http\Controllers\Gateway\nowpayments;

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Controller;


class ProcessController extends Controller
{

    public static function process($request, $nowpay, $amount, $deposit)
    {
        
        $mode = $nowpay->gateway_parameters->mode == 'sandbox' ? true : false;
        $pay = new NowPaymentsAPI($nowpay->gateway_parameters->nowpay_key,$mode);

        $data = array(
            "price_amount" => $amount,
            "price_currency" => $nowpay->gateway_parameters->gateway_currency,
            "ipn_callback_url" => route('user.nowpay.success'),
            "order_id" => $deposit->transaction_id,
            "order_description" => "Plan Purchage",
            'success_url'=>route('user.nowpay.success'),
	        'cancel_url'=>route('user.nowpay.success')
          );

          $a = json_decode($pay->createInvoice($data));

          return $a;

    }

    public function ipn()
    {
        if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
            $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            ksort($request_data);
            $sorted_request_json = json_encode($request_data, JSON_UNESCAPED_SLASHES);
            if ($request_json !== false && !empty($request_json)) {
                $gateway    = Gateway::where('alias', 'NowPaymentsHosted')->first();
                $gatewayAcc = json_decode($gateway->gateway_parameters);
                $hmac       = hash_hmac("sha512", $sorted_request_json, trim($gatewayAcc->secret_key->value));
                if ($hmac == $recived_hmac) {
                    if ($request_data['payment_status']=='confirmed' || $request_data['payment_status']=='finished') {
                        if ($request_data['actually_paid'] == $request_data['pay_amount']) {
                           
                            if(session('type') == 'deposit'){
                                $payment = Deposit::where('transaction_id', $request_data['order_id'])->first();
                            }else{
                                $payment = Payment::where('transaction_id', $request_data['order_id'])->first();
                            }
                            
                             PaymentController::updateUserData($payment, $payment->charge, $request_data['order_id']);
                        }
                    }
                }
            }
        }
        
        
    }
}
