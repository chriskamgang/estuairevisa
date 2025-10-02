@php
$singlePlans = $plans->where('plan_type', 'single_entry');
$multiPlans = $plans->where('plan_type', 'multiple_entry');
@endphp

<div class="tab-pane fade show active" id="single" role="tabpanel" aria-labelledby="single-tab">
    <div class="row gy-4 mt-4 justify-content-center align-items-center">
        @forelse($singlePlans as $plan)
        <div class="col-lg-4 col-sm-6">
            <div class="visa-package" {{$plan->is_recommended ? "data-title=Recommended" : ''}}>
                <h4 class="title mb-0">{{ $plan->title }}</h4>
                <p>{{ $plan->heading }}</p>
                        <span class="visa-package-country">{{ __("Visa for") }}: {{$plan->country->name}}</span>
                <h3 class="price">{{ number_format($plan->price, 2) . ' ' . $general->site_currency }}
                </h3>
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
                <h4 class="title mb-0">{{ $plan->title }}</h4>
                <p>{{ $plan->heading }}</p>
                        <span class="visa-package-country">{{ __("Visa for") }}: {{$plan->country->name}}</span>
                <h3 class="price">{{ number_format($plan->price, 2) . ' ' . $general->site_currency }}
                </h3>
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