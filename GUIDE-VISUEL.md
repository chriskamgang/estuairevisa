# 🎨 GUIDE VISUEL - ESTUAIREVISA

> Diagrammes et schémas pour comprendre rapidement le projet

---

## 📊 RELATIONS ENTRE MODELS (Base de données)

### Vue d'ensemble des entités principales

```
┌─────────────────────────────────────────────────────────────────────────┐
│                        SYSTÈME DE VISA                                  │
└─────────────────────────────────────────────────────────────────────────┘

     ┌──────────┐           ┌──────────┐           ┌──────────┐
     │ Country  │──────────▶│   Plan   │──────────▶│ Checkout │
     └──────────┘  has many └──────────┘  has many └──────────┘
         │                       │                       │
         │                       │                       │
         │ has many              │ has many              │ belongs to
         ▼                       ▼                       ▼
  ┌────────────┐         ┌──────────────┐        ┌──────────┐
  │VisaFileField│         │CheckoutLog   │        │   User   │
  └────────────┘         └──────────────┘        └──────────┘
  (Champs requis)        (Historique statut)     (Utilisateur)


┌─────────────────────────────────────────────────────────────────────────┐
│                      SYSTÈME DE PAIEMENT                                │
└─────────────────────────────────────────────────────────────────────────┘

  ┌──────────┐          ┌──────────┐          ┌──────────────┐
  │ Checkout │─────────▶│ Payment  │─────────▶│  Gateway     │
  └──────────┘  creates └──────────┘  uses    └──────────────┘
       │                      │                (Stripe, PayPal...)
       │                      │
       │ has many             │ creates
       ▼                      ▼
  ┌──────────────┐      ┌──────────────┐
  │ CheckoutLog  │      │ Transaction  │
  └──────────────┘      └──────────────┘
  (Historique)          (Historique finances)


┌─────────────────────────────────────────────────────────────────────────┐
│                      SYSTÈME UTILISATEUR                                │
└─────────────────────────────────────────────────────────────────────────┘

  ┌──────────┐          ┌──────────────┐         ┌──────────┐
  │   User   │─────────▶│LoginSecurity │         │  Admin   │
  └──────────┘  has one └──────────────┘         └──────────┘
       │                 (Config 2FA)            (Séparé de User)
       │
       │ has many
       ├──────────────┐
       │              │
       ▼              ▼
  ┌──────────┐  ┌──────────┐
  │ Checkout │  │  Ticket  │
  └──────────┘  └──────────┘
  (Demandes)    (Support)
                     │
                     │ has many
                     ▼
              ┌─────────────┐
              │TicketReply  │
              └─────────────┘


┌─────────────────────────────────────────────────────────────────────────┐
│                      SYSTÈME CMS/CONTENU                                │
└─────────────────────────────────────────────────────────────────────────┘

  ┌──────────┐          ┌──────────────┐
  │ Language │◀─────────│     Page     │
  └──────────┘ belongs  └──────────────┘
  (Français,            (Pages CMS)
   Anglais...)                │
                              │ has
                              ▼
                        ┌──────────────┐
                        │SectionData   │
                        └──────────────┘
                        (Blog, Témoignages...)

  ┌──────────┐
  │   Menu   │
  └──────────┘
  (Navigation)
```

---

## 🗺️ CARTOGRAPHIE DES CONTROLLERS

### Controllers Frontend (Public)

