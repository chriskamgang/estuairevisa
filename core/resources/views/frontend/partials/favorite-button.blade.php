{{--
    Favorite Button Component

    Usage:
    @include('frontend.partials.favorite-button', [
        'item' => $plan,
        'type' => 'App\Models\Plan'
    ])
--}}

@auth
    @php
        $isFavorite = auth()->user()->favorites()
            ->where('favorable_type', $type)
            ->where('favorable_id', $item->id)
            ->exists();

        $favoriteId = $isFavorite
            ? auth()->user()->favorites()
                ->where('favorable_type', $type)
                ->where('favorable_id', $item->id)
                ->first()->id
            : null;
    @endphp

    <button type="button"
            class="btn-favorite {{ $isFavorite ? 'active' : '' }}"
            data-favorite-id="{{ $favoriteId }}"
            data-item-type="{{ $type }}"
            data-item-id="{{ $item->id }}"
            onclick="toggleFavorite(this)"
            title="{{ $isFavorite ? __('Retirer des favoris') : __('Ajouter aux favoris') }}">
        <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}"></i>
    </button>
@else
    <a href="{{ route('user.login') }}"
       class="btn-favorite"
       title="{{ __('Connectez-vous pour ajouter aux favoris') }}">
        <i class="bi bi-heart"></i>
    </a>
@endauth

@once
    @push('style')
    <style>
        .btn-favorite {
            background: transparent;
            border: 2px solid #dc3545;
            color: #dc3545;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            font-size: 1.2rem;
            text-decoration: none;
        }

        .btn-favorite:hover {
            background: #dc3545;
            color: white;
            transform: scale(1.1);
        }

        .btn-favorite.active {
            background: #dc3545;
            color: white;
        }

        .btn-favorite.active i {
            animation: heartBeat 0.3s ease;
        }

        @keyframes heartBeat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.3); }
        }

        /* Small variant */
        .btn-favorite.btn-sm {
            width: 32px;
            height: 32px;
            font-size: 1rem;
        }

        /* Large variant */
        .btn-favorite.btn-lg {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
    </style>
    @endpush

    @push('script')
    <script>
        function toggleFavorite(button) {
            const isActive = button.classList.contains('active');
            const favoriteId = button.dataset.favoriteId;
            const itemType = button.dataset.itemType;
            const itemId = button.dataset.itemId;

            if (isActive && favoriteId) {
                // Remove from favorites
                removeFavorite(button, favoriteId);
            } else {
                // Add to favorites
                addFavorite(button, itemType, itemId);
            }
        }

        function addFavorite(button, type, id) {
            fetch('{{ route("user.favorites.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    favorable_type: type,
                    favorable_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update button state
                button.classList.add('active');
                button.querySelector('i').classList.remove('bi-heart');
                button.querySelector('i').classList.add('bi-heart-fill');
                button.title = '{{ __("Retirer des favoris") }}';

                // Show success message (optional)
                showNotification('{{ __("Ajouté aux favoris") }}', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('{{ __("Erreur lors de l\'ajout") }}', 'error');
            });
        }

        function removeFavorite(button, favoriteId) {
            fetch(`/user/favorites/${favoriteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update button state
                button.classList.remove('active');
                button.querySelector('i').classList.remove('bi-heart-fill');
                button.querySelector('i').classList.add('bi-heart');
                button.dataset.favoriteId = '';
                button.title = '{{ __("Ajouter aux favoris") }}';

                // Show success message (optional)
                showNotification('{{ __("Retiré des favoris") }}', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('{{ __("Erreur lors de la suppression") }}', 'error');
            });
        }

        function showNotification(message, type) {
            // Implement your notification system here
            // This is a simple example using alert
            // You can replace this with toast notifications, etc.

            // Example with Bootstrap Toast (if available)
            if (typeof bootstrap !== 'undefined') {
                const toastHtml = `
                    <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;

                // Create toast container if it doesn't exist
                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.className = 'position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '9999';
                    document.body.appendChild(toastContainer);
                }

                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                const toastElement = toastContainer.lastElementChild;
                const toast = new bootstrap.Toast(toastElement);
                toast.show();

                // Remove toast element after it's hidden
                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastElement.remove();
                });
            } else {
                // Fallback to simple alert
                console.log(message);
            }
        }
    </script>
    @endpush
@endonce
