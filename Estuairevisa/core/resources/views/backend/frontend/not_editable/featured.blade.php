@php 
    $elements = element('featured.element');
@endphp

<div class="row row-cols-lg-5 row-cols-md-3 row-cols-2  rowcols gy-4 justify-content-center">
    @foreach($elements as $element)
      <div class="col">
        <div class="featured-on-item text-center">
          <img src="{{ getFile('featured', $element->data->image) }}" alt="image">
          <h3 class="title">{{__($element->data->title)}}</h3>
        </div>
      </div>
     @endforeach
</div>