```
📁 Controllers/
│
├── 🌐 SiteController               # Pages générales
│   ├── index()                     → Homepage
│   ├── page($slug)                 → Pages CMS dynamiques
│   ├── allblog()                   → Liste blog
│   ├── blog($id)                   → Article blog
│   └── contactSend()               → Formulaire contact
│
├── 🎫 VisaController               # Gestion visa user
│   ├── all()                       → Liste demandes user
│   ├── details($orderId)           → Détails demande
│   ├── reSubmit($orderId)          → Re-soumettre si rejeté
│   └── visaPayment()               → Paiement complémentaire
│
├── 📝 VisaApplyController          # Formulaire demande
│   ├── startApplay($planId)        → Début demande
│   ├── applayInfos()               → Formulaire infos perso
│   ├── infoSubmit()                → Validation infos
│   ├── applyDocuments()            → Upload documents
│   ├── submitDocument()            → Sauvegarder documents
│   ├── checkout()                  → Panier
│   ├── placeorder()                → Créer commande (avant paiement)
│   ├── cart()                      → Voir panier
│   ├── track()                     → Suivre commande
│   └── planChange($id)             → Changer de plan
│
├── 👤 UserController               # Dashboard user
│   ├── dashboard()                 → Dashboard
│   ├── profile()                   → Voir profil
│   ├── profileUpdate()             → Modifier profil
│   ├── changePassword()            → Formulaire changement MDP
│   ├── updatePassword()            → Sauvegarder nouveau MDP
│   ├── referral()                  → Programme de parrainage
│   └── transactionLog()            → Historique transactions
│
├── 💳 PaymentController            # Processus paiement
│   ├── gateways()                  → Liste gateways
│   ├── gatewaysDetails($id)        → Détails gateway
│   ├── gatewayRedirect()           → Redirection vers gateway
│   └── paynow($gatewayId)          → Initier paiement
│
├── 🎟️ TicketController             # Support client
│   ├── index()                     → Liste tickets
│   ├── create()                    → Créer ticket
│   ├── store()                     → Sauvegarder ticket
│   ├── show($id)                   → Voir détails
│   ├── reply()                     → Répondre
│   └── statusChange($id)           → Fermer/Ouvrir ticket
│
└── 🔐 Auth/                        # Authentification
    ├── LoginController             → Connexion
    ├── RegisterController          → Inscription
    ├── ForgotPasswordController    → Reset MDP
    └── SocialAuthController        → OAuth (Google/FB)
```

### Controllers Backend (Admin)

