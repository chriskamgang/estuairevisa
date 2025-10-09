<?php

namespace App\Http\Controllers\Gateway\freemopay;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\Deposit;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessController extends Controller
{
    public static $gateway;

    /**
     * Initialise le paiement Freemopay (Orange Money ou MTN Mobile Money)
     */
    public function depositInsert(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|regex:/^237[0-9]{9}$/',
        ], [
            'phone_number.regex' => 'Le numéro doit commencer par 237 et contenir 12 chiffres (ex: 237695509408)'
        ]);

        $phoneNumber = $request->phone_number;

        // Récupération de la gateway (Orange Money ou MTN)
        $gateway = self::$gateway;
        $gatewayParams = json_decode($gateway->gateway_parameters);

        // Création du paiement en base de données
        if (session('type') == 'deposit') {
            $payment = Deposit::where('transaction_id', session('track'))->first();
        } else {
            $payment = Payment::where('transaction_id', session('track'))->first();
        }

        if (!$payment) {
            return back()->with('error', 'Transaction non trouvée');
        }

        // Appel API Freemopay pour initialiser le paiement
        try {
            $response = Http::withBasicAuth($gatewayParams->app_key, $gatewayParams->secret_key)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->timeout(30)
                ->post('https://api-v2.freemopay.com/api/v2/payment', [
                    'payer' => $phoneNumber,
                    'amount' => (int) $payment->amount,
                    'externalId' => $payment->transaction_id,
                    'callback' => route('freemopay.callback'),
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Enregistrement de la référence Freemopay
                $payment->freemopay_reference = $data['reference'] ?? null;
                $payment->freemopay_status = $data['status'] ?? 'CREATED';
                $payment->save();

                // Instructions pour l'utilisateur
                $instructions = $gateway->gateway_name === 'Orange Money'
                    ? "Composez #150# depuis votre téléphone Orange Money pour valider le paiement."
                    : "Composez *126# depuis votre téléphone MTN Mobile Money pour valider le paiement.";

                return view('frontend.gateway.freemopay.waiting', compact('payment', 'gateway', 'instructions'));
            } else {
                $errorMessage = $response->json('message') ?? 'Erreur lors de l\'initialisation du paiement';
                Log::error('Freemopay Init Error', [
                    'response' => $response->body(),
                    'status' => $response->status()
                ]);

                return back()->with('error', $errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('Freemopay Exception', ['error' => $e->getMessage()]);
            return back()->with('error', 'Erreur de connexion à Freemopay. Veuillez réessayer.');
        }
    }

    /**
     * Webhook - Reçoit les notifications de Freemopay
     */
    public function callback(Request $request)
    {
        Log::info('Freemopay Webhook Received', $request->all());

        $status = $request->input('status');
        $reference = $request->input('reference');
        $externalId = $request->input('externalId');
        $amount = $request->input('amount');
        $message = $request->input('message');

        // Recherche du paiement par externalId (transaction_id)
        $payment = Deposit::where('transaction_id', $externalId)->first();

        if (!$payment) {
            $payment = Payment::where('transaction_id', $externalId)->first();
        }

        if (!$payment) {
            Log::error('Freemopay Webhook: Payment not found', ['externalId' => $externalId]);
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Vérification de la référence
        if ($payment->freemopay_reference !== $reference) {
            Log::error('Freemopay Webhook: Reference mismatch', [
                'expected' => $payment->freemopay_reference,
                'received' => $reference
            ]);
            return response()->json(['error' => 'Invalid reference'], 400);
        }

        // Vérification du montant
        if ((int) $payment->amount !== (int) $amount) {
            Log::error('Freemopay Webhook: Amount mismatch', [
                'expected' => $payment->amount,
                'received' => $amount
            ]);
            return response()->json(['error' => 'Invalid amount'], 400);
        }

        // Traitement selon le statut
        if ($status === 'SUCCESS') {
            // Mise à jour du paiement
            $payment->freemopay_status = 'SUCCESS';
            $payment->save();

            // Mise à jour du compte utilisateur
            PaymentController::updateUserData($payment, $payment->charge, $reference);

            Log::info('Freemopay Payment Success', ['transaction_id' => $externalId]);

            return response()->json(['status' => 'success'], 200);

        } elseif ($status === 'FAILED') {
            $payment->freemopay_status = 'FAILED';
            $payment->freemopay_message = $message;
            $payment->save();

            Log::warning('Freemopay Payment Failed', [
                'transaction_id' => $externalId,
                'message' => $message
            ]);

            return response()->json(['status' => 'failed', 'message' => $message], 200);
        }

        return response()->json(['status' => 'unknown'], 400);
    }

    /**
     * Affiche la page d'attente du paiement
     */
    public function waiting(Request $request)
    {
        $transactionId = $request->query('trx');

        // Recherche du paiement
        $payment = Deposit::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            $payment = Payment::where('transaction_id', $transactionId)->first();
        }

        if (!$payment) {
            return redirect()->route('user.dashboard')->with('error', 'Transaction non trouvée');
        }

        // Récupération de la gateway
        $gateway = \App\Models\Gateway::find($payment->gateway_id);

        if (!$gateway) {
            return redirect()->route('user.dashboard')->with('error', 'Gateway non trouvée');
        }

        // Instructions selon le type de paiement
        $instructions = $gateway->gateway_name === 'orangemoney'
            ? "Composez #150# depuis votre téléphone Orange Money pour valider le paiement."
            : "Composez *126# depuis votre téléphone MTN Mobile Money pour valider le paiement.";

        return view('frontend.gateway.freemopay.waiting', compact('payment', 'gateway', 'instructions'));
    }

    /**
     * Vérifie le statut d'un paiement
     */
    public function checkStatus(Request $request)
    {
        $transactionId = $request->input('transaction_id');

        $payment = Deposit::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            $payment = Payment::where('transaction_id', $transactionId)->first();
        }

        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }

        // Si déjà SUCCESS, retourner immédiatement
        if ($payment->freemopay_status === 'SUCCESS') {
            return response()->json([
                'status' => 'SUCCESS',
                'redirect' => route('user.dashboard')
            ]);
        }

        // Récupération de la gateway
        $gateway = \App\Models\Gateway::find($payment->gateway_id);

        if (!$gateway) {
            return response()->json(['error' => 'Gateway not found'], 404);
        }

        $gatewayParams = $gateway->gateway_parameters; // Déjà un objet, pas besoin de json_decode

        try {
            $response = Http::withBasicAuth($gatewayParams->app_key, $gatewayParams->secret_key)
                ->timeout(10)
                ->get("https://api-v2.freemopay.com/api/v2/payment/{$payment->freemopay_reference}");

            if ($response->successful()) {
                $data = $response->json();
                $status = $data['status'] ?? 'PENDING';

                Log::info('Freemopay Status Check', [
                    'transaction_id' => $transactionId,
                    'status' => $status,
                    'data' => $data
                ]);

                // Mise à jour du statut
                $payment->freemopay_status = $status;

                if ($status === 'SUCCESS') {
                    $payment->payment_status = 1; // Complete
                } elseif ($status === 'FAILED') {
                    $payment->payment_status = 3; // Rejected
                } else {
                    $payment->payment_status = 2; // Pending
                }

                $payment->save();

                if ($status === 'SUCCESS') {
                    // Créditer le compte utilisateur
                    PaymentController::updateUserData($payment, $payment->charge, $payment->freemopay_reference);

                    return response()->json([
                        'status' => 'SUCCESS',
                        'redirect' => route('user.dashboard')
                    ]);
                }

                if ($status === 'FAILED') {
                    return response()->json([
                        'status' => 'FAILED',
                        'message' => $data['message'] ?? 'Le paiement a échoué'
                    ]);
                }

                return response()->json(['status' => $status]);
            }
        } catch (\Exception $e) {
            Log::error('Freemopay Check Status Error', ['error' => $e->getMessage()]);
        }

        return response()->json(['status' => $payment->freemopay_status ?? 'PENDING']);
    }
}
