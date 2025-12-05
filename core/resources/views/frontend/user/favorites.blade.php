@extends('frontend.layout.master2')
@section('content2')

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="mb-0">{{ __("Mes Favoris") }}</h3>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteSelected()">
                    <i class="bi bi-trash"></i> {{ __("Supprimer sélection") }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ __("Filtrer par collection") }}</label>
            <select class="form-control" id="collection-filter" onchange="filterFavorites()">
                <option value="">{{ __("Toutes les collections") }}</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection }}" {{ request('collection') == $collection ? 'selected' : '' }}>
                        {{ $collection }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>{{ __("Filtrer par type") }}</label>
            <select class="form-control" id="type-filter" onchange="filterFavorites()">
                <option value="">{{ __("Tous les types") }}</option>
                <option value="App\Models\Plan" {{ request('type') == 'App\Models\Plan' ? 'selected' : '' }}>
                    {{ __("Plans") }}
                </option>
                <option value="App\Models\Country" {{ request('type') == 'App\Models\Country' ? 'selected' : '' }}>
                    {{ __("Pays") }}
                </option>
            </select>
        </div>
    </div>
</div>

<!-- Favorites Grid -->
<div class="row g-3">
    @forelse($favorites as $favorite)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 favorite-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="form-check">
                            <input class="form-check-input favorite-checkbox" type="checkbox"
                                   value="{{ $favorite->id }}" id="fav-{{ $favorite->id }}">
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#"
                                       onclick="editCollection({{ $favorite->id }}, '{{ $favorite->collection }}')">
                                        <i class="bi bi-folder"></i> {{ __("Changer collection") }}
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('user.favorites.remove', $favorite->id) }}" method="POST">
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

                    @if($favorite->favorable)
                        <div class="favorite-content">
                            <!-- Adapt this based on your favorable type -->
                            <h5 class="card-title">
                                @if($favorite->favorable_type == 'App\Models\Plan')
                                    {{ $favorite->favorable->name ?? 'N/A' }}
                                @elseif($favorite->favorable_type == 'App\Models\Country')
                                    {{ $favorite->favorable->name ?? 'N/A' }}
                                @endif
                            </h5>

                            @if($favorite->collection)
                                <span class="badge bg-primary mb-2">{{ $favorite->collection }}</span>
                            @endif

                            <p class="text-muted small">
                                {{ __("Ajouté le") }}: {{ $favorite->created_at->format('d/m/Y') }}
                            </p>

                            @if($favorite->notes)
                                <p class="card-text"><small>{{ $favorite->notes }}</small></p>
                            @endif
                        </div>
                    @else
                        <p class="text-danger">{{ __("Élément supprimé") }}</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="bi bi-heart display-4 d-block mb-3"></i>
                <h5>{{ __("Aucun favori") }}</h5>
                <p>{{ __("Vous n'avez pas encore ajouté de favoris") }}</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="row mt-4">
    <div class="col-12">
        {{ $favorites->links() }}
    </div>
</div>

<!-- Edit Collection Modal -->
<div class="modal fade" id="editCollectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCollectionForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __("Modifier la collection") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __("Nom de la collection") }}</label>
                        <input type="text" class="form-control" name="collection" id="collectionInput">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __("Annuler") }}
                    </button>
                    <button type="submit" class="btn btn-primary">{{ __("Enregistrer") }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    function filterFavorites() {
        const collection = document.getElementById('collection-filter').value;
        const type = document.getElementById('type-filter').value;

        let url = '{{ route("user.favorites") }}?';
        if (collection) url += 'collection=' + collection + '&';
        if (type) url += 'type=' + type;

        window.location.href = url;
    }

    function editCollection(id, currentCollection) {
        document.getElementById('collectionInput').value = currentCollection || '';
        document.getElementById('editCollectionForm').action =
            '/user/favorites/' + id + '/collection';

        new bootstrap.Modal(document.getElementById('editCollectionModal')).show();
    }

    function deleteSelected() {
        const checkboxes = document.querySelectorAll('.favorite-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);

        if (ids.length === 0) {
            alert('{{ __("Veuillez sélectionner au moins un favori") }}');
            return;
        }

        if (!confirm('{{ __("Êtes-vous sûr de vouloir supprimer ces favoris?") }}')) {
            return;
        }

        fetch('{{ route("user.favorites.remove.multiple") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ favorites: ids })
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

@endsection
