<?php

namespace App\Http\Controllers\Gateway\mtnmoney;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController;
use App\Models\Deposit;
use App\Models\Payment;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public static $gateway;

    /**
     * Méthode statique pour traiter le paiement MTN Money via Freemopay
     */
    public static function process($request, $gateway, $totalAmount, $deposit)
    {
        // Valider le numéro de téléphone
        $request->validate([
            'phone_number' => 'required|string|regex:/^237[0-9]{9}$/',
        ], [
            'phone_number.required' => 'Le numéro de téléphone est requis',
            'phone_number.regex' => 'Format invalide. Utilisez: 237XXXXXXXXX (12 chiffres)'
        ]);

        $phoneNumber = $request->phone_number;
        $gatewayParams = $gateway->gateway_parameters;

        try {
            // Appel à l'API Freemopay
            $response = \Illuminate\Support\Facades\Http::withBasicAuth($gatewayParams->app_key, $gatewayParams->secret_key)
                ->timeout(30)
                ->post('https://api-v2.freemopay.com/api/v2/payment', [
                    'payer' => $phoneNumber,
                    'amount' => (int) $totalAmount,
                    'externalId' => $deposit->transaction_id,
                    'callback' => route('freemopay.callback'),
                ]);

            if ($response->successful()) {
                $data = $response->json();

                // Sauvegarder la référence Freemopay
                $deposit->freemopay_reference = $data['reference'] ?? null;
                $deposit->freemopay_status = $data['status'] ?? 'PENDING';
                $deposit->payment_status = 2; // Status Pending en attente de confirmation
                $deposit->save();

                // Rediriger vers la page d'attente
                return redirect()->route('freemopay.waiting', ['trx' => $deposit->transaction_id]);
            } else {
                return redirect()->route('user.dashboard')
                    ->with('error', 'Erreur lors de l\'initialisation du paiement: ' . $response->body());
            }
        } catch (\Exception $e) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Erreur de connexion à Freemopay: ' . $e->getMessage());
        }
    }

    /**
     * Affiche le formulaire de paiement manuel MTN Mobile Money
     */
    public function depositInsert(Request $request)
    {
        $gateway = self::$gateway;
        $gatewayParams = json_decode($gateway->gateway_parameters);

        // Récupération du paiement
        if (session('type') == 'deposit') {
            $payment = Deposit::where('transaction_id', session('track'))->first();
        } else {
            $payment = Payment::where('transaction_id', session('track'))->first();
        }

        if (!$payment) {
            return back()->with('error', 'Transaction non trouvée');
        }

        // Affichage du formulaire de paiement manuel
        return view('frontend.gateway.mtnmoney.manual', compact('payment', 'gateway', 'gatewayParams'));
    }

    /**
     * Traite la soumission de la preuve de paiement
     */
    public function submitProof(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'payment_reference' => 'required|string|max:255',
        ], [
            'proof_image.required' => 'Veuillez télécharger une capture d\'écran de votre paiement',
            'proof_image.image' => 'Le fichier doit être une image',
            'proof_image.max' => 'La taille maximale est de 2 Mo',
            'payment_reference.required' => 'Veuillez entrer le numéro de transaction MTN',
        ]);

        // Recherche du paiement
        $payment = Deposit::where('transaction_id', $request->transaction_id)->first();

        if (!$payment) {
            $payment = Payment::where('transaction_id', $request->transaction_id)->first();
        }

        if (!$payment) {
            return back()->with('error', 'Transaction non trouvée');
        }

        // Upload de l'image de preuve
        if ($request->hasFile('proof_image')) {
            $proofPath = uploadImage($request->file('proof_image'), filePath('payment_proof'));

            $payment->payment_proof = $proofPath;
            $payment->payment_reference = $request->payment_reference;
            $payment->status = 0; // En attente de validation admin
            $payment->save();
        }

        $notify[] = ['success', 'Preuve de paiement soumise avec succès. En attente de validation.'];
        return redirect()->route('user.dashboard')->withNotify($notify);
    }
}
