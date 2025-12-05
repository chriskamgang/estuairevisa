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
                        <form method="post" action="" enctype="multipart/form-data">
                            @csrf
                            <a href="{{ route('admin.frontend.section.manage', request()->name) }}"
                                class="btn btn-primary m-3"> <i class="fas fa-arrow-left"></i> {{ __('Go back') }}</a>
                            <div class="card-body">

                                <!-- Translation Tabs -->
                                <ul class="nav nav-pills mb-4" id="translationTab" role="tablist">
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
                                        <h5 class="mb-3">{{ __('English Content') }}</h5>
                                        <div class="row">
                                            @foreach ($section as $key => $sec)
                                                @if ($key == 'is_category')
                                            <div class="form-group col-md-6">

                                                <label for="">{{ __('Category Name') }}</label>
                                                <select name="category" class="form-control">

                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ $element->category == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @elseif($sec == 'text')
                                            <div class="form-group col-md-6">

                                                <label for="">{{ __(frontendFormatter($key)) }}</label>
                                                <input type="{{ $sec }}" name="{{ $key }}"
                                                    class="form-control" value="{{ $element->data->$key ?? '' }}">
                                            </div>
                                        @elseif($sec == 'file')
                                            <div class="form-group col-md-3 mb-3">
                                                <label class="col-form-label">{{ __(frontendFormatter($key)) }}</label>

                                                <div id="image-preview" class="image-preview"
                                                    style="background-image:url({{ getFile($name, $element->data->$key) }});">
                                                    <label for="image-upload"
                                                        id="image-label">{{ __('Choose File') }}</label>
                                                    <input type="{{ $sec }}" name="{{ $key }}"
                                                        id="image-upload" />
                                                </div>
                                            </div>
                                        @elseif($sec == 'textarea')

                                            <div class="form-group col-md-12">

                                                <label for="">{{ __(frontendFormatter($key)) }}</label>
                                                <textarea name="{{ $key }}"
                                                    class="form-control">{{ clean($element->data->$key ?? old($key)) }}</textarea>
                                            </div>

                                        @elseif($sec == 'textarea_nic')

                                            <div class="form-group col-md-12">

                                                <label for="">{{ __(frontendFormatter($key)) }}</label>
                                                <textarea name="{{ $key }}"
                                                    class="form-control summernote">{{ clean($element->data->$key ?? old($key)) }}</textarea>

                                            </div>

                                        @elseif($sec == 'icon')

                                            <div class="form-group col-md-6">
                                                <div class="input-group">
                                                    <label for=""
                                                        class="w-100">{{ __(frontendFormatter($key)) }}</label>
                                                    <input type="text" class="form-control icon-value"
                                                        name="{{ $key }}" value="{{ $element->data->$key }}">
                                                    <span class="input-group-append">
                                                        <button class="btn btn-outline-secondary iconpicker"
                                                            data-icon="fas fa-home" role="iconpicker"></button>
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                        </div>
                                    </div>

                                    <!-- French Tab -->
                                    <div class="tab-pane fade" id="fr" role="tabpanel">
                                        <h5 class="mb-3">{{ __('French Translation') }}</h5>
                                        <div class="row">
                                            @foreach ($section as $key => $sec)
                                                @if ($sec == 'text')
                                                    <div class="form-group col-md-6">
                                                        <label for="">{{ __(frontendFormatter($key)) }} (FR)</label>
                                                        <input type="text" name="translations[fr][{{ $key }}]"
                                                            class="form-control" value="{{ $element->translations['fr'][$key] ?? '' }}">
                                                    </div>
                                                @elseif($sec == 'textarea')
                                                    <div class="form-group col-md-12">
                                                        <label for="">{{ __(frontendFormatter($key)) }} (FR)</label>
                                                        <textarea name="translations[fr][{{ $key }}]"
                                                            class="form-control">{{ $element->translations['fr'][$key] ?? '' }}</textarea>
                                                    </div>
                                                @elseif($sec == 'textarea_nic')
                                                    <div class="form-group col-md-12">
                                                        <label for="">{{ __(frontendFormatter($key)) }} (FR)</label>
                                                        <textarea name="translations[fr][{{ $key }}]"
                                                            class="form-control summernote-fr">{{ $element->translations['fr'][$key] ?? '' }}</textarea>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="form-group col-md-12">
                                        <button type="submit"
                                            class="form-control btn btn-primary">{{ __('Update') }}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')

    <script>
        $(function() {
            'use strict'
            $('.summernote, .summernote-fr').summernote();
            $('.iconpicker').iconpicker();

            $('.iconpicker').on('change', function(e) {
                $('.icon-value').val(e.icon)
            })

            $.uploadPreview({
                input_field: "#image-upload", // Default: .image-upload
                preview_box: "#image-preview", // Default: .image-preview
                label_field: "#image-label", // Default: .image-label
                label_default: "{{ __('Choose File') }}", // Default: Choose File
                label_selected: "{{ __('Upload File') }}", // Default: Change File
                no_label: false, // Default: false
                success_callback: null // Default: null
            });
        })
    </script>
@endpush
