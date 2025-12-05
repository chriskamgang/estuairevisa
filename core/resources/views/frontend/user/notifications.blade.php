@extends('frontend.layout.master2')
@section('content2')

<div class="row mb-4">
    <div class="col-md-8">
        <h3 class="mb-0">{{ __("Centre de Notifications") }}</h3>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-outline-primary"
                    onclick="markAllAsRead()">
                <i class="bi bi-check-all"></i> {{ __("Tout marquer comme lu") }}
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="deleteAllRead()">
                <i class="bi bi-trash"></i> {{ __("Supprimer lues") }}
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'all' ? 'active' : '' }}"
                   href="{{ route('user.notifications', ['filter' => 'all']) }}">
                    {{ __("Toutes") }} ({{ $counts['all'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter == 'unread' ? 'active' : '' }}"
                   href="{{ route('user.notifications', ['filter' => 'unread']) }}">
                    <i class="bi bi-envelope"></i> {{ __("Non lues") }} ({{ $counts['unread'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'nouveaute' ? 'active' : '' }}"
                   href="{{ route('user.notifications', ['type' => 'nouveaute']) }}">
                    <i class="bi bi-star"></i> {{ __("Nouveautés") }} ({{ $counts['nouveaute'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'rappel' ? 'active' : '' }}"
                   href="{{ route('user.notifications', ['type' => 'rappel']) }}">
                    <i class="bi bi-bell"></i> {{ __("Rappels") }} ({{ $counts['rappel'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $type == 'message' ? 'active' : '' }}"
                   href="{{ route('user.notifications', ['type' => 'message']) }}">
                    <i class="bi bi-chat"></i> {{ __("Messages") }} ({{ $counts['message'] }})
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Notifications List -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0">
                @forelse($notifications as $notification)
                    <div class="notification-item {{ !$notification->read ? 'unread' : '' }} border-bottom p-3"
                         id="notification-{{ $notification->id }}">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="notification-icon {{ getNotificationIconClass($notification->type) }}">
                                    <i class="{{ getNotificationIcon($notification->type) }}"></i>
                                </div>
                            </div>

                            <div class="col">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            {{ $notification->title }}
                                            @if(!$notification->read)
                                                <span class="badge bg-primary ms-2">{{ __("Nouveau") }}</span>
                                            @endif
                                        </h6>
                                        <p class="mb-2 text-muted">{{ $notification->message }}</p>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button"
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if(!$notification->read)
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       onclick="markAsRead({{ $notification->id }})">
                                                        <i class="bi bi-check"></i> {{ __("Marquer comme lu") }}
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       onclick="markAsUnread({{ $notification->id }})">
                                                        <i class="bi bi-envelope"></i> {{ __("Marquer comme non lu") }}
                                                    </a>
                                                </li>
                                            @endif

                                            @if($notification->url)
                                                <li>
                                                    <a class="dropdown-item" href="{{ $notification->url }}">
                                                        <i class="bi bi-box-arrow-up-right"></i> {{ __("Voir détails") }}
                                                    </a>
                                                </li>
                                            @endif

                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('user.notifications.delete', $notification->id) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash"></i> {{ __("Supprimer") }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-bell-slash display-4 text-muted d-block mb-3"></i>
                        <h5>{{ __("Aucune notification") }}</h5>
                        <p class="text-muted">{{ __("Vous n'avez pas de notifications pour le moment") }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($notifications->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            {{ $notifications->links() }}
        </div>
    </div>
@endif

@push('style')
<style>
    .notification-item {
        transition: background-color 0.3s;
    }

    .notification-item.unread {
        background-color: #f8f9fa;
        border-left: 3px solid #0d6efd;
    }

    .notification-item:hover {
        background-color: #f1f3f5;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .notification-icon.icon-nouveaute {
        background-color: #d4edda;
        color: #155724;
    }

    .notification-icon.icon-rappel {
        background-color: #fff3cd;
        color: #856404;
    }

    .notification-icon.icon-message {
        background-color: #cce5ff;
        color: #004085;
    }

    .notification-icon.icon-system {
        background-color: #e2e3e5;
        color: #383d41;
    }
</style>
@endpush

@push('script')
<script>
    function markAsRead(id) {
        fetch(`/user/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAsUnread(id) {
        fetch(`/user/notifications/${id}/unread`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function markAllAsRead() {
        if (!confirm('{{ __("Marquer toutes les notifications comme lues?") }}')) {
            return;
        }

        fetch('{{ route("user.notifications.read.all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("Une erreur est survenue") }}');
        });
    }

    function deleteAllRead() {
        if (!confirm('{{ __("Supprimer toutes les notifications lues?") }}')) {
            return;
        }

        fetch('{{ route("user.notifications.delete.read") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
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
    function getNotificationIcon($type) {
        return match($type) {
            'nouveaute' => 'bi bi-star-fill',
            'rappel' => 'bi bi-bell-fill',
            'message' => 'bi bi-chat-fill',
            'system' => 'bi bi-gear-fill',
            default => 'bi bi-info-circle-fill'
        };
    }

    function getNotificationIconClass($type) {
        return 'icon-' . $type;
    }
@endphp

@endsection
