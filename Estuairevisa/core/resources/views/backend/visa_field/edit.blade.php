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
                        <form action="{{ route('admin.field.update',$field->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method("PUT")

                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Title") }}</label>
                                        <input type="text" name="title" value="{{$field->title}}" class="form-control"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Label") }}</label>
                                        <input type="text" name="label" class="form-control" value="{{$field->label}}"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Name") }}</label>
                                        <input type="text" name="name" readonly class="form-control"
                                            value="{{$field->name}}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Status") }}</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1" {{$field->status ? 'selected' : ''}}>{{__("Active")}}
                                            </option>
                                            <option value="0" {{!$field->status ? 'selected' : ''}}>{{__("Inactive")}}
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Icon") }}</label>
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __("Short Description") }}</label>
                                        <input type="text" name="short_description" class="form-control"
                                            value="{{$field->short_description}}" required>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary float-right">
                                        <i class="fas fa-save"></i> {{ __("Save Field") }}
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
<script>
    'use strict'

        $("input[name=label]").on('input', function () {
            let label = $(this).val();
            let name = label
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '_') 
                .replace(/^_+|_+$/g, ''); 

            $("input[name=name]").val(name);
        });
</script>
@endpush