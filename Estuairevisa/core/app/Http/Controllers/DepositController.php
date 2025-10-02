<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Gateway;

class DepositController extends Controller
{
    public function deposit()
    {
        $gateways = Gateway::where('status', 1)->latest()->get();

        $pageTitle = "Payment Methods";

        $type = 'deposit';

        return view("frontend.user.gateway.gateways", compact('gateways', 'pageTitle', 'type'));
    }

    public function depositLog()
    {
        $pageTitle = "Transactions";

        $transactions = Deposit::where('user_id', auth()->id())->latest()->with('user')->whereIn('payment_status',[1,2,3])->paginate();

        return view('frontend.user.deposit_log', compact('pageTitle', 'transactions'));
    }
}
