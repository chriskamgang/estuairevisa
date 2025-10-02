<div class="application-slide details-slide">
    <div class="application-slide-dialog">
        <div class="application-slide-content">
            <div class="application-slide-body">
                <div class="visa-progress-area">
                    <div id="document-progress" class="single-progress">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Documents') }}</p>
                    </div>
                    <div id="details-progress" class="single-progress active">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Details') }}</p>
                    </div>
                    <div id="review-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Review') }}</p>
                    </div>
                    <div id="checkout-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{ __('Checkout') }}</p>
                    </div>
                </div>
        
                <div class="mt-5">
                    <div id="details-wrapper">
        
                        <div class="d-flex flex-wrap align-items-center gap-2 justify-content-sm-between justify-content-center mb-sm-5 mb-4">
                            <p class="text-xl text-uppercase text-primary mb-0">{{ $plan->title }}   <span class="visa-package-country text-nowrap"> {{ __('Visa for:') }} {{$plan->country->name}}</span></p>
                            <p class="mb-0 d-flex align-items-center gap-2 justify-content-end">{{ __('Price') }}:
                            <b class="text-dark text-xl">{{ number_format($plan->price, 2).' '.$general->site_currency }}</b></p>
                        </div>
        
                        <h6 class="text-center mb-5">{{ __('Please Provide The Following Details') }}</h6>
        
                        <form method="post">
                            @csrf
                            <div class="row gy-4">
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Phone Number') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-telephone"></i>
                                        <input type="number" name="phone_number" placeholder="{{ __(' Phone Number') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Email address') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-envelope"></i>
                                        <input type="text" name="email_address" placeholder="{{ __(' Email address') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('First name') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-person"></i>
                                        <input type="text" name="first_name" placeholder="{{ __(' First name') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Last Name') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-person"></i>
                                        <input type="text" name="last_name" placeholder="{{ __(' Last Name') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
        
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('From') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-flag"></i>
                                        <input type="text" name="from" value="{{ $from->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Live in') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-globe-americas"></i>
                                        <input type="text" name="live" value="{{ $live->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Passport number') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-passport"></i>
                                        <input type="text" name="passport_number" placeholder="{{ __(' Passport number') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Profession') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-briefcase"></i>
                                        <input type="text" name="profession" placeholder="{{ __(' Profession') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Travel date') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-calendar4-week"></i>
                                        <input type="date" name="travel_date" placeholder="{{ __(' Travel date') }}"
                                            autocomplete="off" min="">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">{{ __('Travel purpose') }}</label>
                                    <div class="visa-icon-field">
                                        <i class="bi bi-suitcase"></i>
                                        <input type="text" name="travel_purpose" placeholder="{{ __(' Travel purpose') }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="d-flex align-items-center justify-content-center gap-3 mt-sm-5 mt-4">
                            <a href="{{route('home')}}" class="btn btn-md btn-outline-secondary">{{ __('Cancel') }}</a>
                            <button type="button" class="btn btn-md btn-primary fetch-review_btn">{{ __('Review') }} <i class="bi bi-chevron-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    'use strict'

    $(function() {
        $(document).off('click', '.fetch-review_btn').on('click', '.fetch-review_btn', async function(e) {
            e.preventDefault();

            let data = {
                phone_number: $('input[name="phone_number"]').val(),
                email_address: $('input[name="email_address"]').val(),
                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                from: $('input[name="from"]').val(),
                live: $('input[name="live"]').val(),
                passport_number: $('input[name="passport_number"]').val(),
                profession: $('input[name="profession"]').val(),
                travel_date: $('input[name="travel_date"]').val(),
                travel_purpose: $('input[name="travel_purpose"]').val(),
                _token : $("input[name='_token']").val()
            };

            try {
                $('.fetch-review_btn').prop('disabled', true).text('Submitting...');
                const response = await $.ajax({
                    url: "{{ route('visa.applay.info.submit') }}",
                    method: 'POST',
                    data: data
                });

                if (response.status) {
                    $('.modal').modal('hide');
                    $('.modal-backdrop').hide();

                    $('.application-slide').removeClass('active');
                    $('.root_modal').html(response.html);
                    $('.root_modal .review-slide').addClass('active');
                } else {
                    iziToast.error({
                        message: response.message || 'Please complete required fields',
                        position: 'topRight'
                    });
                }
            } catch {
                iziToast.error({
                    message: 'Upload failed. Please try again.',
                    position: 'topRight'
                });
            } finally {
                $('.fetch-review_btn').prop('disabled', false).html(
                    'Details <i class="bi bi-chevron-right"></i>');
            }
        });

     
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;

        $('input[name=travel_date]').attr('min', minDate);

    });
</script>