```
📁 Controllers/Admin/
│
├── 📊 HomeController               # Dashboard admin
│   ├── dashboard()                 → Stats & graphiques
│   ├── subscribers()               → Liste abonnés newsletter
│   ├── transaction()               → Transactions
│   └── markNotification()          → Marquer notif lue
│
├── 👥 ManageUserController         # Gestion utilisateurs
│   ├── index()                     → Liste users
│   ├── userDetails($userId)        → Profil complet
│   ├── userUpdate($userId)         → Modifier user
│   ├── userBalanceUpdate()         → Ajuster solde
│   ├── sendUserMail()              → Envoyer email
│   ├── disabled()                  → Users désactivés
│   ├── userStatusWiseFilter()      → Filtrer par statut
│   ├── contactList()               → Messages contact
│   └── loginAsUser($userId)        → Se connecter en tant que user
│
├── 🎫 VisaApplyController          # Gestion demandes visa
│   ├── list($type)                 → Liste (all/pending/approved...)
│   ├── details($orderId)           → Détails demande
│   ├── changeStatus($fileId)       → Approuver/Rejeter
│   └── download($fileId)           → Télécharger document
│
├── 🌍 CountryController            # Gestion pays
│   ├── index()                     → Liste pays
│   ├── store()                     → Ajouter pays
│   ├── update($id)                 → Modifier pays
│   └── delete($id)                 → Supprimer pays
│
├── 💼 PlanController               # Gestion plans visa
│   ├── index()                     → Liste plans
│   ├── create()                    → Formulaire création
│   ├── store()                     → Sauvegarder plan
│   ├── edit($id)                   → Formulaire édition
│   ├── update($id)                 → Modifier plan
│   └── destroy($id)                → Supprimer plan
│
├── 📋 VisaFileFieldController      # Champs formulaire
│   ├── index()                     → Liste champs
│   ├── create()                    → Nouveau champ
│   ├── store()                     → Sauvegarder
│   ├── edit($id)                   → Modifier
│   └── destroy($id)                → Supprimer
│
├── 💳 ManageGatewayController      # Configuration paiements
│   ├── stripe()                    → Config Stripe
│   ├── stripeUpdate()              → Sauvegarder Stripe
│   ├── paypal()                    → Config PayPal
│   ├── razorpay()                  → Config Razorpay
│   ├── mollie()                    → Config Mollie
│   ├── ... (15+ gateways)
│   ├── depositLog()                → Historique dépôts
│   ├── depositAccept($trx)         → Accepter dépôt manuel
│   └── depositReject($trx)         → Rejeter dépôt
│
├── 📄 PagesController              # CMS - Gestion pages
│   ├── index()                     → Liste pages
│   ├── pageCreate()                → Créer page
│   ├── pageInsert()                → Sauvegarder
│   ├── pageEdit($id)               → Éditer
│   ├── pageUpdate($id)             → Modifier
│   ├── pageDelete($id)             → Supprimer
│   ├── pageContent($id)            → Page builder (GrapesJS)
│   ├── saveContent()               → Sauvegarder JSON
│   ├── uploadPbImage()             → Upload image builder
│   └── removePbImage()             → Supprimer image
│
├── 🧩 ManageSectionController      # Sections homepage
│   ├── index()                     → Liste sections
│   ├── section($name)              → Éditer section
│   ├── sectionContentUpdate()      → Sauvegarder section
│   ├── sectionElement($name)       → Liste éléments (blog, témoignages)
│   ├── sectionElementCreate()      → Créer élément
│   ├── editElement($id)            → Éditer élément
│   ├── updateElement($id)          → Modifier élément
│   ├── deleteElement($id)          → Supprimer élément
│   ├── blogCategory()              → Catégories blog
│   └── faqCategory()               → Catégories FAQ
│
├── ⚙️ GeneralSettingController     # Paramètres généraux
│   ├── index()                     → Paramètres site
│   ├── generalSettingUpdate()      → Sauvegarder
│   ├── preloader()                 → Config preloader
│   ├── analytics()                 → Google Analytics
│   ├── cookieConsent()             → Cookie consent
│   ├── recaptcha()                 → Google reCAPTCHA
│   ├── socialite()                 → OAuth (Google/FB)
│   ├── seoManage()                 → SEO meta tags
│   └── cacheClear()                → Nettoyer cache
│
├── 📧 EmailTemplateController      # Templates email
│   ├── emailConfig()               → Config SMTP
│   ├── emailTemplates()            → Liste templates
│   ├── emailTemplatesEdit($id)     → Éditer template
│   └── emailTemplatesUpdate($id)   → Sauvegarder
│
├── 🌐 LanguageController           # Multi-langue
│   ├── index()                     → Liste langues
│   ├── store()                     → Ajouter langue
│   ├── update($id)                 → Modifier
│   ├── delete($id)                 → Supprimer
│   ├── transalate($lang)           → Traduire (éditeur)
│   ├── transalateUpate($lang)      → Sauvegarder traductions
│   ├── import($lang)               → Importer fichier
│   ├── export($lang)               → Exporter fichier
│   └── changeLang()                → Changer langue active
│
├── 🎟️ TicketController             # Support admin
│   ├── index()                     → Liste tickets
│   ├── pendingList()               → Tickets en attente
│   ├── show($id)                   → Voir ticket
│   └── reply()                     → Répondre
│
├── 📊 ReportController             # Rapports
│   └── paymentReport()             → Rapport paiements
│
├── 🔧 MenuController               # Menus navigation
│   ├── index()                     → Gérer menus
│   ├── headerStore()               → Menu header
│   └── footerStore()               → Menu footer
│
└── 👨‍💼 AdminController             # Profil admin
    ├── profile()                   → Voir profil
    ├── profileUpdate()             → Modifier profil
    └── changePassword()            → Changer MDP
```

### Controllers Gateway (Paiements)

