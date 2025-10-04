@php
$elements = element('how_work.element');
@endphp
<div class="row gy-4">
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