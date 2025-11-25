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
            <a href="{{ route('featured.detail', $element->id) }}" class="featured-link">
              <div class="featured-on-item text-center" title="{{ __('Cliquez pour voir les détails') }}">
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
            </a>
          </div>
         @endforeach
        </div>
    </featured-section>
  </div>
</section>

<!-- Modals removed - now using dedicated pages -->

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

/* Featured link styles */
.featured-link {
  text-decoration: none;
  color: inherit;
  display: block;
  transition: transform 0.3s ease;
}

.featured-link:hover {
  text-decoration: none;
  color: inherit;
}

.featured-link:hover .featured-on-item {
  transform: translateY(-8px);
}
</style>

<script>
// Featured section - no modals, using page navigation
console.log('Featured section initialized');
</script>

