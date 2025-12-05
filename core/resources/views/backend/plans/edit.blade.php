@extends('backend.layout.master')

@section('content')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __($pageTitle) }}</h1>
        </div>

        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Plan Type -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Plan Type") }}</label>
                                        <select name="plan_type" class="form-control selectric" required>
                                            <option value="single_entry" {{ $plan->plan_type === 'single_entry' ?
                                                'selected' : '' }}>{{ __("Single Entry") }}</option>
                                            <option value="multiple_entry" {{ $plan->plan_type === 'multiple_entry' ?
                                                'selected' : '' }}>{{ __("Multiple Entry") }}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Price") }}</label>
                                        <input type="number" step="0.01" min="0" name="price" class="form-control"
                                            value="{{ old('price', $plan->price) }}" required>
                                    </div>
                                </div>

                                <!-- Translation Tabs -->
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>{{ __("Translations") }}</h4>
                                        </div>
                                        <div class="card-body">
                                            <ul class="nav nav-pills mb-3" id="translationTab" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="en-tab" data-toggle="pill" href="#en" role="tab">
                                                        <i class="fas fa-flag-usa"></i> English
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="fr-tab" data-toggle="pill" href="#fr" role="tab">
                                                        <i class="fas fa-flag"></i> Français
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="tab-content" id="translationTabContent">
                                                <!-- English Tab -->
                                                <div class="tab-pane fade show active" id="en" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __("Title") }} (EN)</label>
                                                                <input type="text" name="title" class="form-control"
                                                                    value="{{ old('title', $plan->title) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __("Heading") }} (EN)</label>
                                                                <input type="text" name="heading" class="form-control"
                                                                    value="{{ old('heading', $plan->heading) }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ __("Short Description") }} (EN)</label>
                                                                <input type="text" name="short_description" class="form-control"
                                                                    value="{{ old('short_description', $plan->short_description) }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- French Tab -->
                                                <div class="tab-pane fade" id="fr" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __("Title") }} (FR)</label>
                                                                <input type="text" name="title_translations[fr]" class="form-control"
                                                                    value="{{ old('title_translations.fr', $plan->title_translations['fr'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>{{ __("Heading") }} (FR)</label>
                                                                <input type="text" name="heading_translations[fr]" class="form-control"
                                                                    value="{{ old('heading_translations.fr', $plan->heading_translations['fr'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>{{ __("Short Description") }} (FR)</label>
                                                                <input type="text" name="short_description_translations[fr]" class="form-control"
                                                                    value="{{ old('short_description_translations.fr', $plan->short_description_translations['fr'] ?? '') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{__("Available Countries")}}</label>
                                        <select name="country_ids[]" id="" class="form-control multi-select" multiple required>
                                            @foreach($countries as $country)
                                            <option value="{{$country->id}}" {{in_array($country->id,$plan->country_ids ??[])
                                                ? 'selected' : ''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Status") }}</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{ $plan->status ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ !$plan->status ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Is Recommended -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Recommended") }}</label>
                                        <select name="is_recommended" class="form-control" required>
                                            <option value="1" {{ $plan->is_recommended ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ !$plan->is_recommended ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __("Update Plan") }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@push('script')
<script src="{{ asset('asset/admin/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(function(){
            'use strict'

            $('.multi-select').select2({
            tags: false,
            tokenSeparators: [',', ' '],
            placeholder: "Select or type countries"
        });
        });
</script>
@endpush