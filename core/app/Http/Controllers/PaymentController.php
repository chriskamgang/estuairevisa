<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Checkout;
use App\Models\CheckoutLog;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

    public function gateways(Request $request)
    {

        $checkout_data = session('checkout_data');

        if (!isset($checkout_data['items'])) {
            return redirect()->back()->with('error', 'Request failed');
        }

        $gateways = Gateway::where('status', 1)->latest()->get();
        $checkout_data = collect($checkout_data);
        $total_amount = collect($checkout_data['items'] ?? [])->sum(fn($item) => $item['plan']->price);

        $pageTitle = "Payment Methods";

        return view("frontend.user.gateway.gateways", compact('gateways', 'pageTitle', 'checkout_data', 'total_amount'));
    }

    public function paynow(Request $request)
    {
        $request->validate([
            'amount' => 'required|gte:0',
        ]);


        $gateway = Gateway::where('status', 1)->findOrFail($request->id);
        $trx = strtoupper(Str::random());

        $checkout_data = session('checkout_data');

        if (!session('checkout_data')) {
            session()->put('type', 'deposit');
        }

        $checkout_data = collect($checkout_data);

        if (session('type') != 'deposit') {
            $total_amount = collect($checkout_data['items'] ?? [])->sum(fn($item) => $item['plan']->price);
        } else {
            $total_amount = $request->amount;
        }


        $final_amount = ($total_amount * ($gateway->rate == 0 ? 1 : $gateway->rate)) + $gateway->charge;

        if ($final_amount <= 0) {

            session()->forget('deposit');
            session()->forget('checkout_data');
            return back()->with('error', 'Invalid amount');
        }

        if (isset($request->type) && $request->type == 'deposit') {

            $deposit = Deposit::create([
                'gateway_id' => $gateway->id,
                'user_id' => auth()->id(),
                'transaction_id' => $trx,
                'amount' => $request->amount,
                'rate' => $gateway->rate,
                'charge' => $gateway->charge,
                'final_amount' => $final_amount,
                'payment_status' => 0,
                'payment_type' => 1,
            ]);

            session()->put('trx', $trx);
            session()->put('type', 'deposit');


            return redirect()->route('user.gateway.details', $gateway->id);
        } else {

            $trx = strtoupper(str::random());

            Payment::create([
                'checkout_id' => 0,
                'gateway_id' => $gateway->id,
                'user_id' => auth()->id(),
                'transaction_id' => $trx,
                'amount' => $request->amount,
                'rate' => $gateway->rate,
                'charge' => $gateway->charge,
                'final_amount' => $final_amount,
                'payment_status' => 0,
                'checkout_data' => json_encode($checkout_data) // Sauvegarder les données du panier
            ]);

            session()->put('trx', $trx);
            session()->put('type', 'payment');
        }

        return redirect()->route('user.gateway.details', $gateway->id);
    }

    public function gatewaysDetails($id)
    {

        $gateway = Gateway::where('status', 1)->findOrFail($id);

        if (session('type') == 'deposit') {
            $deposit = Deposit::where('transaction_id', session('trx'))->firstOrFail();
        } else {

            $deposit = Payment::where('transaction_id', session('trx'))->firstOrFail();
        }

        $pageTitle = $gateway->gateway_name . ' Payment Details';

        if ($gateway->gateway_name == 'vouguepay') {

            $vouguePayParams["marchant_id"] = $gateway->gateway_parameters->vouguepay_merchant_id;
            $vouguePayParams["redirect_url"] = route("user.vouguepay.redirect");
            $vouguePayParams["currency"] = $gateway->gateway_parameters->gateway_currency;
            $vouguePayParams["merchant_ref"] = $deposit->transaction_id;
            $vouguePayParams["memo"] = "Payment";
            $vouguePayParams["store_id"] = $deposit->user_id;
            $vouguePayParams["loadText"] = $deposit->transaction_id;
            $vouguePayParams["amount"] = $deposit->final_amount;
            $vouguePayParams = json_decode(json_encode($vouguePayParams));

            return view("frontend.user.gateway.{$gateway->gateway_name}", compact('gateway', 'pageTitle', 'deposit', 'vouguePayParams'));
        }

        if ($gateway->is_created) {
            return view("frontend.user.gateway.gateway_manual", compact('gateway', 'pageTitle', 'deposit'));
        }


        return view("frontend.user.gateway.{$gateway->gateway_name}", compact('gateway', 'pageTitle', 'deposit'));
    }

    public function gatewayRedirect(Request $request, $id)
    {

        $gateway = Gateway::where('status', 1)->findOrFail($id);

        if (session('type') == 'deposit') {
            $deposit = Deposit::where('transaction_id', session('trx'))->firstOrFail();
        } else {
            session()->put('type', 'payment');
            $deposit = Payment::where('transaction_id', session('trx'))->firstOrFail();
        }


        $name = $gateway->gateway_name;

        if ($gateway->is_created) {
            $name = "manual";
        }
        $new = __NAMESPACE__ . '\\Gateway\\' . $name . '\ProcessController';

        $data = $new::process($request, $gateway, $deposit->final_amount, $deposit);

        // Orange Money et MTN Mobile Money (Freemopay)
        if ($gateway->gateway_name == 'orangemoney' || $gateway->gateway_name == 'mtnmoney') {
            return $data; // Retourne directement la RedirectResponse
        }

        if ($gateway->gateway_name == 'nowpayments') {

            return redirect()->to($data->invoice_url);
        }

        if ($gateway->gateway_name == 'mollie') {

            return redirect()->to($data['redirect_url']);
        }

        if ($gateway->gateway_name == 'paghiper') {

            if (isset($data['status']) && $data['status'] == false) {
                return redirect()->route('user.dashboard')->with('error', 'Something Went Wrong');
            }

            return redirect()->to($data);
        }

        if ($gateway->gateway_name == 'coinpayments') {

            if (isset($data['result']['checkout_url'])) {

                return redirect()->to($data['result']['checkout_url']);
            }
        }

        if ($gateway->gateway_name == 'paypal') {

            $data = json_decode($data);

            return redirect()->to($data->links[1]->href);
        }

        $notify[] = ['success', 'Your Payment is Successfully Recieved'];
        return redirect()->route('user.dashboard')->withNotify($notify);
    }

    public static function updateUserData($deposit, $fee_amount, $transaction)
    {
        $general = GeneralSetting::first();
        $user = auth()->user() ?? User::find($deposit->user_id); // Fallback sur le user du paiement
        $admin = Admin::first();

        $type = session('type');
        $trx = session('trx');
        $notiType = $type === 'deposit' ? 'deposit' : 'payment';
        $notiMessage = $type === 'deposit' ? 'New deposit from ' : 'New payment from ';
        $notiUrl = $type === 'deposit' ? route('admin.deposit.all') : route('admin.visa.list');

        if ($type === 'deposit') {

            // Update user balance
            $user->balance += $deposit->amount;
            $user->save();

            // Send deposit success email
            sendMail('PAYMENT_SUCCESSFULL', [
                'property' => $deposit->property->property_name ?? 'Deposit',
                'trx' => $transaction,
                'amount' => $deposit->amount,
                'currency' => $general->site_currency,
            ], $deposit->user);

            // Notify admin
            if ($admin) {
                $admin->notify(new AdminNotification([
                    'type' => $notiType,
                    'url' => $notiUrl,
                    'message' => $notiMessage . $user->username,
                ]));
            }
        } else {

            DB::beginTransaction();

            // Récupérer checkout_data depuis la session ou depuis le paiement
            $checkout_data = session('checkout_data');

            // Si pas de session, récupérer depuis la BDD
            if (!$checkout_data && $deposit->checkout_data) {
                $checkout_data = json_decode($deposit->checkout_data, true);
            }

            if (!$checkout_data || !isset($checkout_data['items'])) {
                Log::error('Checkout data not found for payment', [
                    'transaction_id' => $deposit->transaction_id,
                    'user_id' => $user->id
                ]);
                return; // Ne rien faire si pas de données
            }

            $checkout = new Checkout();
            $checkout->user_id = $deposit->user_id; // Utiliser user_id du paiement au lieu de auth()
            $checkout->trx = strtoupper(Str::random());
            $checkout->total_visa = count($checkout_data['items']);
            $checkout->payment_status = 1;
            $checkout->save();

            $deposit->checkout_id = $checkout->id;

            $totalAmount = 0;

            foreach ($checkout_data['items'] as $order => $item) {

                // Gérer le format du plan (objet ou tableau)
                $plan = is_array($item['plan']) ? (object) $item['plan'] : $item['plan'];

                $log = new CheckoutLog();
                $log->order_number = $order;
                $log->plan_id = $plan->id;
                $log->checkout_id = $checkout->id;
                $log->price = $plan->price;
                $log->files = $item['session_info']['files'];
                $log->personal_info = $item['session_info']['personal_info'];
                $log->status = 'pending';
                $log->save();

                $totalAmount += $log->price;

                if ($admin) {
                    $admin->notify(new AdminNotification([
                        'type' => "others",
                        'url' => route('admin.visa.list', 'pending'),
                        'message' => "New visa applay from " . $user->username,
                    ]));
                }


                sendMail('VISA_APPLAY', [
                    'plan' => $log->plan->title,
                    'price' => $log->price,
                    'order_number' => $log->order_number,
                    'status' => $log->status,
                    'note' => 'Your visa application is pending. Please wait while it is being reviewed.',
                    'link' => route('visa.track', ['order_number' => $log->order_number]),
                ], $user);
            }

            Transaction::create([
                'trx' => $deposit->transaction_id,
                'gateway_id' => $deposit->gateway_id,
                'amount' => $deposit->final_amount,
                'currency' => $general->site_currency,
                'details' => 'Payment Successfull',
                'charge' => $fee_amount,
                'type' => '-',
                'gateway_transaction' => $transaction,
                'user_id' => $user->id,
                'payment_status' => 1,
            ]);

            // Envoyer notification WhatsApp
            // Récupérer le numéro de téléphone (user ou première demande de visa)
            $phoneNumber = $user->phone_number;
            if (!$phoneNumber && isset($checkout_data['items'])) {
                $firstItem = reset($checkout_data['items']);
                if (isset($firstItem['session_info']['personal_info']['phone_number'])) {
                    $phoneNumber = $firstItem['session_info']['personal_info']['phone_number'];
                }
            }

            if ($phoneNumber) {
                // Récupérer le nom (user ou première demande)
                $firstName = $user->first_name;
                $lastName = $user->last_name;
                if ((!$firstName || !$lastName) && isset($firstItem['session_info']['personal_info'])) {
                    $firstName = $firstItem['session_info']['personal_info']['first_name'] ?? $firstName;
                    $lastName = $firstItem['session_info']['personal_info']['last_name'] ?? $lastName;
                }

                $whatsappMessage = "🎉 *Paiement Confirmé - Immigration de l'Estuaire* 🎉\n\n";
                $whatsappMessage .= "Bonjour *{$firstName} {$lastName}*,\n\n";
                $whatsappMessage .= "Votre paiement de *{$deposit->final_amount} {$general->site_currency}* a été confirmé avec succès!\n\n";
                $whatsappMessage .= "📋 *Détails de vos demandes de visa:*\n";
                $whatsappMessage .= "━━━━━━━━━━━━━━━━━━━━\n";

                $visaCount = 1;
                foreach ($checkout_data['items'] as $order => $item) {
                    $plan = is_array($item['plan']) ? (object) $item['plan'] : $item['plan'];
                    $whatsappMessage .= "\n*{$visaCount}. {$plan->title}*\n";
                    $whatsappMessage .= "   • Numéro de commande: `{$order}`\n";
                    $whatsappMessage .= "   • Prix: {$plan->price} {$general->site_currency}\n";
                    $whatsappMessage .= "   • Statut: ⏳ En attente de traitement\n";
                    $whatsappMessage .= "   • Suivi: " . route('visa.track', ['order_number' => $order]) . "\n";
                    $visaCount++;
                }

                $whatsappMessage .= "\n━━━━━━━━━━━━━━━━━━━━\n";
                $whatsappMessage .= "💳 *Résumé du paiement:*\n";
                $whatsappMessage .= "   • Transaction: {$deposit->transaction_id}\n";
                $whatsappMessage .= "   • Montant total: {$deposit->final_amount} {$general->site_currency}\n";
                $whatsappMessage .= "   • Nombre de visas: {$checkout->total_visa}\n";
                $whatsappMessage .= "\n📧 Un email de confirmation vous a également été envoyé.\n\n";
                $whatsappMessage .= "Merci de votre confiance! 🙏";

                // Formatter le numéro de téléphone au format international si nécessaire
                $phone = $phoneNumber;
                if (!str_starts_with($phone, '+')) {
                    $phone = '+237' . ltrim($phone, '0'); // Ajouter code pays Cameroun par défaut
                }

                sendWhatsApp($phone, $whatsappMessage);
            }

            DB::commit();

            referMoney($checkout->user, $totalAmount);

            session()->forget('checkout_data');

            // Supprimer aussi checkout_data de la base de données
            $deposit->checkout_data = null;
        }

        $deposit->payment_status = 1;
        $deposit->save();

        session()->forget('type');
    }
}
