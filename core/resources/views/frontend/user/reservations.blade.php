@extends('frontend.layout.master2')
@section('content2')

<div class="row mb-4">
    <div class="col-12">
        <h3 class="mb-0">{{ __("Mes Réservations") }}</h3>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="nir-user-card {{ $status == 'all' ? 'active' : '' }}">
            <a href="{{ route('user.reservations', ['status' => 'all']) }}" class="text-decoration-none">
                <span class="icon"><i class="bi bi-list-ul"></i></span>
                <div class="content">
                    <p class="mb-0">{{ __("Total") }}</p>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="nir-user-card style-pending {{ $status == 'active' ? 'active' : '' }}">
            <a href="{{ route('user.reservations', ['status' => 'active']) }}" class="text-decoration-none">
                <span class="icon"><i class="bi bi-clock-history"></i></span>
                <div class="content">
                    <p class="mb-0">{{ __("En cours") }}</p>
                    <h3 class="mb-0">{{ $stats['active'] }}</h3>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="nir-user-card style-complete {{ $status == 'completed' ? 'active' : '' }}">
            <a href="{{ route('user.reservations', ['status' => 'completed']) }}" class="text-decoration-none">
                <span class="icon"><i class="bi bi-check-circle"></i></span>
                <div class="content">
                    <p class="mb-0">{{ __("Complétées") }}</p>
                    <h3 class="mb-0">{{ $stats['completed'] }}</h3>
                </div>
            </a>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="nir-user-card style-danger {{ $status == 'cancelled' ? 'active' : '' }}">
            <a href="{{ route('user.reservations', ['status' => 'cancelled']) }}" class="text-decoration-none">
                <span class="icon"><i class="bi bi-x-circle"></i></span>
                <div class="content">
                    <p class="mb-0">{{ __("Annulées") }}</p>
                    <h3 class="mb-0">{{ $stats['cancelled'] }}</h3>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Reservations List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @forelse($reservations as $reservation)
                    <div class="reservation-item border-bottom pb-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="text-center">
                                    <span class="badge bg-{{ getStatusColor($reservation->status) }} p-2">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                    <p class="text-muted small mb-0 mt-2">
                                        {{ $reservation->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="mb-1">
                                    {{ __("Réservation") }} #{{ $reservation->id }}
                                </h5>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $reservation->checkout->country ?? 'N/A' }}
                                </p>
                                <p class="mb-0">
                                    <small>
                                        <strong>{{ __("Prix") }}:</strong> {{ number_format($reservation->price, 2) }} FCFA
                                    </small>
                                </p>
                            </div>

                            <div class="col-md-4 text-md-end">
                                <div class="btn-group">
                                    <a href="{{ route('user.visa.details', $reservation->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> {{ __("Détails") }}
                                    </a>

                                    @if(in_array($reservation->status, ['pending', 'proccessing']))
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="cancelReservation({{ $reservation->id }})">
                                            <i class="bi bi-x-circle"></i> {{ __("Annuler") }}
                                        </button>
                                    @endif

                                    @if($reservation->status == 'pending' && $reservation->checkout->payment_status == 0)
                                        <a href="{{ route('user.visa.payment', $reservation->id) }}"
                                           class="btn btn-sm btn-success">
                                            <i class="bi bi-credit-card"></i> {{ __("Payer") }}
                                        </a>
                                    @endif
                                </div>

                                @if($reservation->status == 'complete' || $reservation->status == 'shipped')
                                    <p class="text-success mt-2 mb-0">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ __("Traitement terminé") }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline for status history -->
                        @if($reservation->status_logs ?? false)
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history"></i>
                                    {{ __("Dernière mise à jour") }}:
                                    {{ $reservation->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                        <h5>{{ __("Aucune réservation") }}</h5>
                        <p class="text-muted">{{ __("Vous n'avez pas encore de réservations") }}</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                            {{ __("Faire une demande") }}
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($reservations->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            {{ $reservations->links() }}
        </div>
    </div>
@endif

@push('script')
<script>
    function cancelReservation(id) {
        if (!confirm('{{ __("Êtes-vous sûr de vouloir annuler cette réservation?") }}')) {
            return;
        }

        fetch(`/user/reservations/${id}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Une erreur est survenue") }}');
        });
    }
</script>
@endpush

@php
    function getStatusColor($status) {
        return match($status) {
            'pending' => 'warning',
            'proccessing' => 'info',
            'complete', 'shipped' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
@endphp

@endsection
