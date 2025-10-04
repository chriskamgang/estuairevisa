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
                        <form action="{{ route('admin.plans.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <!-- Plan Type -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Plan Type") }}</label>
                                        <select name="plan_type" class="form-control selectric" required>
                                            <option value="single_entry">{{__("Single Entry")}}</option>
                                            <option value="multiple_entry">{{__("Multiple Entry")}}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Heading -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Heading") }}</label>
                                        <input type="text" name="heading" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Title -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Title") }}</label>
                                        <input type="text" name="title" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Short Description -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Short Description") }}</label>
                                        <input type="text" name="short_description" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Price") }}</label>
                                        <input type="number" step="0.01" min="0" name="price" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{__("Available Countries")}}</label>
                                        <select name="country_ids[]" id="" class="form-control multi-select" multiple>
                                            @foreach($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">{{__("For Country")}}</label>
                                        <select name="for_country"  class="form-control" required>
                                            <option>{{__("Select country")}}</option>
                                            @foreach($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Status") }}</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1">{{__("Active")}}</option>
                                            <option value="0">{{__("Inactive")}}</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Is Recommended -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Recommended") }}</label>
                                        <select name="is_recommended" class="form-control" required>
                                            <option value="1">{{__("Yes")}}</option>
                                            <option value="0">{{__("No")}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __("Save Plan") }}
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