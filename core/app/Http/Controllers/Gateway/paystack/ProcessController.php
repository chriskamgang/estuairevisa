<?php

namespace App\Http\Controllers\Gateway\paystack;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\Deposit;
use App\Models\Payment;
use Illuminate\Http\Request;

class ProcessController extends Controller{
    public function returnSuccess(Request $request)
    {

       if(isset($request['reference'])){

           if(session('type') == 'deposit'){
             $payment = Deposit::where('transaction_id', $request['reference'])->first();
           }else{

               $payment = Payment::where('transaction_id', $request['reference'])->first();
           }


           PaymentController::updateUserData($payment, $payment->charge, $request['reference']);

           $notify[] = ['success','Successfully Purchased Property'];

           return redirect()->route('user.dashboard')->withNotify($notify);

       }
    }
}