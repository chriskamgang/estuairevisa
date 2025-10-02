<?php

namespace App\Http\Controllers\Gateway\paytm;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;

class ProcessController extends Controller
{
    public static function process($request, $paytm, $totalAmount, $deposit)
    {

        $general = GeneralSetting::first();

       
  
        $paytmParams = array();

        $paytmParams["body"] = array(
            "requestType"   => "Payment",
            "mid"           => $paytm->gateway_parameters->merchant_id,
            "websiteName"   => $general->sitename,
            "orderId"       => $deposit->transaction_id,
            "callbackUrl"   => "https://<callback URL to be used by merchant>",
            "txnAmount"     => array(
                "value"     => $totalAmount,
                "currency"  => $paytm->gateway_parameters->gateway_currency,
            ),
            "userInfo"      => array(
                "custId"    => auth()->user()->username,
            ),
        );

        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $paytm->gateway_parameters->merchant_id);

        $paytmParams["head"] = array(
            "signature"    => $checksum
        );

        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        /* for Staging */
        $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$paytm->gateway_parameters->merchant_id."&orderId=".$deposit->transaction_id."";

        

        /* for Production */
        // $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=YOUR_MID_HERE&orderId=ORDERID_98765";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        
        return $response;
    }

    public function returnSuccess()
    {
        $transaction = PaytmWallet::with('receive');


        $response = $transaction->response();
        $order_id = $transaction->getOrderId();


        if ($transaction->isSuccessful()) {
        } else if ($transaction->isFailed()) {
        }
    }
}
