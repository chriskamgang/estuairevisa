<?php

namespace App\Http\Controllers\Gateway\paghiper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\Deposit;
use App\Models\Payment;
use Illuminate\Http\Request;

class ProcessController extends Controller
{

    public static function process($request, $gateway, $amount, $deposit)
{
    try {
        \PagHipperSDK\Auth::init(
            $gateway->gateway_parameters->paghiper_key,
            $gateway->gateway_parameters->token
        );

        $pagHiper = new \PagHipperSDK\PagHiper();

        $items = [];
        $items[] = (new \PagHipperSDK\Entities\Item())
            ->setItemId($deposit->id)
            ->setDescription('Plan Purchase')
            ->setQuantity(1)
            ->setPriceCents($amount);

        $payer = (new \PagHipperSDK\Entities\Payer())
            ->setPayerEmail(auth()->user()->email)
            ->setPayerName(auth()->user()->username)
            ->setPayerCpfCnpj($request->cpf);

        $transaction = (new \PagHipperSDK\Entities\Transaction())
            ->setOrderId($deposit->transaction_id)
            ->setNotificationUrl(route('user.paghiper.success'))
            ->setShippingMethods('PAC')
            ->setFixedDescription(true)
            ->setDaysDueDate('3')
            ->setPayer($payer)
            ->setItems($items);

        $transaction = $pagHiper->createTransaction($transaction);

        return $transaction->getBankSlip()->getUrlSlip();

    } catch (\PagHipperSDK\Exception\ValidationException $e) {
        // Specific PagHiper validation exception
        return [
            'status' => false,
            'message' => 'Validation error: ' . $e->getMessage()
        ];

    } catch (\PagHipperSDK\Exception\ErrorException $e) {
        // PagHiper general errors
        return [
            'status' => false,
            'message' => 'API error: ' . $e->getMessage()
        ];

    } catch (\Exception $e) {
        // Catch any other unexpected errors
        return [
            'status' => false,
            'message' => 'Unexpected error: ' . $e->getMessage()
        ];
    }
}


    public function returnSuccess(Request $request)
    {

        $transaction = \PagHipperSDK\Response\GetTransactionPix::populate($request->all());

        if(session('type') == 'deposit'){
            $deposit = Deposit::where('transaction_id', session('trx'))->first();
        }else{
            $deposit = Payment::where('transaction_id', session('trx'))->first();
        }

        if (isset($request->transaction_id)) {

            PaymentController::updateUserData($deposit, 0, $transaction->getOrderId());

            $notify[] = ['success', 'property Subscribed Successfully'];

            return redirect()->route('user.dashboard')->withNotify($notify);
        }
    }
}