```
📁 Controllers/Gateway/
│
├── 📁 stripe/
│   └── ProcessController           → Stripe payment
├── 📁 paypal/
│   └── ProcessController           → PayPal payment
├── 📁 razorpay/
│   └── ProcessController           → Razorpay (Inde)
├── 📁 mollie/
│   └── ProcessController           → Mollie (Europe)
├── 📁 paystack/
│   └── ProcessController           → Paystack (Afrique)
├── 📁 flutterwave/
│   └── ProcessController           → Flutterwave
├── 📁 paytm/
│   └── ProcessController           → Paytm (Inde)
├── 📁 coinpayments/
│   └── ProcessController           → Crypto (BTC, ETH...)
├── 📁 nowpayments/
│   └── ProcessController           → Crypto
└── ... (15+ gateways)

Méthodes communes :
├── pay()                           → Initier paiement
├── returnSuccess()                 → Callback succès
└── ipn()                           → Webhook notification
```

---

## 🛤️ PARCOURS UTILISATEUR

### 1. Demande de visa (Frontend)

```
┌──────────────────────────────────────────────────────────────────────┐
│                       ÉTAPE 1 : RECHERCHE                            │
└──────────────────────────────────────────────────────────────────────┘

User sur Homepage → Formulaire recherche
┌─────────────────────────────────┐
│  I am from:    [Germany ▼]      │
│  I live in:    [France  ▼]      │
│         [Find Now]              │
└─────────────────────────────────┘
                ↓
    GET /visa/search/countries
    Retourne plans disponibles


┌──────────────────────────────────────────────────────────────────────┐
│                  ÉTAPE 2 : SÉLECTION PLAN                            │
└──────────────────────────────────────────────────────────────────────┘

Affiche plans (Single Entry / Multiple Entry)
┌───────────────┬───────────────┐
│ SINGLE ENTRY  │ MULTIPLE ENTRY│
│   $50         │     $120      │
│ [Apply Now]   │ [Apply Now]   │
└───────────────┴───────────────┘
                ↓
    GET /visa/applay/start/{planId}
    Session: stocke plan choisi


┌──────────────────────────────────────────────────────────────────────┐
│               ÉTAPE 3 : INFORMATIONS PERSONNELLES                    │
└──────────────────────────────────────────────────────────────────────┘

Formulaire infos
┌─────────────────────────────────┐
│  Full Name: [_____________]     │
│  Email:     [_____________]     │
│  Phone:     [_____________]     │
│  Passport#: [_____________]     │
│         [Next Step]             │
└─────────────────────────────────┘
                ↓
    POST /visa/applay/info/submit
    Session: stocke infos


┌──────────────────────────────────────────────────────────────────────┐
│                   ÉTAPE 4 : UPLOAD DOCUMENTS                         │
└──────────────────────────────────────────────────────────────────────┘

Upload documents requis
┌─────────────────────────────────┐
│  Passport Copy: [Choose File]   │
│  Photo:         [Choose File]   │
│  Bank Statement:[Choose File]   │
│         [Submit]                │
└─────────────────────────────────┘
                ↓
    POST /visa/applay/documents
    Sauvegarde: /asset/images/visa_document/


┌──────────────────────────────────────────────────────────────────────┐
│                     ÉTAPE 5 : CHECKOUT                               │
└──────────────────────────────────────────────────────────────────────┘

Panier récapitulatif
┌─────────────────────────────────┐
│  Germany → France               │
│  Single Entry Plan              │
│  Amount: $50                    │
│  [Proceed to Payment]           │
└─────────────────────────────────┘
                ↓
    GET /visa/applay/placeorder
    Crée Checkout (status: pending payment)


┌──────────────────────────────────────────────────────────────────────┐
│                    ÉTAPE 6 : PAIEMENT                                │
└──────────────────────────────────────────────────────────────────────┘

Choix gateway
┌─────────────────────────────────┐
│  [💳 Stripe]                    │
│  [🅿️ PayPal]                    │
│  [₹  Razorpay]                  │
│  [🪙 Crypto]                    │
└─────────────────────────────────┘
                ↓
    POST /user/paynow/{gatewayId}
    Redirect vers gateway
                ↓
         User paie
                ↓
    Callback: /user/payment-success
    Crée CheckoutLog (status: processing)


┌──────────────────────────────────────────────────────────────────────┐
│                  ÉTAPE 7 : CONFIRMATION                              │
└──────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────┐
│   ✅ Payment Successful!        │
│                                 │
│  Order ID: #12345               │
│  Status: Processing             │
│                                 │
│  [View in Dashboard]            │
└─────────────────────────────────┘
```

