# 📚 DOCUMENTATION COMPLÈTE - ESTUAIREVISA

> **Système de gestion de demandes de visa en ligne**
> Framework: Laravel 8.x | PHP 8.2+ | MySQL | Bootstrap 5

---

## 📑 TABLE DES MATIÈRES

1. [Vue d'ensemble](#vue-densemble)
2. [Structure du projet](#structure-du-projet)
3. [Architecture technique](#architecture-technique)
4. [Composants principaux](#composants-principaux)
5. [Flux de données](#flux-de-données)
6. [Configuration et démarrage](#configuration-et-démarrage)
7. [Guide du développeur](#guide-du-développeur)
8. [Foire aux questions](#foire-aux-questions)

---

## 🎯 VUE D'ENSEMBLE

### Qu'est-ce qu'Estuairevisa ?

Estuairevisa est une plateforme web permettant de gérer des demandes de visa en ligne. Elle permet aux utilisateurs de :
- Rechercher des visas par pays
- Choisir des plans de visa (Single/Multiple Entry)
- Soumettre des documents requis
- Effectuer des paiements en ligne (15+ passerelles)
- Suivre l'état de leur demande

### Fonctionnalités principales

#### 👥 **Pour les utilisateurs**
- ✅ Inscription/Connexion (avec OAuth Google/Facebook)
- ✅ Recherche de visa par pays de résidence et destination
- ✅ Sélection de plans tarifaires
- ✅ Upload de documents (passeport, photos, etc.)
- ✅ Paiement sécurisé multi-gateway
- ✅ Suivi de demande (en cours, approuvé, rejeté)
- ✅ Système de tickets support
- ✅ Authentification 2FA (Google Authenticator)
- ✅ Multi-langue

#### 🔧 **Pour les administrateurs**
- ✅ Dashboard avec statistiques
- ✅ Gestion des demandes de visa
- ✅ Gestion des utilisateurs
- ✅ Configuration des pays et plans
- ✅ Gestion des champs de formulaire
- ✅ Configuration des passerelles de paiement
- ✅ Gestion du contenu (pages, sections, blog)
- ✅ Paramètres généraux (site, email, SEO)
- ✅ Système de notifications

---

## 📂 STRUCTURE DU PROJET

### Vue générale

```
/Estuairevisa/
│
├── 📄 index.php                    # Point d'entrée principal
├── 📄 .htaccess                    # Configuration Apache
├── 📄 CLAUDE.md                    # Guide pour Claude AI
├── 📄 DOCUMENTATION.md             # Ce fichier
│
├── 📁 asset/                       # Assets statiques (CSS, JS, Images)
│   ├── 📁 admin/                   # Assets du panel admin
│   ├── 📁 frontend/                # Assets du site public
│   └── 📁 images/                  # Images uploadées
│
├── 📁 core/                        # Application Laravel
│   ├── 📁 app/                     # Code métier
│   ├── 📁 resources/               # Vues, langues
│   ├── 📁 routes/                  # Définition des routes
│   ├── 📁 database/                # Seeders (migrations vides)
│   ├── 📁 config/                  # Configuration Laravel
│   ├── 📄 .env                     # Variables d'environnement
│   └── 📄 artisan                  # CLI Laravel
│
└── 📁 install/                     # Installation wizard
    └── 📄 database.sql             # Schéma initial de la BD

```

### Détail du dossier `/core/app/`

```
app/
│
├── 📁 Console/                     # Commandes Artisan custom
│
├── 📁 Exceptions/                  # Gestion des erreurs
│
├── 📁 Http/
│   ├── 📁 Controllers/
│   │   │
│   │   ├── 📁 Admin/               # BACKEND (Panel Admin)
│   │   │   ├── AdminController.php          # Profil admin
│   │   │   ├── HomeController.php           # Dashboard
│   │   │   ├── ManageUserController.php     # Gestion utilisateurs
│   │   │   ├── VisaApplyController.php      # Gestion demandes visa
│   │   │   ├── CountryController.php        # CRUD pays
│   │   │   ├── PlanController.php           # CRUD plans visa
│   │   │   ├── VisaFileFieldController.php  # Champs formulaire
│   │   │   ├── ManageGatewayController.php  # Config paiements
│   │   │   ├── PagesController.php          # CMS/Pages
│   │   │   ├── ManageSectionController.php  # Sections homepage
│   │   │   ├── GeneralSettingController.php # Paramètres site
│   │   │   └── TicketController.php         # Support tickets
│   │   │
│   │   ├── 📁 Auth/                # Authentification utilisateurs
│   │   │   ├── LoginController.php
│   │   │   ├── RegisterController.php
│   │   │   ├── ForgotPasswordController.php
│   │   │   └── SocialAuthController.php     # OAuth (Google/FB)
│   │   │
│   │   ├── 📁 Gateway/             # Passerelles de paiement
│   │   │   ├── 📁 stripe/
│   │   │   ├── 📁 paypal/
│   │   │   ├── 📁 razorpay/
│   │   │   ├── 📁 mollie/
│   │   │   ├── 📁 paystack/
│   │   │   ├── 📁 flutterwave/
│   │   │   ├── 📁 coinpayments/
│   │   │   └── ... (15+ gateways)
│   │   │
│   │   ├── Controller.php          # Base controller
│   │   ├── SiteController.php      # Pages publiques
│   │   ├── UserController.php      # Dashboard utilisateur
│   │   ├── VisaController.php      # Liste visa user
│   │   ├── VisaApplyController.php # Formulaire demande
│   │   ├── PaymentController.php   # Processus paiement
│   │   ├── TicketController.php    # Support user
│   │   └── LoginSecurityController.php # 2FA
│   │
│   ├── 📁 Middleware/              # Middlewares custom
│   │   ├── Admin.php               # Vérif admin
│   │   ├── Inactive.php            # Vérif user actif
│   │   ├── Demo.php                # Mode démo
│   │   ├── IsEmailVerified.php    # Email vérifié
│   │   └── Google2fa.php           # 2FA check
│   │
│   └── 📁 Helpers/
│       └── helpers.php             # Fonctions globales
│
├── 📁 Models/                      # Eloquent Models (25 models)
│   ├── User.php                    # Utilisateurs
│   ├── Admin.php                   # Administrateurs
│   ├── Country.php                 # Pays
│   ├── Plan.php                    # Plans de visa
│   ├── Checkout.php                # Panier/Commande
│   ├── CheckoutLog.php             # Historique commande
│   ├── Payment.php                 # Paiements
│   ├── Deposit.php                 # Dépôts
│   ├── Transaction.php             # Transactions
│   ├── Gateway.php                 # Passerelles paiement
│   ├── VisaFileField.php           # Champs formulaire visa
│   ├── VisaStatusLog.php           # Historique statut visa
│   ├── Ticket.php                  # Tickets support
│   ├── TicketReply.php             # Réponses tickets
│   ├── Page.php                    # Pages CMS
│   ├── SectionData.php             # Sections homepage
│   ├── Menu.php                    # Menus site
│   ├── Language.php                # Langues
│   ├── EmailTemplate.php           # Templates email
│   ├── GeneralSetting.php          # Paramètres généraux
│   ├── Contact.php                 # Messages contact
│   ├── Subscriber.php              # Newsletter
│   ├── Comment.php                 # Commentaires blog
│   ├── LoginSecurity.php           # Config 2FA
│   └── AdminPasswordReset.php      # Reset password admin
│
├── 📁 Notifications/               # Notifications Laravel
│
├── 📁 Providers/                   # Service Providers
│   ├── AppServiceProvider.php
│   ├── AuthServiceProvider.php
│   ├── RouteServiceProvider.php
│   └── OauthConfigServiceProvider.php
│
└── 📁 Support/                     # Classes utilitaires
```

### Détail du dossier `/core/resources/`

```
resources/
│
├── 📁 views/                       # Templates Blade
│   │
│   ├── 📁 backend/                 # ADMIN PANEL
│   │   ├── 📁 auth/                # Connexion admin
│   │   ├── 📁 layout/              # Layout admin
│   │   │   ├── navbar.blade.php
│   │   │   ├── sidebar.blade.php
│   │   │   └── master.blade.php
│   │   ├── dashboard.blade.php     # Dashboard
│   │   ├── 📁 user/                # Gestion users
│   │   ├── 📁 visa/                # Gestion visas
│   │   ├── 📁 country/             # Gestion pays
│   │   ├── 📁 gateways/            # Config paiements
│   │   ├── 📁 frontend/            # Gestion contenu site
│   │   └── ... (autres sections)
│   │
│   ├── 📁 frontend/                # SITE PUBLIC
│   │   ├── 📁 layout/              # Layout public
│   │   │   ├── header.blade.php
│   │   │   ├── footer.blade.php
│   │   │   └── master.blade.php
│   │   ├── home.blade.php          # Page d'accueil
│   │   ├── 📁 visa/                # Pages visa
│   │   ├── 📁 user/                # Dashboard user
│   │   │   ├── dashboard.blade.php
│   │   │   ├── profile.blade.php
│   │   │   ├── visa_list.blade.php
│   │   │   └── ticket.blade.php
│   │   └── pages.blade.php         # Pages CMS
│   │
│   └── 📁 errors/                  # Pages erreur
│       ├── 404.blade.php
│       └── 500.blade.php
│
├── 📁 lang/                        # Traductions
│   ├── 📁 en/
│   └── 📁 fr/
│
├── 📁 js/                          # JavaScript source
│   └── app.js
│
└── 📁 css/                         # CSS source
    └── app.css
```

---

## 🏗️ ARCHITECTURE TECHNIQUE

### Stack technologique

| Composant | Technologie | Version |
|-----------|-------------|---------|
| **Framework** | Laravel | 8.65+ |
| **Langage** | PHP | 8.2+ |
| **Base de données** | MySQL | 5.7+ / 8.0+ |
| **Frontend** | Bootstrap | 5.x |
| **Build Tool** | Laravel Mix | 6.x |
| **Authentification** | Laravel Sanctum | 2.11+ |
| **OAuth** | Laravel Socialite | 5.16+ |
| **2FA** | pragmarx/google2fa-laravel | 2.0+ |

### Architecture MVC

```
┌─────────────────────────────────────────────────────────────┐
│                        NAVIGATEUR                           │
│              (http://estuairevisa.com)                      │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                    SERVEUR WEB (Apache)                     │
│               DocumentRoot: /Estuairevisa/                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                    POINT D'ENTRÉE                           │
│                     /index.php                              │
│                          ↓                                  │
│        require '/core/bootstrap/app.php'                    │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                    LARAVEL KERNEL                           │
│           (dans /core/app/Http/Kernel.php)                  │
│                          ↓                                  │
│              Charge les Middlewares                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                       ROUTING                               │
│                  /core/routes/web.php                       │
│                          ↓                                  │
│   Route::get('/visa/apply', [VisaController, 'apply'])     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                     MIDDLEWARE                              │
│         auth, verified, 2fa, language, etc.                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                     CONTROLLER                              │
│          VisaApplyController@startApplay()                  │
│                          ↓                                  │
│              Logique métier + validation                    │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                       MODEL                                 │
│              Eloquent ORM (Plan, Country)                   │
│                          ↓                                  │
│              Interaction avec la BD                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│                        VIEW                                 │
│      resources/views/frontend/visa/apply.blade.php          │
│                          ↓                                  │
│              Rendu HTML envoyé au navigateur                │
└─────────────────────────────────────────────────────────────┘
```

### Système de routage

Le fichier `/core/routes/web.php` (420+ lignes) contient toutes les routes :

#### Routes Frontend (Public)
```php
Route::get('/', [SiteController::class, 'index'])->name('home');
Route::get('/blogs', [SiteController::class, 'allblog']);
Route::get('/visa/applay/start/{id}', [VisaApplyController::class, 'startApplay']);
```

#### Routes Admin (Protégées)
```php
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [HomeController::class, 'dashboard'])
        ->middleware(['admin', 'demo']);
});
```

#### Routes Utilisateur (Authentifiées)
```php
Route::name('user.')->group(function () {
    Route::middleware(['auth', 'verified', '2fa'])->group(function () {
        Route::get('dashboard', [UserController::class, 'dashboard']);
    });
});
```

---

## 🧩 COMPOSANTS PRINCIPAUX

### 1. Système de Visa

#### Modèles impliqués
- **Country** : Liste des pays (Germany, France, USA...)
- **Plan** : Plans tarifaires par pays (Single Entry, Multiple Entry)
- **VisaFileField** : Champs requis pour chaque pays (passeport, photo...)
- **Checkout** : Panier de demandes de visa
- **CheckoutLog** : Historique des modifications de statut

#### Flux de demande de visa

```
1. USER: Sélectionne pays départ + destination
   → GET /visa/applay/start/{planId}
   → VisaApplyController@startApplay()

2. USER: Remplit informations personnelles
   → POST /visa/applay/info/submit
   → VisaApplyController@infoSubmit()
   → Stocke dans session

3. USER: Upload documents requis
   → POST /visa/applay/documents
   → VisaApplyController@submitDocument()
   → Sauvegarde fichiers dans /asset/images/visa_document/

4. USER: Vérifie panier et choisit paiement
   → GET /visa/applay/checkout
   → VisaApplyController@checkout()

5. USER: Effectue paiement
   → POST /user/paynow/{gatewayId}
   → PaymentController@paynow()
   → Redirige vers gateway (Stripe, PayPal...)

6. GATEWAY: Callback après paiement
   → GET /user/payment-success
   → Crée Checkout + CheckoutLog
   → Status: "processing"

7. ADMIN: Traite la demande
   → POST /admin/visa/status/change/{id}
   → Change status: approved/rejected/canceled

8. USER: Reçoit notification
   → Email + notification dans dashboard
```

### 2. Système de Paiement

#### Passerelles supportées (15+)

| Gateway | Pays principaux | Types |
|---------|-----------------|-------|
| **Stripe** | Mondial | Carte bancaire |
| **PayPal** | Mondial | Compte PayPal |
| **Razorpay** | Inde | UPI, Cartes, Wallets |
| **Mollie** | Europe | iDEAL, Bancontact |
| **Paystack** | Afrique | Mobile money |
| **Flutterwave** | Afrique | Cartes, Mobile |
| **Paytm** | Inde | Wallet, UPI |
| **Coinpayments** | Mondial | Crypto (BTC, ETH...) |
| **Nowpayments** | Mondial | Crypto |
| **Paghiper** | Brésil | Boleto |
| **Voguepay** | Nigeria | Cartes |
| **Bank Transfer** | Mondial | Virement manuel |

#### Configuration d'une gateway (Admin)

```
1. Admin → /admin/gateway/stripe
2. Saisit: API Key, Secret Key, Status (active/inactive)
3. Définit: Frais fixes + pourcentage
4. Sauvegarde → Gateway activée pour les users
```

### 3. Authentification & Sécurité

#### Systèmes d'auth

**1. Multi-guard Laravel**
- `web` guard : Utilisateurs
- `admin` guard : Administrateurs

**2. OAuth Social Login**
```php
// Google
Route::get('/google/login', [SocialAuthController, 'redirectToGoogle']);
Route::get('/google/callback', [SocialAuthController, 'handleGoogleCallback']);

// Facebook
Route::get('/facebook/login', [SocialAuthController, 'redirectToFacebook']);
```

**3. Two-Factor Authentication (2FA)**
- Via Google Authenticator
- Middleware: `Google2fa`
- Controller: `LoginSecurityController`

#### Middlewares de sécurité

| Middleware | Rôle |
|------------|------|
| `Admin` | Vérifie que l'utilisateur est admin |
| `Inactive` | Bloque les comptes désactivés |
| `Demo` | Limite les actions en mode démo |
| `IsEmailVerified` | Force la vérification email |
| `Google2fa` | Vérifie le code 2FA |

### 4. Système CMS (Content Management)

#### Page Builder
- Éditeur drag-and-drop GrapesJS
- Sauvegarde en JSON dans `pages.page_content`
- Shortcodes dynamiques pour sections

#### Sections homepage
```
- Banner section
- Featured section
- How it works
- Plans section
- Testimonials
- Blog section
- Newsletter
```

#### Gestion du contenu
```
/admin/pages/create        → Créer page
/admin/manage/element/blog → Gérer articles blog
/admin/manage/section      → Éditer sections homepage
```

### 5. Multi-langue

#### Structure
```
- Table `languages` : Liste des langues
- Colonne `is_default` : Langue par défaut
- Sessions : Stocke la langue choisie
- Helpers : selectedLanguage()
```

#### Traductions
```
resources/lang/
├── en/
│   ├── auth.php
│   └── validation.php
└── fr/
    ├── auth.php
    └── validation.php
```

---

## 🔄 FLUX DE DONNÉES

### Cycle de vie d'une demande de visa

```mermaid (texte)
┌──────────────┐
│   NOUVEAU    │ (User remplit formulaire)
└──────┬───────┘
       ↓
┌──────────────┐
│  PROCESSING  │ (Paiement effectué, en attente traitement)
└──────┬───────┘
       ↓
    ┌──┴──┐
    ↓     ↓
┌────────┐ ┌────────┐
│APPROVED│ │REJECTED│ (Admin décide)
└────────┘ └────────┘
```

### États possibles (CheckoutLog)

| Status | Description |
|--------|-------------|
| `0` - Processing | En cours de traitement |
| `1` - Approved | Demande approuvée |
| `2` - Rejected | Demande rejetée |
| `3` - Canceled | Annulée par user/admin |

---

## ⚙️ CONFIGURATION ET DÉMARRAGE

### Prérequis

- PHP 8.2+
- MySQL 5.7+ ou 8.0+
- Composer
- Node.js & npm
- Apache/Nginx

### Installation

#### 1. Configuration base de données

```env
# core/.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=immigo
DB_USERNAME=root
DB_PASSWORD=
```

#### 2. Import du schéma

```bash
# Depuis /Estuairevisa/
mysql -u root -p immigo < install/lib/database.sql
```

#### 3. Installation dépendances

```bash
# PHP dependencies
cd core/
composer install

# Node dependencies
npm install
```

#### 4. Compilation assets

```bash
npm run dev   # Développement
npm run prod  # Production
```

#### 5. Créer fichier "installed"

```bash
touch install/installed
```

#### 6. Lancer le serveur

```bash
# Depuis /Estuairevisa/
php -S 127.0.0.1:8000
```

### Accès à l'application

- **Frontend** : http://127.0.0.1:8000
- **Admin Panel** : http://127.0.0.1:8000/admin
  - Email : `admin@gmail.com`
  - Mot de passe : `admin` (à changer)

---

## 👨‍💻 GUIDE DU DÉVELOPPEUR

### Commandes utiles

```bash
# Depuis /core/

# Artisan
php artisan list                    # Liste commandes
php artisan cache:clear             # Nettoyer cache
php artisan config:clear            # Nettoyer config
php artisan view:clear              # Nettoyer vues compilées
php artisan route:list              # Liste toutes les routes

# Composer
composer dump-autoload              # Regénérer autoload

# npm
npm run watch                       # Watch changements assets
npm run hot                         # Hot reload (dev)
```

### Ajouter une nouvelle route

```php
// core/routes/web.php

// Route publique
Route::get('/nouvelle-page', [SiteController::class, 'nouvellePage'])
    ->name('nouvelle.page');

// Route admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['admin', 'demo'])->group(function () {
        Route::get('/nouvelle-fonction', [AdminController::class, 'nouvelle'])
            ->name('nouvelle.fonction');
    });
});
```

### Créer un nouveau controller

```bash
cd core/
php artisan make:controller MonController
```

### Créer un nouveau model

```bash
php artisan make:model MonModel
```

### Structure d'un controller typique

```php
<?php

namespace App\Http\Controllers;

use App\Models\Visa;
use Illuminate\Http\Request;

class MonController extends Controller
{
    public function index()
    {
        $visas = Visa::all();
        return view('frontend.ma-vue', compact('visas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field' => 'required|string|max:255',
        ]);

        // Logique de sauvegarde

        return redirect()->back()->with('success', 'Enregistré !');
    }
}
```

### Helpers disponibles

```php
// Dans core/app/Http/Helpers/helpers.php

selectedLanguage()              // Retourne la langue active
uploadImage($file, $path)       // Upload et redimensionne image
makeDirectory($path)            // Crée un dossier
removeFile($path)               // Supprime un fichier
replaceBaseUrl($content)        // Remplace {base_url} dans contenu
```

### Blade directives utiles

```blade
{{-- Inclure section dynamique --}}
@include('frontend.sections.' . $section->name)

{{-- Vérifier auth --}}
@auth
    Connecté
@endauth

@guest
    Non connecté
@endguest

{{-- Boucle --}}
@foreach($visas as $visa)
    {{ $visa->name }}
@endforeach

{{-- Afficher erreurs validation --}}
@error('field')
    <span class="error">{{ $message }}</span>
@enderror
```

---

## ❓ FOIRE AUX QUESTIONS

### Q: Où sont stockés les fichiers uploadés ?
**R:** Dans `/asset/images/` avec sous-dossiers par type :
- `/asset/images/user/` : Photos de profil
- `/asset/images/visa_document/` : Documents visa
- `/asset/images/country/` : Drapeaux pays

### Q: Comment ajouter une nouvelle passerelle de paiement ?
**R:**
1. Créer `/core/app/Http/Controllers/Gateway/magateway/ProcessController.php`
2. Implémenter les méthodes `pay()`, `returnSuccess()`, `ipn()`
3. Ajouter route dans `web.php`
4. Créer vue config admin dans `/resources/views/backend/gateways/`

### Q: Comment changer la langue par défaut ?
**R:** Dans la BD, table `languages`, mettre `is_default = 1` pour la langue souhaitée.

### Q: Où modifier les emails envoyés ?
**R:** Admin panel → `/admin/email/templates` ou DB table `email_templates`.

### Q: Comment désactiver le mode démo ?
**R:** Retirer le middleware `demo` des routes admin dans `web.php`.

### Q: La structure `/core/` est-elle standard ?
**R:** Non, Laravel devrait être à la racine. Voir `CLAUDE.md` pour explications.

### Q: Puis-je créer une API REST ?
**R:** Oui, ajouter routes dans `/core/routes/api.php` et créer des API Resources.

### Q: Comment déboguer ?
**R:**
```bash
# Activer debug dans .env
APP_DEBUG=true

# Voir logs
tail -f core/storage/logs/laravel.log
```

---

## 📞 SUPPORT & RESSOURCES

### Documentation officielle
- **Laravel** : https://laravel.com/docs/8.x
- **Bootstrap** : https://getbootstrap.com/docs/5.0
- **Blade Templates** : https://laravel.com/docs/8.x/blade

### Structure recommandée
Voir les diagrammes d'architecture dans cette documentation pour comprendre comment améliorer la structure actuelle.

---

**Document créé le :** 2025-10-02
**Version du projet :** 1.0.0
**Auteur de la doc :** Claude AI (Anthropic)
