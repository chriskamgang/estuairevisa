@php
$content = content('testimonial.content');
$elements = element('testimonial.element');
@endphp

<section class="testimonial-section py-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xxl-6 col-lg-8 text-center">
        <div class="section-header">
          <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __("Testimonial") }}</span>
          <h2 class="section-title mt-3">{{ __("What Our Clients Say") }}</h2>
          <p class="mb-0">{{ __("Working with this team was an absolute pleasure. They understood our needs perfectly and delivered beyond our expectations. Highly recommended!") }}</p>
        </div>
      </div>
    </div>
    <testimonial-section>
      <div class="testimonial-slider non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
        data-gjs-editable='false' data-gjs-removable='false'
        data-gjs-propagate='["removable","editable","draggable","stylable"]'>
        @foreach ($elements as $element)
     
        <div class="testimonial-slide">
          <div class="testimonial-item">
            <span class="icon"><i class="bi bi-quote"></i></span>
            <p class="testimonial-details">{{ translate($element, 'answer') }}</p>

            <div class="client">
              <img src="{{ getFile('testimonial', $element->data->image) }}" alt="image" class="thumb">
              <div class="content">
                <h3 class="name">{{ translate($element, 'client_name') }}</h3>
                <p class="mb-0 ratings">
                  @php
                  $notfill = 5 - $element->data->review;
                  @endphp
                  @for ($i = 1; $i <= $element->data->review; $i++)
                    <i class="bi bi-star-fill"></i>
                    @endfor
                    @for ($i = 1; $i <= $notfill; $i++) <i class="bi bi-star"></i>
                      @endfor
                </p>
              </div>
            </div>
          </div>
        </div>
        @endforeach

      </div>
    </testimonial-section>
  </div>
</section>