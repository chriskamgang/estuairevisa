<div class="modal fade" id="countrySelectModal" tabindex="-1" aria-labelledby="countrySelectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body pt-5">
                <p class="text-lg text-center mb-5">{{__("Please tell us where are you from and where do you live")}}</p>

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="single-find-field flex-grow-1">
                        <div class="icon">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                        <div class="visa-field">
                            <span>{{__("I am from")}}</span>
                            <input type="text" name="i_am_from" value="" autocomplete="off">
                            <ul class="visa-country-list scroll-y">
                                @forelse($countries as $country)
                                <li data-value="{{$country->getTranslatedName()}}" data-id="{{$country->id}}">
                                    <img src="{{getFile('country',$country->image)}}" alt="image">
                                    <span>{{$country->getTranslatedName()}}</span>
                                </li>
                                @empty
                                <li class="no-result">
                                    <span class="icon"><i class="bi bi-search"></i></span>
                                    <p class="mb-0">{{__("The country you are looking for is not on our list.")}}
                                    </p>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <div class="single-find-field flex-grow-1">
                        <div class="icon">
                            <i class="bi bi-house-door-fill"></i>
                        </div>
                        <div class="visa-field">
                            <span>{{__("I live in")}}</span>
                            <input type="text" name="i_live_in" value="" autocomplete="off">

                            <ul class="visa-country-list scroll-y">
                                @forelse($countries as $country)
                                <li data-value="{{$country->getTranslatedName()}}" data-id="{{$country->id}}">
                                    <img src="{{getFile('country',$country->image)}}" alt="image">
                                    <span>{{$country->getTranslatedName()}}</span>
                                </li>
                                @empty
                                <li class="no-result">
                                    <span class="icon"><i class="bi bi-search"></i></span>
                                    <p class="mb-0">{{__("The country you are looking for is not on our list.")}}
                                    </p>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="d-flex align-items-center justify-content-end gap-3 mt-5">
                    <button type="button" class="btn btn-md btn-secondary"
                        data-bs-dismiss="modal">{{__("Cancel")}}</button>
                    <button type="button" class="btn btn-md btn-primary fetch-visa_info-btn">{{__("Next")}} <i
                            class="bi bi-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    'use strict';

    $(function () {

        // Show list on focus or click
        $(document).off('focus click', '.visa-field input').on('focus click', '.visa-field input', function () {
            const $input = $(this);
            const $list = $input.siblings('.visa-country-list');
            $list.addClass('active');
        });

        // Filter country list on typing
        $(document).off('input', '.visa-field input').on('input', '.visa-field input', function () {
            const $input = $(this);
            const query = $input.val().toLowerCase();
            const $list = $input.siblings('.visa-country-list');
            const $items = $list.find('li:not(.no-result)');
            let hasMatch = false;

            $items.each(function () {
                const $item = $(this);
                const match = $item.data('value').toLowerCase().includes(query);
                $item.toggle(match);
                if (match) hasMatch = true;
            });

            $list.find('.no-result').toggle(!hasMatch);
            $list.addClass('active');
        });

        // Select country from list
        $(document).off('click', '.visa-country-list li').on('click', '.visa-country-list li', function () {
            const $item = $(this);
            if ($item.hasClass('no-result')) return;

            const value = $item.data('value');
            const id = $item.data('id');
            const $field = $item.closest('.visa-field');

            $field.find('input').val(value).attr('data-id', id);
            $item.addClass('active').siblings().removeClass('active');
            $item.closest('.visa-country-list').removeClass('active');
        });

        // Hide list when clicking outside
        $(document).off('click.visaField').on('click.visaField', function (e) {
            if (!$(e.target).closest('.visa-field').length) {
                $('.visa-country-list').removeClass('active');
            }
        });

       

    });
</script>