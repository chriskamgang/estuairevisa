@php
    $content = content('faq.content');
    $elements = element('faq.element');
@endphp

<div class="accordion" id="accordionExample">
    @foreach ($elements as $item)
        <div class="accordion-item">
            <h4 class="accordion-header" id="heading-{{ $loop->iteration }}">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                    data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="false">
                    {{ __($item->data->question) }}
                </button>
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
