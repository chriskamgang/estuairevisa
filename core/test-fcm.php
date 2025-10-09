<?php

/**
 * Script de test pour Firebase Cloud Messaging V1 API
 *
 * Ce script teste l'envoi de notification push FCM
 *
 * Usage: php test-fcm.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Test Firebase Cloud Messaging V1 ===\n\n";

// Récupérer un utilisateur avec FCM token activé
$user = \App\Models\User::where('push_notification_enabled', 1)
    ->whereNotNull('fcm_token')
    ->first();

if (!$user) {
    echo "❌ Aucun utilisateur avec notifications push activées trouvé.\n";
    echo "Veuillez d'abord activer les notifications depuis le dashboard.\n";
    exit(1);
}

echo "✅ Utilisateur trouvé: {$user->fname} {$user->lname} (ID: {$user->id})\n";
echo "📱 FCM Token: " . substr($user->fcm_token, 0, 30) . "...\n\n";

// Test 1: Vérifier les credentials
echo "Test 1: Vérification des credentials Firebase...\n";
$credentialsPath = config('firebase.credentials');
if (!file_exists($credentialsPath)) {
    echo "❌ Fichier de credentials introuvable: {$credentialsPath}\n";
    exit(1);
}
echo "✅ Fichier de credentials trouvé\n\n";

// Test 2: Générer un access token
echo "Test 2: Génération de l'access token OAuth 2.0...\n";
$credentials = json_decode(file_get_contents($credentialsPath), true);
$accessToken = getFirebaseAccessToken($credentials);

if (!$accessToken) {
    echo "❌ Échec de génération de l'access token\n";
    echo "Vérifiez les logs dans storage/logs/laravel.log\n";
    exit(1);
}
echo "✅ Access token généré: " . substr($accessToken, 0, 30) . "...\n\n";

// Test 3: Envoyer une notification de test
echo "Test 3: Envoi d'une notification push de test...\n";
$result = sendFCMNotification(
    $user,
    "🧪 Test de Notification",
    "Ceci est un test d'envoi de notification push via FCM V1 API. Si vous recevez cela, tout fonctionne parfaitement!",
    [
        'type' => 'test',
        'test_id' => uniqid('test_')
    ],
    route('user.dashboard')
);

if ($result['success']) {
    echo "✅ Notification envoyée avec succès!\n";
    echo "📝 Message Name: " . ($result['message_name'] ?? 'N/A') . "\n";
    echo "\n";
    echo "🎉 Vérifiez votre appareil/navigateur pour voir la notification!\n";
} else {
    echo "❌ Échec d'envoi de la notification\n";
    echo "Erreur: " . ($result['error'] ?? 'Unknown error') . "\n";
    echo "\nConsultez les logs pour plus de détails:\n";
    echo "tail -f storage/logs/laravel.log\n";
    exit(1);
}

echo "\n=== Test terminé ===\n";
