<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MobileMoneyGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gateways = [
            // 1. Orange Money (Freemopay API)
            [
                'gateway_name' => 'orangemoney',
                'gateway_image' => 'orange_money.png', // À uploader dans public/uploads/gateway/
                'gateway_parameters' => json_encode([
                    'app_key' => 'YOUR_FREEMOPAY_APP_KEY',
                    'secret_key' => 'YOUR_FREEMOPAY_SECRET_KEY',
                    'gateway_currency' => 'FCFA',
                    'payment_type' => 'automatic'
                ]),
                'gateway_type' => 1, // Automatique
                'user_proof_param' => json_encode([]),
                'rate' => 1.00,
                'charge' => 0.00,
                'status' => 0, // Désactivé par défaut (à activer après configuration)
                'is_created' => 0,
            ],

            // 2. MTN Mobile Money (Freemopay API)
            [
                'gateway_name' => 'mtnmoney',
                'gateway_image' => 'mtn_momo.png', // À uploader dans public/uploads/gateway/
                'gateway_parameters' => json_encode([
                    'app_key' => 'YOUR_FREEMOPAY_APP_KEY',
                    'secret_key' => 'YOUR_FREEMOPAY_SECRET_KEY',
                    'gateway_currency' => 'FCFA',
                    'payment_type' => 'automatic'
                ]),
                'gateway_type' => 1, // Automatique
                'user_proof_param' => json_encode([]),
                'rate' => 1.00,
                'charge' => 0.00,
                'status' => 0, // Désactivé par défaut
                'is_created' => 0,
            ],

            // 3. Orange Money Manuel
            [
                'gateway_name' => 'Orange Money Manuel',
                'gateway_image' => 'orange_money_manual.png', // À uploader
                'gateway_parameters' => json_encode([
                    'merchant_phone' => '237XXXXXXXXX', // Numéro du marchand
                    'merchant_name' => 'Immigration de l\'Estuaire',
                    'gateway_currency' => 'FCFA',
                    'instruction' => '<p><strong>Instructions de paiement Orange Money:</strong></p>
                    <ol>
                        <li>Composez <strong>#150#</strong> sur votre téléphone Orange Money</li>
                        <li>Sélectionnez "Transfert d\'argent"</li>
                        <li>Entrez le montant indiqué ci-dessous</li>
                        <li>Envoyez au numéro: <strong>237XXXXXXXXX</strong></li>
                        <li>Notez le numéro de transaction</li>
                        <li>Soumettez la preuve de paiement avec le formulaire</li>
                    </ol>'
                ]),
                'gateway_type' => 0, // Manuel
                'user_proof_param' => json_encode([
                    [
                        'field_name' => 'Transaction Number',
                        'type' => 'text',
                        'validation' => 'required'
                    ],
                    [
                        'field_name' => 'Payment Screenshot',
                        'type' => 'file',
                        'validation' => 'required'
                    ]
                ]),
                'rate' => 1.00,
                'charge' => 0.00,
                'status' => 1, // Activé par défaut
                'is_created' => 1,
            ],

            // 4. MTN Mobile Money Manuel
            [
                'gateway_name' => 'MTN Mobile Money Manuel',
                'gateway_image' => 'mtn_momo_manual.png', // À uploader
                'gateway_parameters' => json_encode([
                    'merchant_phone' => '237XXXXXXXXX', // Numéro du marchand MTN
                    'merchant_name' => 'Immigration de l\'Estuaire',
                    'gateway_currency' => 'FCFA',
                    'instruction' => '<p><strong>Instructions de paiement MTN Mobile Money:</strong></p>
                    <ol>
                        <li>Composez <strong>*126#</strong> sur votre téléphone MTN</li>
                        <li>Sélectionnez "Transfert d\'argent"</li>
                        <li>Entrez le montant indiqué ci-dessous</li>
                        <li>Envoyez au numéro: <strong>237XXXXXXXXX</strong></li>
                        <li>Notez le numéro de transaction</li>
                        <li>Soumettez la preuve de paiement avec le formulaire</li>
                    </ol>'
                ]),
                'gateway_type' => 0, // Manuel
                'user_proof_param' => json_encode([
                    [
                        'field_name' => 'Transaction Number',
                        'type' => 'text',
                        'validation' => 'required'
                    ],
                    [
                        'field_name' => 'Payment Screenshot',
                        'type' => 'file',
                        'validation' => 'required'
                    ]
                ]),
                'rate' => 1.00,
                'charge' => 0.00,
                'status' => 1, // Activé par défaut
                'is_created' => 1,
            ],
        ];

        foreach ($gateways as $gateway) {
            \DB::table('gateways')->insert(array_merge($gateway, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✅ 4 Gateways Mobile Money insérés avec succès!');
    }
}
