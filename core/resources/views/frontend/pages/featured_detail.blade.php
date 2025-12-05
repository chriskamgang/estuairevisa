@extends('frontend.layout.master')
@section('frontend_content')

@php
    $breadcrumb = content('breadcrumb.content');
@endphp

<section class="breadcrumbs" style="background-image: url({{ getFile('breadcrumb', @$breadcrumb->data->backgroundimage) }});">
    <div class="container">
        <div class="d-flex flex-wrap gap-3 flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
            <h2 class="mb-0">{{ __($pageTitle ?? '') }}</h2>
            <ol>
                <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li>{{ __($pageTitle ?? '') }}</li>
            </ol>
        </div>
    </div>
</section>

<section class="featured-detail-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white py-4">
                        <div class="d-flex align-items-center">
                            <div class="featured-icon-wrapper me-3">
                                <img src="{{ getFile('featured', $featured->data->image) }}"
                                     alt="{{ translate($featured, 'title') }}"
                                     class="featured-header-image">
                            </div>
                            <div>
                                <h3 class="mb-0 text-white">{{ translate($featured, 'title') }}</h3>
                                @if(translate($featured, 'short_description'))
                                    <p class="mb-0 mt-2 text-white-50">{{ translate($featured, 'short_description') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="featured-content">
                            {!! clean(translate($featured, 'description') ?: '') !!}
                        </div>

                        <div class="mt-5 text-center">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-arrow-left me-2"></i> {{ __('Retour à l\'accueil') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.featured-detail-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.featured-icon-wrapper {
    width: 80px;
    height: 80px;
    background: white;
    border-radius: 12px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.featured-header-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.featured-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.featured-content h1,
.featured-content h2,
.featured-content h3,
.featured-content h4 {
    color: #2c3e50;
    margin-top: 30px;
    margin-bottom: 20px;
    font-weight: 600;
}

.featured-content h2 {
    font-size: 1.8rem;
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
}

.featured-content h3 {
    font-size: 1.5rem;
    color: #007bff;
}

.featured-content ul,
.featured-content ol {
    margin: 20px 0;
    padding-left: 30px;
}

.featured-content li {
    margin-bottom: 12px;
}

.featured-content p {
    margin-bottom: 15px;
}

.featured-content img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 25px 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.featured-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 25px 0;
    background: white;
}

.featured-content table th,
.featured-content table td {
    padding: 15px;
    border: 1px solid #dee2e6;
    text-align: left;
}

.featured-content table th {
    background-color: #007bff;
    color: white;
    font-weight: 600;
}

.featured-content table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.featured-content blockquote {
    border-left: 5px solid #007bff;
    padding-left: 25px;
    margin: 25px 0;
    color: #555;
    font-style: italic;
    background: #f8f9fa;
    padding: 20px 20px 20px 25px;
    border-radius: 5px;
}

.featured-content strong {
    color: #2c3e50;
    font-weight: 600;
}

.card {
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border: none;
}

@media print {
    .card-header .featured-icon-wrapper,
    .btn {
        display: none !important;
    }

    .featured-content {
        font-size: 12pt;
    }
}

@media (max-width: 768px) {
    .featured-icon-wrapper {
        width: 60px;
        height: 60px;
    }

    .featured-content {
        font-size: 1rem;
    }

    .featured-content h2 {
        font-size: 1.5rem;
    }

    .card-body {
        padding: 20px !important;
    }
}
</style>

@endsection
