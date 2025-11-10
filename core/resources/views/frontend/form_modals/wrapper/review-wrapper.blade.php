<div class="application-slide review-slide">
    <div class="application-slide-dialog">
        <div class="application-slide-content">
            <div class="application-slide-body">
                <div class="visa-progress-area">
                    <div id="document-progress" class="single-progress">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Documents') }}</p>
                    </div>
                    <div id="details-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Details') }}</p>
                    </div>
                    <div id="review-progress" class="single-progress active">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Review') }}</p>
                    </div>
                    <div id="checkout-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Checkout') }}</p>
                    </div>
                </div>
        
                <div class="mt-sm-5 mt-4">
                    <div id="review-wrapper">
                        <div class="d-flex flex-wrap align-items-center gap-2 justify-content-sm-between justify-content-center mb-sm-5 mb-3">
                            <p class="text-xl text-uppercase text-primary mb-0 plan_name">{{ $plan->title }}</p>
                            <p class="mb-0 d-flex align-items-center gap-2 justify-content-end">{{ __('Price') }}: <b
                                    class="text-dark text-xl plan_price">{{ number_format($plan->price, 2)." ".$general->site_currency }}</b></p>
                        </div>
        
                        <div class="row gy-sm-4 gy-3">
                            <div class="col-lg-6">
                                <label class="form-label">{{__("Change Visa Type?")}}</label>
                                <select name="plan_id" class="form-select bg-white">
                                    @foreach($plans as $item)
                                    <option value="{{$item->id}}" {{$item->id == $plan->id ? 'selected' : ''}}
                                        data-action="{{route('visa.plan.change',$item->id)}}" data-duration="{{$item->heading}}" data-title="{{$item->title}}" data-description="{{$item->short_description}}">{{$item->title}}
                                        - {{ number_format($item->price,2)." ".$general->site_currency }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="">
                                    <ul class="list-group">
                                        
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Plan Name")}}:</span>
                                            <span class="visa_title">{{$plan->title}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Description")}}:</span>
                                            <span class="visa_short_description">{{$plan->short_description}}</span>
                                        </li>
                                            <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Duration")}}:</span>
                                            <span class="visa_duration">{{$plan->heading}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Name")}}:</span>
                                            <span>{{$data['personal_info']['first_name'].'
                                                '.$data['personal_info']['last_name']}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Contact Number")}}:</span>
                                            <span>{{$data['personal_info']['phone_number']}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Email Address")}}:</span>
                                            <span>{{$data['personal_info']['email_address']}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("From")}}:</span>
                                            <span>{{$from->name}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Live in")}}:</span>
                                            <span>{{$live->name}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Passport No")}}:</span>
                                            <span>{{$data['personal_info']['passport_number']}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Profession")}}:</span>
                                            <span>{{$data['personal_info']['profession']}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Travel Date")}}:</span>
                                            <span>{{$data['personal_info']['travel_date']}}</span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between gap-2">
                                            <span class="fw-semibold">{{__("Purpose of Travel")}}</span>
                                            <span>{{$data['personal_info']['travel_purpose']}}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
        
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-sm-5 mt-3">
                            <a href="{{route('home')}}" class="btn btn-md btn-outline-secondary cancel-btn">{{__("Cancel")}}</a>
                            <button type="button" id="checkout-btn"
                                class="btn btn-md btn-primary checkout_btn">{{__("Checkout")}} <i
                                    class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(function(){

        'use strict'

        $(".checkout_btn").on('click',function(){

            $.ajax({
                url : "{{route('visa.applay.checkout')}}",
                method : 'get',
                success: function(response){
                    if(response.status){
                        $('.modal').modal('hide');
                        $('.modal-backdrop').hide();

                        $('.application-slide').removeClass('active');
                        $('.root_modal').html(response.html);
                        $('.root_modal .checkout-slide').addClass('active');
                    }
                }
            })
        });


        $("select[name=plan_id]").on('change', function () {

            let selectedOption = $(this).find('option:selected');
            let action = selectedOption.data('action');
            let title = selectedOption.data('title');
            let description = selectedOption.data('duration');
            let duration = selectedOption.data('description');
            let currency = "{{$general->site_currency}}";

            if (!action) return;

            $.ajax({
                url: action,
                method: 'GET',
                success: function (response) {
                    if (response.status) {

                         iziToast.success({
                            message: response.message || 'Something went wrong.',
                            position: 'topRight'
                        });
                        $('.plan_name').text(`${response.plan_name}`)
                        $('.plan_price').text(`${response.plan_price} ${currency}`)
                        $('.visa_title').text(`${title}`)
                        $('.visa_short_description').text(`${description}`)
                        $('.visa_duration').text(`${duration}`)
                    } else {
                        iziToast.error({
                            message: response.message || 'Something went wrong.',
                            position: 'topRight'
                        });
                    }
                },
                error: function () {
                    iziToast.error({
                            message: 'Something went wrong. Please try again.',
                            position: 'topRight'
                        });
                }
            });
        });

    })
</script>