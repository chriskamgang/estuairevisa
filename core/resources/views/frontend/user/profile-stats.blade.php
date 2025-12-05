@extends('frontend.layout.master2')
@section('content2')

<div class="row mb-4">
    <div class="col-12">
        <h3 class="mb-0">{{ __("Mon Profil") }}</h3>
    </div>
</div>

<!-- User Profile Card -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="mb-3">
                    @if($user->image)
                        <img src="{{ getFile('user', $user->image) }}"
                             alt="{{ $user->fullname }}"
                             class="rounded-circle"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto"
                             style="width: 120px; height: 120px; font-size: 3rem;">
                            {{ substr($user->fname, 0, 1) }}{{ substr($user->lname, 0, 1) }}
                        </div>
                    @endif
                </div>

                <h4 class="mb-1">{{ $user->fullname }}</h4>
                <p class="text-muted mb-0">{{ '@' . $user->username }}</p>
                <p class="text-muted">{{ $user->email }}</p>

                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('user.profile') }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> {{ __("Modifier le profil") }}
                    </a>
                    <a href="{{ route('user.change.password') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-shield-lock"></i> {{ __("Changer le mot de passe") }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Statistics Grid -->
        <div class="row g-3">
            <div class="col-6 col-lg-4">
                <div class="nir-user-card">
                    <span class="icon"><i class="bi bi-eye"></i></span>
                    <div class="content">
                        <p class="mb-0">{{ __("Visites") }}</p>
                        <h3 class="mb-0">{{ $stats['total_visits'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="nir-user-card style-favorite">
                    <a href="{{ route('user.favorites') }}" class="text-decoration-none">
                        <span class="icon"><i class="bi bi-heart-fill"></i></span>
                        <div class="content">
                            <p class="mb-0">{{ __("Favoris") }}</p>
                            <h3 class="mb-0">{{ $stats['total_favorites'] }}</h3>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="nir-user-card style-notification">
                    <a href="{{ route('user.notifications') }}" class="text-decoration-none">
                        <span class="icon"><i class="bi bi-bell-fill"></i></span>
                        <div class="content">
                            <p class="mb-0">{{ __("Notifications") }}</p>
                            <h3 class="mb-0">{{ $stats['unread_notifications'] }}</h3>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="nir-user-card style-pending">
                    <span class="icon"><i class="bi bi-clock-history"></i></span>
                    <div class="content">
                        <p class="mb-0">{{ __("En cours") }}</p>
                        <h3 class="mb-0">{{ $stats['active_applications'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="nir-user-card style-complete">
                    <span class="icon"><i class="bi bi-check-circle-fill"></i></span>
                    <div class="content">
                        <p class="mb-0">{{ __("Complétées") }}</p>
                        <h3 class="mb-0">{{ $stats['completed_applications'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-4">
                <div class="nir-user-card style-payment">
                    <span class="icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    <div class="content">
                        <p class="mb-0">{{ __("Paiements dus") }}</p>
                        <h3 class="mb-0">{{ $stats['pending_payments'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Spent Card -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">{{ __("Total dépensé") }}</h6>
                        <h2 class="mb-0">{{ number_format($stats['total_spent'], 2) }} FCFA</h2>
                    </div>
                    <div class="text-primary" style="font-size: 3rem;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Information -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __("Informations personnelles") }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>{{ __("Nom complet") }}:</strong>
                    <p class="mb-0">{{ $user->fullname }}</p>
                </div>
                <div class="mb-3">
                    <strong>{{ __("Email") }}:</strong>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
                <div class="mb-3">
                    <strong>{{ __("Téléphone") }}:</strong>
                    <p class="mb-0">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                @if($user->address)
                    <div class="mb-3">
                        <strong>{{ __("Adresse") }}:</strong>
                        <p class="mb-0">
                            {{ $user->address->city ?? '' }},
                            {{ $user->address->state ?? '' }},
                            {{ $user->address->country ?? '' }}
                            {{ $user->address->zip ?? '' }}
                        </p>
                    </div>
                @endif
                <div class="mb-0">
                    <strong>{{ __("Membre depuis") }}:</strong>
                    <p class="mb-0">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __("Paramètres de compte") }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ __("Authentification à deux facteurs") }}</strong>
                            <p class="text-muted mb-0 small">
                                {{ __("Sécurisez votre compte avec 2FA") }}
                            </p>
                        </div>
                        <a href="{{ route('user.2fa') }}" class="btn btn-sm btn-outline-primary">
                            {{ __("Configurer") }}
                        </a>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ __("Notifications Push") }}</strong>
                            <p class="text-muted mb-0 small">
                                {{ __("Recevez des notifications en temps réel") }}
                            </p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="pushNotifications"
                                   {{ $user->push_notification_enabled ? 'checked' : '' }}
                                   onchange="togglePushNotifications(this)">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ __("Statut du compte") }}</strong>
                            <p class="text-muted mb-0 small">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">{{ __("Vérifié") }}</span>
                                @else
                                    <span class="badge bg-warning">{{ __("Non vérifié") }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ __("Solde du compte") }}</strong>
                            <h4 class="mb-0 text-primary">
                                {{ number_format($user->balance, 2) }} FCFA
                            </h4>
                        </div>
                        <a href="{{ route('user.deposit') }}" class="btn btn-sm btn-primary">
                            {{ __("Recharger") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __("Actions rapides") }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <a href="{{ route('user.reservations') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-calendar-check d-block mb-2" style="font-size: 2rem;"></i>
                            {{ __("Mes Réservations") }}
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('user.favorites') }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-heart d-block mb-2" style="font-size: 2rem;"></i>
                            {{ __("Mes Favoris") }}
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('user.transaction.log') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-receipt d-block mb-2" style="font-size: 2rem;"></i>
                            {{ __("Transactions") }}
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('user.ticket.index') }}" class="btn btn-outline-info w-100">
                            <i class="bi bi-headset d-block mb-2" style="font-size: 2rem;"></i>
                            {{ __("Support") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function togglePushNotifications(checkbox) {
        // Implement push notification toggle logic here
        console.log('Push notifications:', checkbox.checked);

        // You can add AJAX request to update user preferences
        fetch('/user/update-notification-preference', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                push_enabled: checkbox.checked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endpush

@endsection
