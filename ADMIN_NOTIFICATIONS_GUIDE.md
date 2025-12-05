# Guide Administrateur - Gestion des Notifications

## Table des matières
1. [Introduction](#introduction)
2. [Créer des notifications](#créer-des-notifications)
3. [Notifications automatiques](#notifications-automatiques)
4. [Notifications en masse](#notifications-en-masse)
5. [Intégration avec les événements existants](#intégration-avec-les-événements-existants)
6. [Exemples pratiques](#exemples-pratiques)

---

## Introduction

Ce guide explique comment les administrateurs peuvent gérer et envoyer des notifications aux utilisateurs dans l'application Estuairevisa.

---

## Créer des notifications

### Notification simple

```php
use App\Models\UserNotification;
use App\Models\User;

// Pour un utilisateur spécifique
$user = User::find($userId);

UserNotification::create([
    'user_id' => $user->id,
    'type' => 'message', // nouveaute, rappel, message, system
    'title' => 'Bienvenue sur Estuairevisa',
    'message' => 'Merci de vous être inscrit. Nous sommes là pour vous aider!',
    'url' => route('home'), // optionnel
    'data' => json_encode(['action' => 'welcome']) // optionnel
]);
```

### Notification avec push FCM et WhatsApp

```php
use App\Models\UserNotification;

function sendCompleteNotification($user, $title, $message, $url = null)
{
    // 1. Créer la notification dans la base de données
    $notification = UserNotification::create([
        'user_id' => $user->id,
        'type' => 'system',
        'title' => $title,
        'message' => $message,
        'url' => $url
    ]);

    // 2. Envoyer la notification push via FCM
    if ($user->fcm_token && $user->push_notification_enabled) {
        sendFCMNotification(
            $user,
            $title,
            $message,
            ['notification_id' => $notification->id],
            $url
        );
    }

    // 3. Attendre 5 secondes avant d'envoyer WhatsApp
    sleep(5);

    // 4. Envoyer via WhatsApp
    if ($user->phone) {
        $whatsappMessage = "{$title}\n\n{$message}";
        if ($url) {
            $whatsappMessage .= "\n\nVoir plus: {$url}";
        }
        sendWhatsApp($user->phone, $whatsappMessage);
    }

    return $notification;
}

// Utilisation
$user = User::find($userId);
sendCompleteNotification(
    $user,
    'Votre visa est prêt',
    'Votre demande de visa a été approuvée. Vous pouvez le télécharger maintenant.',
    route('user.visa.details', $visaId)
);
```

---

## Notifications automatiques

### 1. Notification lors de l'inscription

```php
// Dans RegisterController.php

public function register(Request $request)
{
    // ... votre logique d'inscription existante ...

    $user = User::create([...]);

    // Créer notification de bienvenue
    UserNotification::create([
        'user_id' => $user->id,
        'type' => 'nouveaute',
        'title' => 'Bienvenue ' . $user->fname . '!',
        'message' => 'Merci de vous être inscrit sur Estuairevisa. Découvrez nos services de demande de visa.',
        'url' => route('user.dashboard')
    ]);

    // Envoyer FCM si le token est déjà enregistré
    if ($user->fcm_token) {
        sendFCMNotification(
            $user,
            'Bienvenue!',
            'Votre compte a été créé avec succès',
            [],
            route('user.dashboard')
        );
    }

    return redirect()->route('user.dashboard');
}
```

### 2. Notification lors d'un changement de statut de visa

```php
// Dans Admin\VisaApplyController.php

public function changeStatus(Request $request, $fileId)
{
    $checkoutLog = CheckoutLog::findOrFail($fileId);
    $oldStatus = $checkoutLog->status;
    $newStatus = $request->status;

    // Mettre à jour le statut
    $checkoutLog->status = $newStatus;
    $checkoutLog->save();

    // Récupérer l'utilisateur
    $user = $checkoutLog->checkout->user;

    // Messages personnalisés par statut
    $messages = [
        'proccessing' => [
            'title' => 'Demande en cours de traitement',
            'message' => 'Votre demande de visa est maintenant en cours de traitement.'
        ],
        'complete' => [
            'title' => 'Visa approuvé!',
            'message' => 'Félicitations! Votre visa a été approuvé. Vous pouvez le télécharger maintenant.'
        ],
        'shipped' => [
            'title' => 'Visa expédié',
            'message' => 'Votre visa a été expédié et sera bientôt livré.'
        ],
        'cancelled' => [
            'title' => 'Demande annulée',
            'message' => 'Votre demande de visa a été annulée. Contactez le support pour plus d\'informations.'
        ]
    ];

    if (isset($messages[$newStatus])) {
        $notifData = $messages[$newStatus];

        // Créer la notification
        UserNotification::create([
            'user_id' => $user->id,
            'type' => $newStatus === 'complete' ? 'nouveaute' : 'system',
            'title' => $notifData['title'],
            'message' => $notifData['message'],
            'url' => route('user.visa.details', $checkoutLog->id),
            'data' => json_encode([
                'visa_id' => $checkoutLog->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ])
        ]);

        // Envoyer notification complète
        sendCompleteNotification(
            $user,
            $notifData['title'],
            $notifData['message'],
            route('user.visa.details', $checkoutLog->id)
        );
    }

    return back()->with('success', 'Statut mis à jour et notification envoyée');
}
```

### 3. Notification de rappel de paiement

```php
// Dans une commande Artisan (php artisan make:command SendPaymentReminders)

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Checkout;
use App\Models\UserNotification;
use Carbon\Carbon;

class SendPaymentReminders extends Command
{
    protected $signature = 'notifications:payment-reminders';
    protected $description = 'Envoyer des rappels de paiement aux utilisateurs';

    public function handle()
    {
        // Récupérer les paiements en attente depuis plus de 3 jours
        $pendingCheckouts = Checkout::where('payment_status', 0)
            ->where('created_at', '<=', Carbon::now()->subDays(3))
            ->where('created_at', '>=', Carbon::now()->subDays(4))
            ->with('user')
            ->get();

        foreach ($pendingCheckouts as $checkout) {
            $user = $checkout->user;

            // Vérifier si un rappel n'a pas déjà été envoyé aujourd'hui
            $alreadySent = UserNotification::where('user_id', $user->id)
                ->where('type', 'rappel')
                ->whereDate('created_at', Carbon::today())
                ->exists();

            if (!$alreadySent) {
                // Créer la notification
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'rappel',
                    'title' => 'Paiement en attente',
                    'message' => 'Vous avez un paiement en attente de ' .
                                number_format($checkout->total_amount, 2) .
                                ' FCFA. Complétez votre paiement pour finaliser votre demande.',
                    'url' => route('user.gateways'),
                    'data' => json_encode([
                        'checkout_id' => $checkout->id,
                        'amount' => $checkout->total_amount
                    ])
                ]);

                // Envoyer FCM et WhatsApp
                if ($user->fcm_token) {
                    sendFCMNotification(
                        $user,
                        'Paiement en attente',
                        'Complétez votre paiement pour finaliser votre demande',
                        ['checkout_id' => $checkout->id],
                        route('user.gateways')
                    );
                }

                sleep(5);

                if ($user->phone) {
                    sendWhatsApp(
                        $user->phone,
                        "Bonjour {$user->fname},\n\nVous avez un paiement en attente de " .
                        number_format($checkout->total_amount, 2) .
                        " FCFA. Veuillez compléter votre paiement pour finaliser votre demande de visa.\n\n" .
                        "Merci,\nL'équipe Estuairevisa"
                    );
                }

                $this->info("Rappel envoyé à {$user->email}");
            }
        }

        $this->info("Total: {$pendingCheckouts->count()} rappels envoyés");
        return 0;
    }
}
```

Ensuite, planifier cette commande dans `app/Console/Kernel.php` :

```php
protected function schedule(Schedule $schedule)
{
    // Envoyer des rappels de paiement tous les jours à 10h
    $schedule->command('notifications:payment-reminders')
             ->dailyAt('10:00');
}
```

---

## Notifications en masse

### Envoyer une notification à tous les utilisateurs

```php
// Dans un contrôleur admin

use App\Models\User;
use App\Models\UserNotification;

public function sendBulkNotification(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:nouveaute,rappel,message,system',
        'url' => 'nullable|url'
    ]);

    $users = User::where('status', 1)->get(); // Utilisateurs actifs seulement

    foreach ($users as $user) {
        // Créer la notification
        UserNotification::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'url' => $request->url
        ]);

        // Envoyer FCM
        if ($user->fcm_token && $user->push_notification_enabled) {
            sendFCMNotification(
                $user,
                $request->title,
                $request->message,
                ['bulk' => true],
                $request->url
            );
        }
    }

    return back()->with('success', count($users) . ' notifications envoyées');
}
```

### Envoyer à un groupe spécifique

```php
public function sendToVisaApplicants(Request $request)
{
    // Utilisateurs ayant des demandes actives
    $users = User::whereHas('checkouts', function($q) {
        $q->where('payment_status', 0);
    })->get();

    foreach ($users as $user) {
        UserNotification::create([
            'user_id' => $user->id,
            'type' => 'rappel',
            'title' => 'Important: Complétez votre demande',
            'message' => 'Vous avez une demande de visa en attente. Complétez votre paiement pour continuer.',
            'url' => route('user.gateways')
        ]);

        // ... envoyer FCM et WhatsApp
    }

    return back()->with('success', 'Notifications envoyées aux demandeurs');
}
```

---

## Intégration avec les événements existants

### Événement: Nouveau dépôt effectué

```php
// Dans ManageGatewayController.php - méthode depositAccept

public function depositAccept($trx)
{
    $deposit = Deposit::where('trx', $trx)->firstOrFail();
    $user = $deposit->user;

    // ... logique d'acceptation du dépôt ...

    // Créer notification
    UserNotification::create([
        'user_id' => $user->id,
        'type' => 'system',
        'title' => 'Dépôt confirmé',
        'message' => 'Votre dépôt de ' . number_format($deposit->amount, 2) .
                    ' FCFA a été confirmé et ajouté à votre compte.',
        'url' => route('user.deposit.log')
    ]);

    // Envoyer notification complète
    sendCompleteNotification(
        $user,
        'Dépôt confirmé',
        'Votre dépôt a été confirmé avec succès',
        route('user.deposit.log')
    );

    return back()->with('success', 'Dépôt accepté et notification envoyée');
}
```

### Événement: Nouveau ticket de support

```php
// Dans TicketController.php - méthode reply

public function reply(Request $request)
{
    // ... logique de réponse au ticket ...

    $ticket = Ticket::find($request->ticket_id);
    $user = $ticket->user;

    // Créer notification
    UserNotification::create([
        'user_id' => $user->id,
        'type' => 'message',
        'title' => 'Nouvelle réponse à votre ticket',
        'message' => 'Notre équipe a répondu à votre ticket #' . $ticket->id,
        'url' => route('user.ticket.show', $ticket->id),
        'data' => json_encode(['ticket_id' => $ticket->id])
    ]);

    // Envoyer notification
    if ($user->fcm_token) {
        sendFCMNotification(
            $user,
            'Nouvelle réponse',
            'Notre équipe a répondu à votre ticket',
            ['ticket_id' => $ticket->id],
            route('user.ticket.show', $ticket->id)
        );
    }

    return back()->with('success', 'Réponse envoyée');
}
```

---

## Exemples pratiques

### 1. Interface admin pour envoyer des notifications

Créer une vue admin pour envoyer des notifications:

```blade
{{-- resources/views/backend/notifications/create.blade.php --}}

@extends('backend.layout.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Envoyer une notification</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.notifications.send') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Destinataire(s)</label>
                <select name="recipient_type" class="form-control" onchange="toggleRecipientOptions(this)">
                    <option value="all">Tous les utilisateurs</option>
                    <option value="active">Utilisateurs actifs</option>
                    <option value="pending_payment">Avec paiements en attente</option>
                    <option value="specific">Utilisateur spécifique</option>
                </select>
            </div>

            <div class="form-group" id="specific-user" style="display: none;">
                <label>Email de l'utilisateur</label>
                <input type="email" name="user_email" class="form-control">
            </div>

            <div class="form-group">
                <label>Type de notification</label>
                <select name="type" class="form-control" required>
                    <option value="nouveaute">Nouveauté</option>
                    <option value="rappel">Rappel</option>
                    <option value="message">Message</option>
                    <option value="system">Système</option>
                </select>
            </div>

            <div class="form-group">
                <label>Titre</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Message</label>
                <textarea name="message" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label>URL (optionnel)</label>
                <input type="url" name="url" class="form-control"
                       placeholder="https://example.com">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="send_fcm" id="send_fcm" checked>
                <label class="form-check-label" for="send_fcm">
                    Envoyer notification push (FCM)
                </label>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="send_whatsapp" id="send_whatsapp">
                <label class="form-check-label" for="send_whatsapp">
                    Envoyer via WhatsApp
                </label>
            </div>

            <button type="submit" class="btn btn-primary">Envoyer la notification</button>
        </form>
    </div>
</div>

<script>
function toggleRecipientOptions(select) {
    const specificUser = document.getElementById('specific-user');
    if (select.value === 'specific') {
        specificUser.style.display = 'block';
    } else {
        specificUser.style.display = 'none';
    }
}
</script>
@endsection
```

### 2. Contrôleur admin pour gérer les notifications

```php
// app/Http/Controllers/Admin/NotificationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserNotification;

class NotificationController extends Controller
{
    public function create()
    {
        return view('backend.notifications.create');
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required',
            'type' => 'required|in:nouveaute,rappel,message,system',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'url' => 'nullable|url'
        ]);

        // Déterminer les destinataires
        $users = $this->getRecipients($request->recipient_type, $request->user_email);

        $count = 0;
        foreach ($users as $user) {
            // Créer la notification
            UserNotification::create([
                'user_id' => $user->id,
                'type' => $request->type,
                'title' => $request->title,
                'message' => $request->message,
                'url' => $request->url
            ]);

            // Envoyer FCM si demandé
            if ($request->send_fcm && $user->fcm_token) {
                sendFCMNotification(
                    $user,
                    $request->title,
                    $request->message,
                    [],
                    $request->url
                );
            }

            // Envoyer WhatsApp si demandé
            if ($request->send_whatsapp && $user->phone) {
                sleep(1); // Éviter le rate limiting
                sendWhatsApp($user->phone, "{$request->title}\n\n{$request->message}");
            }

            $count++;
        }

        return back()->with('success', "{$count} notifications envoyées avec succès");
    }

    private function getRecipients($type, $email = null)
    {
        return match($type) {
            'all' => User::where('status', 1)->get(),
            'active' => User::where('status', 1)
                ->where('email_verified_at', '!=', null)
                ->get(),
            'pending_payment' => User::whereHas('checkouts', function($q) {
                $q->where('payment_status', 0);
            })->get(),
            'specific' => $email ? User::where('email', $email)->get() : collect(),
            default => collect()
        };
    }

    public function index()
    {
        // Liste de toutes les notifications envoyées
        $notifications = UserNotification::with('user')
            ->latest()
            ->paginate(50);

        return view('backend.notifications.index', compact('notifications'));
    }
}
```

### 3. Route admin

```php
// Dans routes/web.php - Section admin

Route::prefix('admin')->name('admin.')->middleware(['admin', 'demo'])->group(function () {
    // ... autres routes ...

    Route::get('notifications/create', [NotificationController::class, 'create'])
        ->name('notifications.create');
    Route::post('notifications/send', [NotificationController::class, 'send'])
        ->name('notifications.send');
    Route::get('notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');
});
```

---

## Bonnes pratiques

### 1. Éviter le spam
```php
// Vérifier qu'une notification similaire n'a pas été envoyée récemment
$recentlySent = UserNotification::where('user_id', $user->id)
    ->where('type', $type)
    ->where('title', $title)
    ->where('created_at', '>=', Carbon::now()->subHours(24))
    ->exists();

if (!$recentlySent) {
    // Envoyer la notification
}
```

### 2. Respecter les préférences utilisateur
```php
// Vérifier que l'utilisateur a activé les notifications
if ($user->push_notification_enabled) {
    sendFCMNotification(...);
}
```

### 3. Logger les erreurs
```php
try {
    sendFCMNotification(...);
} catch (\Exception $e) {
    \Log::error('Failed to send FCM notification', [
        'user_id' => $user->id,
        'error' => $e->getMessage()
    ]);
}
```

### 4. Utiliser des queues pour les envois en masse
```php
use Illuminate\Support\Facades\Queue;

// Créer un job
php artisan make:job SendBulkNotifications

// Dans le job
public function handle()
{
    foreach ($this->users as $user) {
        UserNotification::create([...]);
        sendFCMNotification(...);
    }
}

// Dispatcher le job
Queue::push(new SendBulkNotifications($users, $data));
```

---

## Conclusion

Ce système de notifications permet de:
- Tenir les utilisateurs informés en temps réel
- Envoyer des rappels automatiques
- Communiquer via multiple canaux (app, FCM, WhatsApp)
- Gérer facilement les notifications en masse

Pour plus d'informations, consultez:
- [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md)
- [GUIDE_UTILISATION.md](GUIDE_UTILISATION.md)
