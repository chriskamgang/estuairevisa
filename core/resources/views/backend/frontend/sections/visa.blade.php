@php
$plans = \App\Models\Plan::active()->get();
$singlePlans = $plans->where('plan_type', 'single_entry');
$multiPlans = $plans->where('plan_type', 'multiple_entry');
$countries = \App\Models\Country::active()->get();
$content = content('visa.content');
@endphp

<div class="visa-section">
    <visa-section>
        <div class="container non-editable-area" data-gjs-stylable='false' data-gjs-draggable='false'
            data-gjs-editable='false' data-gjs-removable='false'
            data-gjs-propagate='["removable","editable","draggable","stylable"]'>
            {{-- Section de recherche désactivée --}}
            {{-- <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="visa-form-wrapper">
                        <div class="single-find-field">
                            <div class="icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="visa-field">
                                <span>{{__("I am from")}}</span>
                                <input type="text" name="i_am_from" value="" autocomplete="off">
                                <ul class="visa-country-list scroll-y">
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

                                <ul class="visa-country-list scroll-y">
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
                        <button class="visa-find-btn"><i class="fa-solid fa-magnifying-glass"></i> Find Now</button>
                    </div>
                </div>
            </div> --}}
            <div class="visa-package-wrapper">
                <div class="row justify-content-center">
                    <div class="col-lg-7 text-center">
                        <h2 class="section-title">{{__(@$content->data->title)}}</h2>
                        <p>{{__(@$content->data->subtitle)}}</p>

                        <ul class="nav nav-tabs justify-content-center" id="visaTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="single-tab" data-bs-toggle="tab"
                                    data-bs-target="#single" type="button" role="tab" aria-controls="single"
                                    aria-selected="true">
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
                                    <h4 class="price">{{ number_format($plan->price, 1).' '.$general->site_currency}}
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
    </visa-section>
</div>