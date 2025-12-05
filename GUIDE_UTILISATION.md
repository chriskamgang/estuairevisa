# Guide d'Utilisation - Nouvelles Fonctionnalités Utilisateur

## Table des matières
1. [Introduction](#introduction)
2. [Installation](#installation)
3. [Utilisation des Favoris](#utilisation-des-favoris)
4. [Gestion des Réservations](#gestion-des-réservations)
5. [Centre de Notifications](#centre-de-notifications)
6. [Profil avec Statistiques](#profil-avec-statistiques)
7. [Exemples d'Intégration](#exemples-dintégration)
8. [Personnalisation](#personnalisation)

---

## Introduction

Ce guide explique comment utiliser les nouvelles fonctionnalités implémentées dans l'application Estuairevisa :
- Système de Favoris
- Gestion des Réservations améliorée
- Centre de Notifications
- Profil utilisateur avec statistiques

---

## Installation

### 1. Exécuter les migrations

```bash
cd core
php artisan migrate
```

Cela créera deux nouvelles tables :
- `favorites` - Pour stocker les favoris des utilisateurs
- `user_notifications` - Pour le système de notifications

### 2. Vérifier les routes

Toutes les routes ont été ajoutées dans [core/routes/web.php](core/routes/web.php#L336-L353). Vérifiez qu'elles sont bien chargées :

```bash
php artisan route:list | grep user
```

### 3. Publier les assets (si nécessaire)

Si vous utilisez Laravel Mix pour compiler les assets :

```bash
npm run dev
# ou pour la production
npm run prod
```

---

## Utilisation des Favoris

### Ajouter un bouton "Ajouter aux favoris"

#### Méthode 1 : Utiliser le composant Blade (Recommandé)

```blade
@include('frontend.partials.favorite-button', [
    'item' => $plan,
    'type' => 'App\Models\Plan'
])
```

#### Méthode 2 : HTML personnalisé

```html
<form action="{{ route('user.favorites.add') }}" method="POST">
    @csrf
    <input type="hidden" name="favorable_type" value="App\Models\Plan">
    <input type="hidden" name="favorable_id" value="{{ $plan->id }}">
    <input type="text" name="collection" placeholder="Collection (optionnelle)">
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-heart"></i> Ajouter aux favoris
    </button>
</form>
```

### Afficher les favoris de l'utilisateur

L'utilisateur peut accéder à ses favoris via :
```
/user/favorites
```

### Fonctionnalités disponibles

1. **Filtrer par collection**
   ```
   /user/favorites?collection=Mes%20destinations%20préférées
   ```

2. **Filtrer par type**
   ```
   /user/favorites?type=App\Models\Plan
   ```

3. **Supprimer un favori**
   ```blade
   <form action="{{ route('user.favorites.remove', $favorite->id) }}" method="POST">
       @csrf
       @method('DELETE')
       <button type="submit">Supprimer</button>
   </form>
   ```

4. **Supprimer plusieurs favoris**
   ```javascript
   fetch('/user/favorites/remove-multiple', {
       method: 'POST',
       headers: {
           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
           'Content-Type': 'application/json'
       },
       body: JSON.stringify({
           favorites: [1, 2, 3] // IDs des favoris
       })
   });
   ```

### Vérifier si un item est en favori

```blade
@php
    $isFavorite = auth()->check()
        ? auth()->user()->favorites()
            ->where('favorable_type', 'App\Models\Plan')
            ->where('favorable_id', $plan->id)
            ->exists()
        : false;
@endphp

@if($isFavorite)
    <span class="badge bg-danger">Favori</span>
@endif
```

---

## Gestion des Réservations

### Accéder aux réservations

L'utilisateur peut voir toutes ses réservations à :
```
/user/reservations
```

### Filtrer par statut

- Toutes : `/user/reservations?status=all`
- En cours : `/user/reservations?status=active`
- Complétées : `/user/reservations?status=completed`
- Annulées : `/user/reservations?status=cancelled`

### Annuler une réservation

```blade
<form action="{{ route('user.reservations.cancel', $reservation->id) }}" method="POST">
    @csrf
    <button type="submit" onclick="return confirm('Êtes-vous sûr?')">
        Annuler la réservation
    </button>
</form>
```

### Exemple de carte de réservation

```blade
<div class="card">
    <div class="card-body">
        <h5>Réservation #{{ $reservation->id }}</h5>
        <p>Statut: {{ $reservation->status }}</p>
        <p>Prix: {{ number_format($reservation->price, 2) }} FCFA</p>
        <p>Date: {{ $reservation->created_at->format('d/m/Y') }}</p>

        @if(in_array($reservation->status, ['pending', 'proccessing']))
            <form action="{{ route('user.reservations.cancel', $reservation->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Annuler</button>
            </form>
        @endif
    </div>
</div>
```

---

## Centre de Notifications

### Créer une notification

#### Depuis le contrôleur

```php
use App\Models\UserNotification;

UserNotification::create([
    'user_id' => $user->id,
    'type' => 'rappel', // nouveaute, rappel, message, system
    'title' => 'Document manquant',
    'message' => 'Veuillez télécharger votre passeport pour compléter votre demande.',
    'url' => route('user.visa.details', $visaId),
    'data' => json_encode(['visa_id' => $visaId]) // optionnel
]);
```

#### Types de notifications disponibles

- `nouveaute` - Nouvelles fonctionnalités, actualités
- `rappel` - Rappels de paiements, documents manquants
- `message` - Messages de l'équipe support
- `system` - Notifications système

### Afficher le badge de notifications non lues

```blade
@auth
    <a href="{{ route('user.notifications') }}" class="nav-link">
        <i class="bi bi-bell"></i>
        @if($unreadCount = auth()->user()->unreadNotifications()->count())
            <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
        @endif
    </a>
@endauth
```

### Marquer une notification comme lue (AJAX)

```javascript
function markAsRead(notificationId) {
    fetch(`/user/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'interface
            console.log('Notification marquée comme lue');
        }
    });
}
```

### Envoyer une notification avec FCM et WhatsApp

```php
use App\Models\User;

$user = User::find($userId);

// Envoyer via FCM
if ($user->fcm_token) {
    sendFCMNotification(
        $user,
        'Nouveau message',
        'Vous avez reçu un nouveau message de notre équipe',
        ['type' => 'message'],
        route('user.notifications')
    );
}

// Envoyer via WhatsApp
if ($user->phone) {
    sendWhatsApp(
        $user->phone,
        'Bonjour ' . $user->fname . ', vous avez reçu un nouveau message sur Estuairevisa.'
    );
}

// Créer la notification dans la base de données
UserNotification::create([
    'user_id' => $user->id,
    'type' => 'message',
    'title' => 'Nouveau message',
    'message' => 'Vous avez reçu un nouveau message de notre équipe',
    'url' => route('user.ticket.index')
]);
```

---

## Profil avec Statistiques

### Accéder au profil enrichi

```
/user/profile/stats
```

### Statistiques disponibles

Le profil affiche automatiquement :
- Nombre total de visites/demandes
- Nombre de favoris
- Notifications non lues
- Demandes actives
- Demandes complétées
- Paiements en attente
- Total dépensé

### Récupérer les statistiques dans un contrôleur

```php
use App\Models\Favorite;
use App\Models\UserNotification;
use App\Models\CheckoutLog;
use App\Models\Checkout;

$stats = [
    'total_favorites' => Favorite::where('user_id', $userId)->count(),
    'unread_notifications' => UserNotification::where('user_id', $userId)
        ->where('read', false)
        ->count(),
    'active_applications' => CheckoutLog::whereHas('checkout', function ($q) use ($userId) {
        $q->where('user_id', $userId);
    })->whereIn('status', ['pending', 'proccessing'])->count(),
    // etc.
];
```

---

## Exemples d'Intégration

### 1. Ajouter un bouton favori sur une page de plan

```blade
{{-- resources/views/frontend/plan/show.blade.php --}}

@extends('frontend.layout.master')

@section('content')
    <div class="plan-detail">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $plan->name }}</h1>

            {{-- Bouton Favori --}}
            @include('frontend.partials.favorite-button', [
                'item' => $plan,
                'type' => 'App\Models\Plan'
            ])
        </div>

        <div class="plan-content">
            <p>{{ $plan->description }}</p>
            <p>Prix: {{ number_format($plan->price, 2) }} FCFA</p>
        </div>
    </div>
@endsection
```

### 2. Notification automatique lors d'une nouvelle demande

```php
// Dans VisaApplyController.php

public function submitDocument(Request $request)
{
    // ... votre logique existante ...

    // Créer une notification
    UserNotification::create([
        'user_id' => auth()->id(),
        'type' => 'system',
        'title' => 'Demande reçue',
        'message' => 'Votre demande de visa a été reçue et est en cours de traitement.',
        'url' => route('user.visa.details', $visaId)
    ]);

    // Envoyer notification push
    if (auth()->user()->fcm_token) {
        sendFCMNotification(
            auth()->user(),
            'Demande reçue',
            'Votre demande de visa est en cours de traitement',
            ['visa_id' => $visaId],
            route('user.visa.details', $visaId)
        );
    }

    return redirect()->route('user.visa.details', $visaId);
}
```

### 3. Menu de navigation utilisateur

```blade
{{-- resources/views/frontend/partials/user-menu.blade.php --}}

<nav class="user-menu">
    <ul>
        <li>
            <a href="{{ route('user.dashboard') }}">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>
        </li>
        <li>
            <a href="{{ route('user.profile.stats') }}">
                <i class="bi bi-person-circle"></i> Mon Profil
            </a>
        </li>
        <li>
            <a href="{{ route('user.reservations') }}">
                <i class="bi bi-calendar-check"></i> Mes Réservations
                @if($activeCount = auth()->user()->checkouts()->where('payment_status', 0)->count())
                    <span class="badge bg-warning">{{ $activeCount }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('user.favorites') }}">
                <i class="bi bi-heart"></i> Mes Favoris
                <span class="badge bg-danger">
                    {{ auth()->user()->favorites()->count() }}
                </span>
            </a>
        </li>
        <li>
            <a href="{{ route('user.notifications') }}">
                <i class="bi bi-bell"></i> Notifications
                @if($unreadCount = auth()->user()->unreadNotifications()->count())
                    <span class="badge bg-primary">{{ $unreadCount }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('user.transaction.log') }}">
                <i class="bi bi-receipt"></i> Transactions
            </a>
        </li>
    </ul>
</nav>
```

### 4. Widget de notifications récentes

```blade
{{-- Afficher les 5 dernières notifications --}}

<div class="card">
    <div class="card-header">
        <h5>Notifications récentes</h5>
    </div>
    <div class="card-body p-0">
        @php
            $recentNotifications = auth()->user()
                ->notifications()
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @forelse($recentNotifications as $notification)
            <div class="notification-item {{ !$notification->read ? 'unread' : '' }} p-3 border-bottom">
                <h6>{{ $notification->title }}</h6>
                <p class="mb-1">{{ Str::limit($notification->message, 80) }}</p>
                <small class="text-muted">
                    {{ $notification->created_at->diffForHumans() }}
                </small>
                @if($notification->url)
                    <a href="{{ $notification->url }}" class="btn btn-sm btn-link">Voir</a>
                @endif
            </div>
        @empty
            <p class="text-center text-muted py-3">Aucune notification</p>
        @endforelse

        <div class="text-center p-2">
            <a href="{{ route('user.notifications') }}" class="btn btn-sm btn-link">
                Voir toutes les notifications
            </a>
        </div>
    </div>
</div>
```

---

## Personnalisation

### Changer les couleurs du bouton favori

Modifiez le CSS dans `resources/views/frontend/partials/favorite-button.blade.php` :

```css
.btn-favorite {
    border-color: #your-color;
    color: #your-color;
}

.btn-favorite:hover,
.btn-favorite.active {
    background: #your-color;
}
```

### Personnaliser les icônes de notifications

Modifiez la fonction `getNotificationIcon()` dans `notifications.blade.php` :

```php
function getNotificationIcon($type) {
    return match($type) {
        'nouveaute' => 'bi bi-star-fill',
        'rappel' => 'bi bi-alarm-fill',
        'message' => 'bi bi-envelope-fill',
        'system' => 'bi bi-info-circle-fill',
        default => 'bi bi-bell-fill'
    };
}
```

### Ajouter des champs personnalisés aux favoris

1. Créer une migration pour ajouter des colonnes :
```bash
php artisan make:migration add_custom_fields_to_favorites_table
```

2. Dans la migration :
```php
Schema::table('favorites', function (Blueprint $table) {
    $table->integer('priority')->default(0);
    $table->boolean('is_shared')->default(false);
});
```

3. Mettre à jour le modèle `Favorite` :
```php
protected $fillable = [
    'user_id',
    'favorable_type',
    'favorable_id',
    'collection',
    'notes',
    'priority',
    'is_shared'
];
```

---

## Support et Documentation

### Fichiers importants

- **Contrôleur** : [core/app/Http/Controllers/UserController.php](core/app/Http/Controllers/UserController.php)
- **Modèles** :
  - [core/app/Models/Favorite.php](core/app/Models/Favorite.php)
  - [core/app/Models/UserNotification.php](core/app/Models/UserNotification.php)
- **Routes** : [core/routes/web.php](core/routes/web.php#L336-L353)
- **Vues** :
  - [core/resources/views/frontend/user/favorites.blade.php](core/resources/views/frontend/user/favorites.blade.php)
  - [core/resources/views/frontend/user/reservations.blade.php](core/resources/views/frontend/user/reservations.blade.php)
  - [core/resources/views/frontend/user/notifications.blade.php](core/resources/views/frontend/user/notifications.blade.php)
  - [core/resources/views/frontend/user/profile-stats.blade.php](core/resources/views/frontend/user/profile-stats.blade.php)

### Dépannage

**Problème : Les migrations ne fonctionnent pas**
- Vérifiez la configuration de votre base de données dans `.env`
- Assurez-vous que MySQL/MariaDB est en cours d'exécution

**Problème : Les favoris ne s'affichent pas**
- Vérifiez que l'utilisateur est bien connecté
- Vérifiez les relations dans le modèle `User`

**Problème : Les notifications push ne fonctionnent pas**
- Vérifiez la configuration Firebase dans `config/firebase.php`
- Assurez-vous que le token FCM est bien enregistré

Pour plus d'aide, consultez le fichier [IMPLEMENTATION_SUMMARY.md](IMPLEMENTATION_SUMMARY.md).
