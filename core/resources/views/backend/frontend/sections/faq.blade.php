@php
    $content = content('faq.content');
    $elements = element('faq.element');
@endphp

<section class="faq-section">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5">
                <span class="section-top-title">{{ __('Faq') }}</span>
                <h2 class="section-title">{{ __('Frequently Asked Questions') }}</h2>
                
            </div>
            <div class="col-lg-7">
                <faq-section>
                    <div class="accordion non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
                        data-gjs-editable='false' data-gjs-removable='false'
                        data-gjs-propagate='["removable","editable","draggable","stylable"]' id="accordionExample">
                        @foreach ($elements as $item)
                            <div class="accordion-item">
                                <h4 class="accordion-header" id="heading-{{ $loop->iteration }}">
                                    <span class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="false">
                                        {{ __($item->data->question) }}
                                    </span>
                                </h4>
                                <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p> {{ __($item->data->answer) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </faq-section>
            </div>
        </div>

    </div>
</section>
