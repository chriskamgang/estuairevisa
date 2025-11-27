@extends('backend.layout.master')
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __($pageTitle) }}</h1>
            </div>

            {{-- DeepL Usage Card --}}
            @if(isset($deeplUsage))
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>{{ __('DeepL API Usage') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="text-muted">{{ __('Characters Used') }}</h6>
                                        <h3 class="text-primary">{{ number_format($deeplUsage['character_count']) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="text-muted">{{ __('Characters Limit') }}</h6>
                                        <h3 class="text-info">{{ number_format($deeplUsage['character_limit']) }}</h3>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h6 class="text-muted">{{ __('Usage Percentage') }}</h6>
                                        <h3 class="text-{{ $deeplUsage['percentage_used'] > 80 ? 'danger' : 'success' }}">
                                            {{ $deeplUsage['percentage_used'] }}%
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 25px;">
                                <div class="progress-bar bg-{{ $deeplUsage['percentage_used'] > 80 ? 'danger' : 'success' }}"
                                     role="progressbar"
                                     style="width: {{ $deeplUsage['percentage_used'] }}%;"
                                     aria-valuenow="{{ $deeplUsage['percentage_used'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ $deeplUsage['percentage_used'] }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(isset($deeplError))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger">
                        <strong>{{ __('DeepL API Error:') }}</strong> {{ $deeplError }}
                    </div>
                </div>
            </div>
            @endif

            {{-- Configuration Card --}}
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('API Configuration') }}</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i>
                        <strong>{{ __('Note:') }}</strong>
                        {{ __('Get your DeepL API key from') }}
                        <a href="https://www.deepl.com/pro-api" target="_blank">https://www.deepl.com/pro-api</a>
                        <br>
                        {{ __('The Free API provides 500,000 characters per month. The Pro API requires a paid subscription.') }}
                    </div>

                    <form action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>{{ __('DeepL API Status') }}</label>
                                <select name="deepl_status" class="form-control" required>
                                    <option value="1" {{ $general->deepl_status == 1 ? 'selected' : '' }}>
                                        {{ __("Active") }}
                                    </option>
                                    <option value="0" {{ $general->deepl_status == 0 ? 'selected' : '' }}>
                                        {{ __("Inactive") }}
                                    </option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('Enable or disable DeepL automatic translation functionality') }}
                                </small>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('DeepL API Key') }}</label>
                                <input type="text"
                                       name="deepl_api_key"
                                       class="form-control"
                                       placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx:fx"
                                       value="{{ $general->deepl_api_key }}">
                                <small class="form-text text-muted">
                                    {{ __('Your DeepL authentication key. Free API keys end with ":fx"') }}
                                </small>
                            </div>

                            <div class="form-group col-md-6">
                                <label>{{ __('API Type') }}</label>
                                <select name="deepl_api_type" class="form-control" required>
                                    <option value="free" {{ $general->deepl_api_type == 'free' ? 'selected' : '' }}>
                                        {{ __("Free API") }}
                                    </option>
                                    <option value="pro" {{ $general->deepl_api_type == 'pro' ? 'selected' : '' }}>
                                        {{ __("Pro API") }}
                                    </option>
                                </select>
                                <small class="form-text text-muted">
                                    {{ __('Select "Free API" if your key ends with ":fx", otherwise select "Pro API"') }}
                                </small>
                            </div>

                            <div class="form-group col-md-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="mb-3">{{ __('How to use DeepL Translation:') }}</h6>
                                        <ul class="mb-0">
                                            <li>{{ __('Go to Language Management page') }}</li>
                                            <li>{{ __('Click the "Auto-Translate" button for any non-default language') }}</li>
                                            <li>{{ __('Or use the command line:') }} <code>php artisan language:translate {lang_code}</code></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary float-right">
                                    <i class="fa fa-save"></i> {{ __('Update Setting') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Supported Languages Card --}}
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Supported Languages') }}</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        {{ __('DeepL supports translation for the following languages:') }}
                    </p>
                    <div class="row">
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success"></i> Arabic (AR)</li>
                                <li><i class="fa fa-check text-success"></i> Bulgarian (BG)</li>
                                <li><i class="fa fa-check text-success"></i> Czech (CS)</li>
                                <li><i class="fa fa-check text-success"></i> Danish (DA)</li>
                                <li><i class="fa fa-check text-success"></i> German (DE)</li>
                                <li><i class="fa fa-check text-success"></i> Greek (EL)</li>
                                <li><i class="fa fa-check text-success"></i> English (EN)</li>
                                <li><i class="fa fa-check text-success"></i> Spanish (ES)</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success"></i> Estonian (ET)</li>
                                <li><i class="fa fa-check text-success"></i> Finnish (FI)</li>
                                <li><i class="fa fa-check text-success"></i> French (FR)</li>
                                <li><i class="fa fa-check text-success"></i> Hungarian (HU)</li>
                                <li><i class="fa fa-check text-success"></i> Indonesian (ID)</li>
                                <li><i class="fa fa-check text-success"></i> Italian (IT)</li>
                                <li><i class="fa fa-check text-success"></i> Japanese (JA)</li>
                                <li><i class="fa fa-check text-success"></i> Korean (KO)</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success"></i> Lithuanian (LT)</li>
                                <li><i class="fa fa-check text-success"></i> Latvian (LV)</li>
                                <li><i class="fa fa-check text-success"></i> Norwegian (NB)</li>
                                <li><i class="fa fa-check text-success"></i> Dutch (NL)</li>
                                <li><i class="fa fa-check text-success"></i> Polish (PL)</li>
                                <li><i class="fa fa-check text-success"></i> Portuguese (PT)</li>
                                <li><i class="fa fa-check text-success"></i> Romanian (RO)</li>
                                <li><i class="fa fa-check text-success"></i> Russian (RU)</li>
                            </ul>
                        </div>
                        <div class="col-md-3">
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success"></i> Slovak (SK)</li>
                                <li><i class="fa fa-check text-success"></i> Slovenian (SL)</li>
                                <li><i class="fa fa-check text-success"></i> Swedish (SV)</li>
                                <li><i class="fa fa-check text-success"></i> Turkish (TR)</li>
                                <li><i class="fa fa-check text-success"></i> Ukrainian (UK)</li>
                                <li><i class="fa fa-check text-success"></i> Chinese (ZH)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
