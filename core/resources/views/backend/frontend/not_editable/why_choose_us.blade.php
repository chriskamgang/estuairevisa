@php
    $content = content('why_choose_us.content');
    $elements = element('why_choose_us.element')->take(6);
@endphp


<div class="row gy-5 non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false' data-gjs-editable='false'
    data-gjs-removable='false' data-gjs-propagate='["removable","editable","draggable","stylable"]'>
    @foreach ($elements as $k => $element)
        <div class="col-xl-6 col-lg-12 col-md-6">
            <div class="choose-item">
                <div class="choose-item-title">
                    <h5><span>{{ $k + 1 }}</span> {{ translate($element, 'card_title') }}</h5>
                </div>
                <div class="choose-item-content">
                    <div class="icon">
                        <i class="{{ $element->data->card_icon }}"></i>
                    </div>
                    <div class="choose-item-details">
                        <p class="mb-0">{{ translate($element, 'card_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
