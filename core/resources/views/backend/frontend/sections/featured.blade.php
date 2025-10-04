@php 
    $elements =  element('featured.element');
@endphp
<section class="featured-on-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <div class="section-header">
          <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __("Featured") }}</span>
          <h2 class="section-title mt-3">{{ __("They're Talking About Us") }}</h2>
          <p class="mb-0">{{ __("Lorem ipsum dolor sit amet consectetur, adipisicing elit. Cum, voluptatibus rem sit mollitia eum molestias aut quis a perferendis quidem sint harum! Sint cum iste incidunt possimus.") }}</p>
        </div>
      </div>
    </div>
    
    <featured-section>
        <div class="row row-cols-lg-5 row-cols-md-3 row-cols-2  rowcols gy-4 justify-content-center non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
            data-gjs-editable='false' data-gjs-removable='false'
            data-gjs-propagate='["removable","editable","draggable","stylable"]'>
            
        @foreach($elements as $element)
          <div class="col">
            <div class="featured-on-item text-center">
              <img src="{{ getFile('featured', $element->data->image) }}" alt="image">
              <h5 class="title">{{__($element->data->title)}}</h5>
            </div>
          </div>
         @endforeach
        </div>
    </featured-section>
  </div>
</section>