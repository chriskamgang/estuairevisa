@php
    $elements =  element('featured.element');
@endphp
<section class="featured-on-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <div class="section-header">
          <span class="section-caption"><i class="fa-solid fa-passport"></i> {{ __("Procédures de Visa") }}</span>
          <h2 class="section-title mt-3">{{ __("Comment Obtenir Votre Visa") }}</h2>
          <p class="mb-0">{{ __("Découvrez les étapes détaillées pour chaque type de visa. Cliquez sur une catégorie pour voir la procédure complète.") }}</p>
        </div>
      </div>
    </div>

    <featured-section>
        <div class="row row-cols-lg-5 row-cols-md-3 row-cols-2  rowcols gy-4 justify-content-center non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
            data-gjs-editable='false' data-gjs-removable='false'
            data-gjs-propagate='["removable","editable","draggable","stylable"]'>

        @foreach($elements as $element)
          @php
              $description = $element->data->description ?? '';
              $shortDescription = $element->data->short_description ?? '';
          @endphp
          <div class="col">
            <div class="featured-on-item text-center" style="cursor: pointer;" onclick="openFeaturedModal({{ $element->id }})" title="{{ __('Cliquez pour voir les détails') }}">
              <div class="featured-image-wrapper mb-3">
                <img src="{{ getFile('featured', $element->data->image) }}" alt="{{ __($element->data->title) }}" class="featured-image">
              </div>
              <h5 class="title mb-2">{{__($element->data->title)}}</h5>
              @if(!empty($shortDescription))
                <p class="short-desc text-muted small">{{ __($shortDescription) }}</p>
              @endif
              <div class="mt-2">
                <small class="text-primary"><i class="fas fa-info-circle"></i> {{ __('En savoir plus') }}</small>
              </div>
            </div>
          </div>
         @endforeach
        </div>
    </featured-section>
  </div>
</section>

<!-- Modals pour le pagebuilder -->
@foreach($elements as $element)
  @php
      $description = $element->data->description ?? '';
  @endphp

  <div class="modal fade" id="featuredModal{{ $element->id }}" tabindex="-1" aria-labelledby="featuredModalLabel{{ $element->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-lg-down modal-dialog-scrollable" style="max-width: 90%; width: 1400px;">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <div class="d-flex align-items-center">
            <img src="{{ getFile('featured', $element->data->image) }}" alt="{{ __($element->data->title) }}" style="max-width: 60px; height: auto; margin-right: 15px; background: white; padding: 5px; border-radius: 8px;">
            <h4 class="modal-title mb-0" id="featuredModalLabel{{ $element->id }}">
              {{ __($element->data->title) }}
            </h4>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <div class="featured-procedure-content">
            {!! $description !!}
          </div>
        </div>
        <div class="modal-footer" style="z-index: 10005 !important;">
          <button type="button" class="btn btn-secondary" onclick="event.stopPropagation(); closeFeaturedModal({{ $element->id }}); return false;" style="z-index: 10006 !important; pointer-events: auto !important;">
            <i class="fas fa-times"></i> {{ __('Fermer') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endforeach

<style>
.featured-on-item {
  padding: 20px 15px;
  border-radius: 12px;
  transition: all 0.3s ease;
  background: #fff;
  border: 2px solid #f0f0f0;
  height: 100%;
}

.featured-on-item:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  border-color: var(--primary-color, #007bff);
}

.featured-image-wrapper {
  width: 80px;
  height: 80px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f8f9fa;
  border-radius: 50%;
  padding: 15px;
}

.featured-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.featured-on-item .title {
  font-size: 1rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 8px;
}

.featured-on-item .short-desc {
  font-size: 0.85rem;
  line-height: 1.4;
  min-height: 40px;
}

.featured-on-item:hover .title {
  color: var(--primary-color, #007bff);
}

/* Fix modal z-index and display issues */
.modal {
  z-index: 9999 !important;
}

.modal-backdrop {
  z-index: 9998 !important;
  opacity: 0.3 !important;
  background-color: rgba(0, 0, 0, 0.3) !important;
}

.modal-backdrop.show {
  opacity: 0.3 !important;
}

.modal-dialog {
  z-index: 10000 !important;
}

.modal-content {
  z-index: 10001 !important;
  position: relative;
}

.modal-footer {
  z-index: 10002 !important;
  position: relative;
}

.modal-footer .btn {
  position: relative;
  z-index: 10003 !important;
  pointer-events: auto !important;
  cursor: pointer !important;
}

.featured-procedure-content {
  text-align: left;
  line-height: 1.8;
  font-size: 1rem;
}

.featured-procedure-content h1,
.featured-procedure-content h2,
.featured-procedure-content h3,
.featured-procedure-content h4 {
  color: #333;
  margin-top: 25px;
  margin-bottom: 15px;
  font-weight: 600;
}

.featured-procedure-content h2 {
  font-size: 1.5rem;
  border-bottom: 2px solid #007bff;
  padding-bottom: 8px;
}

.featured-procedure-content ul,
.featured-procedure-content ol {
  margin: 15px 0;
  padding-left: 25px;
}

.featured-procedure-content li {
  margin-bottom: 10px;
}

.featured-procedure-content img {
  max-width: 100%;
  height: auto;
  border-radius: 8px;
  margin: 20px 0;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.featured-procedure-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
}

.featured-procedure-content table th,
.featured-procedure-content table td {
  padding: 12px;
  border: 1px solid #ddd;
  text-align: left;
}

.featured-procedure-content table th {
  background-color: #f8f9fa;
  font-weight: 600;
}

.featured-procedure-content blockquote {
  border-left: 4px solid #007bff;
  padding-left: 20px;
  margin: 20px 0;
  color: #666;
  font-style: italic;
}

.featured-procedure-content strong {
  color: #333;
  font-weight: 600;
}

@media print {
  .modal-header,
  .modal-footer {
    display: none !important;
  }

  .featured-procedure-content {
    font-size: 12pt;
  }
}
</style>

<script>
// Store modal instances
var featuredModals = {};

function openFeaturedModal(elementId) {
  var modalId = 'featuredModal' + elementId;
  var modalElement = document.getElementById(modalId);

  if (modalElement) {
    // Close any existing modals first
    for (var key in featuredModals) {
      if (featuredModals[key]) {
        featuredModals[key].hide();
      }
    }

    // Create or get modal instance
    if (!featuredModals[elementId]) {
      featuredModals[elementId] = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true,
        focus: true
      });
    }

    // Show the modal
    featuredModals[elementId].show();
  }
}

function closeFeaturedModal(elementId) {
  if (featuredModals[elementId]) {
    featuredModals[elementId].hide();
  }
}

function startVisa() {
  // Close all modals first
  for (var key in featuredModals) {
    if (featuredModals[key]) {
      featuredModals[key].hide();
    }
  }

  // Wait for modal to close, then navigate
  setTimeout(function() {
    window.location.href = '{{ route('home') }}';
  }, 300);
}

// Close modal when clicking on backdrop
document.addEventListener('click', function(event) {
  if (event.target.classList.contains('modal-backdrop')) {
    for (var key in featuredModals) {
      if (featuredModals[key]) {
        featuredModals[key].hide();
      }
    }
  }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    for (var key in featuredModals) {
      if (featuredModals[key]) {
        featuredModals[key].hide();
      }
    }
  }
});
</script>

