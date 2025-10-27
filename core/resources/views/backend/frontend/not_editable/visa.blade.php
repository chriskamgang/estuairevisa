@php
$plans = \App\Models\Plan::active()->get();
$singlePlans = $plans->where('plan_type', 'single_entry');
$multiPlans = $plans->where('plan_type', 'multiple_entry');
$content = content('visa.content');
$countries = \App\Models\Country::active()->get();
@endphp


{{-- Section de recherche désactivée --}}
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="visa-form-wrapper">
                <div class="single-find-field">
                    <div class="icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div class="visa-field">
                        <span>{{__("I am from")}}</span>
                        <input type="text" name="i_am_from" value="" autocomplete="off">
                        <ul class="visa-country-list from-country-list scroll-y">
                            @forelse($countries as $country)
                            <li data-value="{{$country->name}}" data-id="{{$country->id}}">
                                <img src="{{getFile('country',$country->image)}}" alt="image">
                                <span>{{$country->name}}</span>
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
                <div class="single-find-field">
                    <div class="icon">
                        <i class="bi bi-house-door-fill"></i>
                    </div>
                    <div class="visa-field">
                        <span>{{__("I live in")}}</span>
                        <input type="text" name="i_live_in" value="" autocomplete="off">

                        <ul class="visa-country-list live-country-list scroll-y">
                            @forelse($countries as $country)
                            <li data-value="{{$country->name}}" data-id="{{$country->id}}">
                                <img src="{{getFile('country',$country->image)}}" alt="image">
                                <span>{{$country->name}}</span>
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
                <button class="visa-find-btn find_btn"><i class="fa-solid fa-magnifying-glass"></i> {{__("Find
                    Now")}}</button>
            </div>
        </div>
    </div> --}}

<div class="container">

    <div class="visa-package-wrapper" id="package_section">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <h2 class="section-title">{{__(@$content->data->title)}}</h2>
                <p>{{__(@$content->data->subtitle)}}</p>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs justify-content-center" id="visaTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="single-tab" data-bs-toggle="tab" data-bs-target="#single"
                            type="button" role="tab" aria-controls="single" aria-selected="true">
                            {{ __("Single") }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="multi-tab" data-bs-toggle="tab" data-bs-target="#multi"
                            type="button" role="tab" aria-controls="multi" aria-selected="false">
                            {{ __("Groupe visa") }}
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content mt-4" id="visaTabContent">
            <div class="tab-pane fade show active" id="single" role="tabpanel" aria-labelledby="single-tab">
                <div class="row gy-4 mt-4 justify-content-center align-items-center">
                    @forelse($singlePlans as $plan)
                    <div class="col-lg-4 col-sm-6">
                        <div class="visa-package" {{$plan->is_recommended ? "data-title=Recommended" : ''}}>
                            <h3 class="title mb-0">{{ $plan->title }}</h3>
                            <p>{{ $plan->heading }}</p>
                            <span class="visa-package-country">{{ __("Visa for") }}: {{$plan->country->name}}</span>
                            <h4 class="price">{{ number_format($plan->price, 2).' '.$general->site_currency}}
                            </h4>
                            <p>{{$plan->short_description}}</p>
                            <button type="button" class="package-btn fetch-country-btn"
                                data-plan="{{route('visa.applay.start',$plan->id)}}">{{ __("Get Started")
                                }}</button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center">
                        <p>{{ __("No single entry packages found.") }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="multi" role="tabpanel" aria-labelledby="multi-tab">
                <div class="row gy-4 mt-4 align-items-center">
                    @forelse($multiPlans as $plan)
                    <div class="col-lg-4 col-sm-6">
                        <div class="visa-package" {{$plan->is_recommended ? "data-title=Recommended" : ''}}>
                            <h3 class="title mb-0">{{ $plan->title }}</h3>
                            <p>{{ $plan->heading }}</p>
                            <span class="visa-package-country">{{ __("Visa for") }}: {{$plan->country->name}}</span>
                            <h4 class="price">{{ number_format($plan->price, 2).' '.$general->site_currency }}
                            </h4>
                            <p>{{$plan->short_description}}</p>
                            <button type="button" class="package-btn fetch-country-btn"
                                data-plan="{{route('visa.applay.start',$plan->id)}}">{{ __("Get Started")
                                }}</button>
                        </div>
                    </div>
                    @empty
                    <div class="text-center">
                        <p>{{ __("No multiple entry packages found.") }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    'use strict';


    $(function(){

         $(document).on('click', '.fetch-country-btn', async function () {
            const $btn = $(this);
            const url = $btn.data('plan');
        
            $btn.prop('disabled', true);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        
            try {
                const response = await $.get(url);
        
                if (!response.status) return;
        
                $('.modal').modal('hide');
                $('.aplication-slide').removeClass('active');
        
       
                $('#' + response.modal_name).remove();
        
                $('.root_modal').append(response.html);
                $('#' + response.modal_name).modal('show');
                
            } catch (error) {
                console.error('Fetch error:', error);
            } finally {
                $btn.prop('disabled', false);
                $btn.html(originalHtml);
            }
        });

        
        
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
        
        $('.visa-field input').on('click', function () {
            // Remove active from all lists first
            $('.visa-country-list').removeClass('active');
    
            // Add active only to the related list of this input
            $(this).siblings('.visa-country-list').addClass('active');
        });
    
        // Optional: Click outside to close all lists
        $(document).on('click', function (e) {
            if (!$(e.target).closest('.visa-field').length) {
                $('.visa-country-list').removeClass('active');
            }
        });

        // Handle "Next" button
        $(document).on('click', '.fetch-visa_info-btn', async function () {
            const $btn = $(this);
            const originalHtml = $btn.html(); // Save original button content
            const from = $("input[name=i_am_from]").data('id');
            const live = $("input[name=i_live_in]").data('id');

            if (!$("input[name=i_am_from]").val() || !$("input[name=i_live_in]").val()) {
                iziToast.error({
                    message: 'Please select both countries.',
                    position: 'topRight'
                });
                return;
            }

            // Disable button and show loading
            $btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm me-1"></span>Loading...`);

            try {
                const response = await $.get("{{ route('visa.applay.infos') }}", { from, live });

                if (!response.status) {
                    iziToast.error({
                        message: response.message || 'Something went wrong.',
                        position: 'topRight'
                    });
                    return;
                }

                $('.root_modal').html(response.html);
                $('#fileInfoModal').modal('show');
            } catch {
                iziToast.error({
                    message: 'Something went wrong. Please try again.',
                    position: 'topRight'
                });
            } finally {
                // Re-enable button and restore content
                $btn.prop('disabled', false).html(originalHtml);
            }
        });

        $(".find_btn").on('click', function () {
            var $btn = $(this); // Reference the button
            var originalText = $btn.html(); // Store original button text
        
            var from = $('.from-country-list li.active').data('id');
            var live = $('.live-country-list li.active').data('id');
        
            if (!from || !live) {
                 iziToast.error({
                            message: 'Please select both countries.',
                            position: 'topRight'
                        });
                        return false;
            }

    // Show loader on button
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...')
                .prop('disabled', true);

    $.ajax({
        url: "{{route('visa.country.search')}}",
        method: 'get',
        data: {
            from_id: from,
            to_id: live
        },
        success: function (response) {
                document.getElementById('package_section').scrollIntoView({
                    behavior: 'smooth'
                });
                
                $("#visaTabContent").html(response)
        },
        complete: function () {
            $btn.html(originalText).prop('disabled', false);
        }
    });
});
    });
</script>
@endpush