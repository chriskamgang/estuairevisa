# Implémentation des Fonctionnalités Utilisateur - Estuairevisa

## Résumé des Fonctionnalités Implémentées

Ce document décrit les nouvelles fonctionnalités implémentées pour améliorer l'expérience utilisateur de l'application Estuairevisa.

---

## 1. Profil Utilisateur Amélioré

### Route
- `GET /user/profile/stats` - [UserController.php:572](core/app/Http/Controllers/UserController.php#L572)

### Fonctionnalités
- Photo et informations personnelles (déjà existant)
- **Nouvelles statistiques** :
  - Nombre total de demandes de visa
  - Nombre de favoris
  - Montant total dépensé
  - Paiements en attente
  - Notifications non lues
  - Demandes actives
  - Demandes complétées

### Utilisation
```php
Route::get('profile/stats', [UserController::class, 'profileWithStats'])->name('profile.stats');
```

---

## 2. Mes Favoris

### Routes
- `GET /user/favorites` - Liste des favoris
- `POST /user/favorites/add` - Ajouter aux favoris
- `DELETE /user/favorites/{id}` - Retirer des favoris
- `POST /user/favorites/remove-multiple` - Suppression multiple
- `POST /user/favorites/{id}/collection` - Organiser par collection

### Fonctionnalités
- [x] Liste des propriétés/plans sauvegardés
- [x] Organisation par collections
- [x] Suppression multiple
- [x] Filtrage par type et collection
- [x] Relation polymorphique (peut favoriser n'importe quel modèle)

### Modèle de Données
**Table: `favorites`**
- `id` - Identifiant unique
- `user_id` - Référence utilisateur
- `favorable_type` - Type d'entité (polymorphique)
- `favorable_id` - ID de l'entité
- `collection` - Nom de collection (nullable)
- `notes` - Notes personnelles (nullable)
- `timestamps` - Dates de création/modification

### Exemple d'utilisation

#### Ajouter un plan aux favoris
```html
<form action="{{ route('user.favorites.add') }}" method="POST">
    @csrf
    <input type="hidden" name="favorable_type" value="App\Models\Plan">
    <input type="hidden" name="favorable_id" value="{{ $plan->id }}">
    <input type="text" name="collection" placeholder="Collection (optionnelle)">
    <button type="submit">Ajouter aux favoris</button>
</form>
```

#### Retirer des favoris
```html
<form action="{{ route('user.favorites.remove', $favorite->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Retirer</button>
</form>
```

---

## 3. Mes Réservations

### Routes
- `GET /user/reservations` - Liste des réservations
- `POST /user/reservations/{id}/cancel` - Annuler une réservation

### Fonctionnalités
- [x] Onglets de filtrage (Toutes, En cours, Complétées, Annulées)
- [x] Détails de chaque réservation
- [x] Actions rapides (annuler, voir détails)
- [x] Historique complet
- [x] Statistiques par statut

### Paramètres de filtrage
- `status=all` - Toutes les réservations
- `status=active` - En cours (pending, proccessing)
- `status=completed` - Complétées (complete, shipped)
- `status=cancelled` - Annulées

### Exemple d'utilisation

#### Afficher les réservations en cours
```html
<a href="{{ route('user.reservations', ['status' => 'active']) }}">
    En cours ({{ $stats['active'] }})
</a>
```

#### Annuler une réservation
```html
<form action="{{ route('user.reservations.cancel', $reservation->id) }}" method="POST">
    @csrf
    <button type="submit" onclick="return confirm('Êtes-vous sûr?')">
        Annuler
    </button>
</form>
```

---

## 4. Centre de Notifications

### Routes
- `GET /user/notifications` - Centre de notifications
- `POST /user/notifications/{id}/read` - Marquer comme lu
- `POST /user/notifications/{id}/unread` - Marquer comme non lu
- `POST /user/notifications/read-all` - Tout marquer comme lu
- `DELETE /user/notifications/{id}` - Supprimer une notification
- `POST /user/notifications/delete-read` - Supprimer toutes les notifications lues

### Fonctionnalités
- [x] Centre de notifications
- [x] Catégories (nouveautés, rappels, messages, système)
- [x] Marquer comme lu/non lu
- [x] Filtrage par type et statut
- [x] Suppression individuelle et en masse
- [x] Compteurs par catégorie

### Modèle de Données
**Table: `user_notifications`**
- `id` - Identifiant unique
- `user_id` - Référence utilisateur
- `type` - Type (nouveaute, rappel, message, system)
- `title` - Titre de la notification
- `message` - Contenu du message
- `data` - Données JSON additionnelles (nullable)
- `url` - URL de redirection (nullable)
- `read` - Statut de lecture (boolean)
- `read_at` - Date de lecture (nullable)
- `timestamps` - Dates de création/modification

### Types de notifications
- `nouveaute` - Nouvelles fonctionnalités, actualités
- `rappel` - Rappels de paiements, documents manquants
- `message` - Messages de l'équipe support
- `system` - Notifications système

### Exemple d'utilisation

#### Créer une notification
```php
use App\Models\UserNotification;

UserNotification::create([
    'user_id' => $user->id,
    'type' => 'rappel',
    'title' => 'Document manquant',
    'message' => 'Veuillez télécharger votre passeport',
    'url' => route('user.visa.details', $visaId),
    'data' => ['visa_id' => $visaId]
]);
```

#### Marquer comme lu (AJAX)
```javascript
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
        // Mettre à jour l'UI
    }
});
```

---

## 5. Modèles et Relations

### User Model
Nouvelles relations ajoutées dans [User.php:59-72](core/app/Models/User.php#L59-L72):

```php
public function favorites()
{
    return $this->hasMany(Favorite::class);
}

public function notifications()
{
    return $this->hasMany(UserNotification::class)->latest();
}

public function unreadNotifications()
{
    return $this->hasMany(UserNotification::class)->where('read', false);
}
```

### Favorite Model
[Favorite.php](core/app/Models/Favorite.php)
- Relation polymorphique avec `favorable()`
- Scopes: `byCollection()`, `byType()`

### UserNotification Model
[UserNotification.php](core/app/Models/UserNotification.php)
- Méthodes: `markAsRead()`, `markAsUnread()`
- Scopes: `unread()`, `read()`, `byType()`

---

## 6. Migrations

### Créées
1. **2025_12_05_063210_create_favorites_table.php**
   - Table `favorites` avec index et contraintes

2. **2025_12_05_063245_create_notifications_table.php**
   - Table `user_notifications` avec index sur `user_id`, `read`, et `type`

### Exécution
```bash
cd core
php artisan migrate
```

---

## 7. Structure des Fichiers

### Contrôleurs
- [core/app/Http/Controllers/UserController.php](core/app/Http/Controllers/UserController.php)
  - Lignes 221-353: Gestion des favoris
  - Lignes 355-429: Gestion des réservations
  - Lignes 431-567: Gestion des notifications
  - Lignes 569-601: Profil avec statistiques

### Routes
- [core/routes/web.php:336-353](core/routes/web.php#L336-L353)

### Modèles
- [core/app/Models/Favorite.php](core/app/Models/Favorite.php)
- [core/app/Models/UserNotification.php](core/app/Models/UserNotification.php)
- [core/app/Models/User.php](core/app/Models/User.php) (relations ajoutées)

### Migrations
- [core/database/migrations/2025_12_05_063210_create_favorites_table.php](core/database/migrations/2025_12_05_063210_create_favorites_table.php)
- [core/database/migrations/2025_12_05_063245_create_notifications_table.php](core/database/migrations/2025_12_05_063245_create_notifications_table.php)

---

## 8. Prochaines Étapes

### À faire par le développeur frontend:

1. **Créer les vues Blade**
   - `resources/views/frontend/user/favorites.blade.php`
   - `resources/views/frontend/user/reservations.blade.php`
   - `resources/views/frontend/user/notifications.blade.php`
   - `resources/views/frontend/user/profile-stats.blade.php`

2. **Intégrer les boutons "Ajouter aux favoris"**
   - Sur les pages de plans/destinations
   - Icône cœur avec état actif/inactif

3. **Ajouter les liens dans la navigation**
   - Menu utilisateur
   - Sidebar du tableau de bord

4. **Implémenter les notifications push**
   - Utiliser le système FCM déjà en place
   - Créer les notifications depuis l'admin

5. **Ajouter les styles CSS**
   - Cartes de favoris
   - Timeline de notifications
   - Badges de compteurs

### Configuration requise:

1. **Base de données**
   - Configurer `.env` avec les credentials DB
   - Exécuter les migrations: `php artisan migrate`

2. **Permissions**
   - Vérifier que les utilisateurs authentifiés ont accès aux routes

---

## 9. Exemples de Code pour les Vues

### Afficher le nombre de notifications non lues (dans le header)
```blade
@auth
    <a href="{{ route('user.notifications') }}">
        <i class="fas fa-bell"></i>
        @if($unreadCount = auth()->user()->unreadNotifications()->count())
            <span class="badge">{{ $unreadCount }}</span>
        @endif
    </a>
@endauth
```

### Bouton d'ajout aux favoris réutilisable
```blade
@php
    $isFavorite = auth()->check()
        ? auth()->user()->favorites()
            ->where('favorable_type', get_class($item))
            ->where('favorable_id', $item->id)
            ->exists()
        : false;
@endphp

<button
    class="btn-favorite {{ $isFavorite ? 'active' : '' }}"
    onclick="toggleFavorite(this, '{{ get_class($item) }}', {{ $item->id }})">
    <i class="fas fa-heart"></i>
</button>
```

### Script JavaScript pour favoris (AJAX)
```javascript
function toggleFavorite(button, type, id) {
    fetch('/user/favorites/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            favorable_type: type,
            favorable_id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        button.classList.toggle('active');
        // Afficher un message de succès
    });
}
```

---

## 10. Notes Importantes

- Toutes les routes sont protégées par les middlewares `auth`, `inactive`, `is_email_verified`, et `2fa`
- Les méthodes utilisent le pattern Laravel standard avec validation et gestion d'erreurs
- Les notifications utilisent le système de notifications existant de l'application
- Le système de favoris est polymorphique et peut être utilisé pour n'importe quel modèle
- Les réservations utilisent les tables `checkouts` et `checkout_logs` existantes

---

## Support

Pour toute question ou problème, référez-vous aux fichiers sources mentionnés ci-dessus ou consultez la documentation Laravel 8.