### 2. Traitement admin (Backend)

```
┌──────────────────────────────────────────────────────────────────────┐
│                      WORKFLOW ADMIN                                  │
└──────────────────────────────────────────────────────────────────────┘

Admin se connecte
    ↓
GET /admin/dashboard
    - Voir stats
    - Notifications demandes en attente
    ↓
GET /admin/visa/list/pending
    - Liste demandes "processing"
    ↓
GET /admin/visa/{orderId}/details
    - Voir infos complètes
    - Télécharger documents
    - Vérifier données
    ↓
POST /admin/visa/status/change/{fileId}
    Choisit: Approved / Rejected / Canceled
    ↓
CheckoutLog mis à jour
    ↓
User reçoit notification + email
```

---

## 🎨 INTERFACE UTILISATEUR

### Frontend (Public)

```
┌──────────────────────────────────────────────────────────────────────┐
│  HEADER                                                              │
│  [Logo]  Home  Visa  Blog  Contact              [Login] [Register]  │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                          HERO SECTION                                │
│                                                                      │
│         Fast, Easy & Secure Visa Processing Online                  │
│                                                                      │
│         ┌─────────────────────────────────┐                         │
│         │  I am from:    [Germany ▼]      │                         │
│         │  I live in:    [France  ▼]      │                         │
│         │         [Find Now]              │                         │
│         └─────────────────────────────────┘                         │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                       FEATURED COUNTRIES                             │
│   🇩🇪 Germany    🇬🇧 UK    🇫🇷 France    🇪🇸 Spain    🇮🇹 Italy    │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                         HOW IT WORKS                                 │
│   1️⃣ Choose     2️⃣ Fill Form    3️⃣ Upload     4️⃣ Get Visa         │
│   Destination     & Pay         Documents                           │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│  FOOTER                                                              │
│  About | Privacy | Terms | Contact                                  │
└──────────────────────────────────────────────────────────────────────┘
```

### Backend (Admin Panel)

```
┌──────────────────────────────────────────────────────────────────────┐
│  ADMIN HEADER                                                        │
│  [Logo]  Dashboard               [Notifications 🔔] [Admin ▼]       │
└──────────────────────────────────────────────────────────────────────┘
│  SIDEBAR         │                MAIN CONTENT                       │
├──────────────────┼───────────────────────────────────────────────────┤
│ 📊 Dashboard     │  ┌──────────────────────────────────────────┐    │
│ 👥 Users         │  │        DASHBOARD STATISTICS              │    │
│ 🎫 Visa Requests │  │                                          │    │
│ 🌍 Countries     │  │  Total Users: 1,234                      │    │
│ 💼 Plans         │  │  Pending Visas: 45                       │    │
│ 💳 Gateways      │  │  Approved Today: 12                      │    │
│ 📄 Pages         │  │  Revenue: $12,450                        │    │
│ 📧 Email         │  │                                          │    │
│ 🌐 Languages     │  │  [Chart] Visa Requests (Last 30 days)   │    │
│ ⚙️ Settings      │  │                                          │    │
│ 🎟️ Tickets       │  └──────────────────────────────────────────┘    │
│ 🔴 Logout        │                                                   │
└──────────────────┴───────────────────────────────────────────────────┘
```

