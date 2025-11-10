@extends('frontend.layout.master')
@section('frontend_content')

@php
$breadcrumb = content('breadcrumb.content');
@endphp

<section class="breadcrumbs"
    style="background-image: url({{ getFile('breadcrumb', @$breadcrumb->data->backgroundimage) }});">
    <div class="container">
        <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center text-capitalize">
            <h2>{{ __("Visa Details") }}</h2>
            <ol>
                <li><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li>{{ __("Visa Details") }}</li>
            </ol>
        </div>
    </div>
</section>

<div class="track-section py-100">
    <div class="container">

        <h3 class="mb-4 text-center">{{ __("Track Visa") }}</h3>

        <form method="GET" action="{{ route('visa.track') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="order_number" class="form-control" placeholder="{{ __('Enter Order Number') }}"
                    value="{{ request('order_number') }}">
                <button class="btn btn-primary" type="submit">{{ __('Track') }}</button>
            </div>
        </form>

        <!-- Result Section -->
        @if(request('order_number'))
        @if($visa)

        @php
        $statusSteps = [
        'pending' => 'Pending',
        'under_review' => 'Under Review',
        'proccessing' => 'Processing',
        'issues' => 'Issues',
        'complete' => 'Completed',
        'shipped' => 'Shipped'
        ];

        $currentStatus = $visa->status;
        $statusReached = false;
        @endphp

        <div class="main-content">
            <div class="container mt-4">
                <div class="step-tracker">
                    @if ($currentStatus === 'reject')
                    <div class="step rejected text-center">
                        <div class="step-icon text-danger"><i class="fas fa-times fa-2x"></i></div>
                        <div class="step-label text-danger mt-2">{{__("Rejected")}}</div>
                    </div>
                    @elseif($currentStatus == 'draft')
                    <div class="step rejected text-center">
                        <div class="step-icon text-warning"><i class="fas fa-times fa-2x"></i></div>
                        <div class="step-label text-warning mt-2">{{__("Draft")}}</div>
                    </div>
                    @else
                    @foreach ($statusSteps as $key => $label)
                    @php
                    $class = '';
                    if ($key === $currentStatus) {
                    $class = 'active';
                    $statusReached = true;
                    } elseif (!$statusReached) {
                    $class = 'completed';
                    }
                    @endphp

                    <div class="step {{ $class }}">
                        <div class="step-icon">{{ $loop->iteration }}</div>
                        <div class="step-label">{{ $label }}</div>
                        @if (!$loop->last)
                        <div class="arrow-right"></div>
                        @endif
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            
            <div class="card border mt-4">
                <div class="card-header">
                    <h5 class="mb-0 h4">{{ __("Visa Application Details") }}</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-sm-4 gy-2">
                        <div class="col-md-6">
                            <h6>{{ __("Track Number") }}</h6>
                            <p>{{ $visa->order_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __("Plan") }}</h6>
                            <p>{{ $visa->plan->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __("Duration") }}</h6>
                            <p>{{ $visa->plan->heading }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __("Description") }}</h6>
                            <p>{{ $visa->plan->short_description }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __("Price") }}</h6>
                            <p>{{ number_format($visa->price, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __("Status") }}</h6>
                            <p>{!! $visa->status() !!}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>{{ __("Applied for") }}</h6>
                            <p><span class="badge badge-primary text-md">{{ isset($visa->personal_info->destination_country) ? \App\Models\Country::find($visa->personal_info->destination_country)->name ?? 'N/A' : 'N/A' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card border mt-4">
                <div class="card-header">
                    <h5 class="mb-0 h4">{{ __("Personal Info") }}</h5>
                </div>
                <div class="card-body">
                    <div class="row gy-sm-4 gy-2">
                        @foreach($visa->personal_info as $name => $value)
                        <div class="col-md-6">
                            <h6>{{ ucwords(str_replace('_', ' ', $name)) }}</h6>
                            <p>{{ $value }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card border mt-4">
                <div class="card-header">
                    <h5 class="mb-0 h4">{{ __("Additional Info") }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>{{ __("Created At") }}:</strong> {{ $visa->created_at->format('Y-m-d') }}
                        </li>
                        <li class="list-group-item">
                            <strong>{{ __("Updated At") }}:</strong> {{ $visa->updated_at->format('Y-m-d') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        @else
        <div class="alert alert-warning mt-3">
            {{ __("No data found for Order Number") }} <strong>{{ request('order_number') }}</strong>.
        </div>
        @endif
        @endif

    </div>
</div>


@push('style')
<style>
    .step-tracker {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
    }


    .step {
        position: relative;
        text-align: center;
        padding: 10px;
    }

    .step-icon {
        width: 30px;
        height: 30px;
        background: #ccc;
        border-radius: 50%;
        line-height: 30px;
        color: white;
        margin: auto;
    }

    .step.completed .step-icon {
        background: var(--success);
    }

    .step.active .step-icon {
        background: var(--warning);
    }

    .step-label {
        margin-top: 5px;
        font-weight: 600;
    }

    .arrow-right {
        width: 30px;
        height: 2px;
        background: #ccc;
        margin: 0 10px;
        align-self: center;
    }
    
    .track-section .card h6 {
        font-weight: 700;
    }
    
    .track-section .card p {
        color: #777;
    }
    
    @media (max-width: 575px) {
        .step-tracker {
            justify-content: flex-start;
        }
        
        .step {
            width: calc(100% / 3);
        }
        
        .step-label {
            font-size: 12px;
        }
        
        .step-icon {
            width: 26px;
            height: 26px;
            line-height: 26px;
            font-size: 13px;
        }
    }
</style>
@endpush

@endsection