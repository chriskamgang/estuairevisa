@php
$elements = element('how_work.element');
@endphp

<section class="how-work-section py-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="section-header text-center">
          <span class="section-caption"><i class="fa-solid fa-plane-departure"></i> {{ __("How Work") }}</span>
          <h2 class="section-title mt-3">{{ __("3 Easy Steps to Get Started") }}</h2>
          <p>{{ __("Lorem, ipsum dolor sit amet consectetur adipisicing elit. Consequuntur sint cumque autem magni
            molestiae, earum molestias animi ipsa, qui in vel repudiandae. Ex voluptatum tenetur obcaecati.") }}</p>
        </div>
      </div>
    </div>

    <how_work-section>
      <div class="row gy-4 non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
        data-gjs-editable='false' data-gjs-removable='false'
        data-gjs-propagate='["removable","editable","draggable","stylable"]'>
        @foreach($elements as $key=>$element)
        <div class="col-lg-4">
          <div class="how-work-item">
            <div class="thumb">
              <img src="{{ getFile('how_work', $element->data->image) }}" alt="image">
            </div>
            <div class="content">
              <span class="work-number">{{$key < 10 ? "0" : "" }}{{$key+1}}</span>
                  <h3 class="h4">{{__($element->data->title)}}</h3>
                  <p class="mb-0">{{__($element->data->short_description)}}</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </how_work-section>
  </div>
</section>