### User Dashboard

```
┌──────────────────────────────────────────────────────────────────────┐
│  USER HEADER                                                         │
│  [Logo]  Dashboard  My Visas  Tickets       [John Doe ▼] [Logout]  │
└──────────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────────┐
│                        USER DASHBOARD                                │
│                                                                      │
│  Welcome back, John! 👋                                              │
│                                                                      │
│  ┌────────────┬────────────┬────────────┬────────────┐              │
│  │ 📋 Total   │ ⏳ Pending │ ✅ Approved│ ❌ Rejected│              │
│  │ Visas: 5   │    2       │     2      │     1      │              │
│  └────────────┴────────────┴────────────┴────────────┘              │
│                                                                      │
│  Recent Applications:                                                │
│  ┌──────────────────────────────────────────────────┐               │
│  │ #001 | Germany → France | Processing | $50       │               │
│  │ #002 | USA → Canada     | Approved   | $120      │               │
│  └──────────────────────────────────────────────────┘               │
│                                                                      │
│  [Apply for New Visa]                                                │
└──────────────────────────────────────────────────────────────────────┘
```

---

## 🔐 SYSTÈME DE SÉCURITÉ

### Middlewares en cascade

```
Requête HTTP
    ↓
┌─────────────────────────┐
│   CSRF Protection       │  Laravel automatique
└───────────┬─────────────┘
            ↓
┌─────────────────────────┐
│   Rate Limiting         │  Limite requêtes par IP
└───────────┬─────────────┘
            ↓
┌─────────────────────────┐
│   Auth Check            │  Middleware: auth
└───────────┬─────────────┘
            ↓
┌─────────────────────────┐
│   Email Verified        │  Middleware: is_email_verified
└───────────┬─────────────┘
            ↓
┌─────────────────────────┐
│   Account Active        │  Middleware: inactive
└───────────┬─────────────┘
            ↓
┌─────────────────────────┐
│   2FA Check             │  Middleware: 2fa
└───────────┬─────────────┘
            ↓
┌─────────────────────────┐
│   Admin Check (si besoin)│ Middleware: admin
└───────────┬─────────────┘
            ↓
     CONTROLLER
```

### Permissions admin

```
Super Admin (role_id = 1)
    ├─ Tous les droits
    └─ Accès complet

Staff Admin (role_id = 2)
    ├─ Gérer visas
    ├─ Voir users
    └─ Répondre tickets

Demo Mode
    ├─ Middleware: demo
    └─ Bloque modifications
```

---

## 📞 POINTS D'INTÉGRATION

### APIs externes

```
┌──────────────────────────────────────────────────────────────────────┐
│                      INTÉGRATIONS TIERCES                            │
└──────────────────────────────────────────────────────────────────────┘

🔐 OAuth (Socialite)
├─ Google OAuth 2.0
│  ├─ Client ID: config/services.php
│  └─ Callback: /google/callback
│
└─ Facebook OAuth
   ├─ App ID: config/services.php
   └─ Callback: /facebook/callback

📧 Email (SMTP)
├─ PHPMailer
├─ Config: Admin → Email Settings
└─ Templates: admin/email/templates

💳 Payment Gateways (15+)
├─ Stripe API
│  └─ Webhooks: /stripe/webhook
├─ PayPal SDK
│  └─ IPN: /paypal/ipn
└─ ... (autres gateways)

🔔 Notifications
├─ Database (table notifications)
├─ Email
└─ Browser Push (optionnel)

📊 Analytics
└─ Google Analytics (script dans footer)

🔐 2FA
└─ Google Authenticator (pragmarx/google2fa)
```

---

**📌 NOTE IMPORTANTE**

Cette documentation visuelle complète le fichier `DOCUMENTATION.md`.
Consultez les deux documents ensemble pour une compréhension complète du projet.

---

**Créé le :** 2025-10-02
**Version :** 1.0.0
