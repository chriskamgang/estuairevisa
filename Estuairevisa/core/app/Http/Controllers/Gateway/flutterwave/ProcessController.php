<?php

namespace App\Http\Controllers\Gateway\flutterwave;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\Deposit;
use App\Models\Payment;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function returnSuccess(Request $request)
    {
        if ($request['status'] == 'successful') {
            $transaction = session('transaction_id');

            if (session('type') == 'deposit') {
                $payment = Deposit::where('transaction_id', $transaction)->first();
            } else {

                $payment = Payment::where('transaction_id', $transaction)->first();
            }


            PaymentController::updateUserData($payment, $payment->charge, $request['transaction_id']);

            session()->forget('transaction_id');

            $notify[] = ['success', 'Successfully Purchased Plan'];

            return redirect()->route('user.dashboard')->withNotify($notify);
        }
    }
}
