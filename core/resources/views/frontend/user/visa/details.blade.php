@extends('frontend.layout.master2')

@section('content2')
<div class="card border">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center gap-2">
        <h5 class="mb-0 h4">{{ __("Visa Application Details") }}</h5>
        <a href="{{ route('user.visa.all') }}" class="btn btn-sm btn-secondary">{{ __("Back to Applications") }}</a>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <h6 class="text-lg">{{ __("Track Number") }}</h6>
                <p>{{ $visa->order_number }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md">{{ __("Plan") }}</h6>
                <p>{{ $visa->plan->title }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md">{{ __("Price") }}</h6>
                <p>{{ number_format($visa->price, 2) }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md">{{ __("Status") }}</h6>
                <p><?= $visa->status() ?></p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md">{{ __("Note") }}</h6>
                <p>{{$visa->note ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md">{{ __("Duration") }}</h6>
                <p>{{$visa->plan->heading ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <h6 class="text-md">{{ __("Description") }}</h6>
                <p>{{$visa->plan->short_description ?? 'N/A' }}</p>
            </div>
                <div class="col-md-6">
                <h6 class="text-md">{{ __("Country") }}</h6>
                <p>{{$visa->plan->country->name}}</p>
            </div>
        </div>
    </div>
</div>

<div class="card border mt-4">
    <div class="card-header">
        <h5 class="mb-0 h4">{{__("Personal Info")}}</h5>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            @foreach($visa->personal_info as $name=>$value)
            <div class="col-md-6">
                <h6 class="text-md">{{ ucwords(str_replace('_',' ',$name)) }}</h6>
                <p>{{ $value }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card border mt-4">
    <div class="card-header">
        <h5 class="mb-0 h4">{{ __("Uploaded Files") }}</h5>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            @forelse ($visa->files as $file)
                @php

                    $extension = explode(".",$file)[1];
                    $type = in_array(strtolower($extension),['jpg', 'jpeg', 'png', 'gif', 'webp']) ? "image" : "pdf";
                    
                @endphp
            
                <div class="col-md-3">
                    <div class="card">
                        @if ($type == "image")
                            <img src="{{ getFile('visa_document', $file) }}" class="card-img-top" alt="Uploaded image">
                        @else
                            @php 
                                $path = asset(filePath('visa_document')."/".$file);
                            @endphp
                            <div class="p-3 text-center">
                                <a href="{{$path}}" target="_blank">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-2"></i>
                                    <div>{{ __("Download File") }}</div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted">{{ __("No files uploaded.") }}</p>
            @endforelse
        </div>
    </div>
</div>


<div class="card border mt-4">
    <div class="card-header">
        <h5 class="mb-0 h4">{{ __("Additional Info") }}</h5>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>{{ __("Created At") }}:</strong> {{ $visa->created_at->format('Y-m-d') }}</li>
            <li class="list-group-item"><strong>{{ __("Updated At") }}:</strong> {{ $visa->updated_at->format('Y-m-d') }}</li>
        </ul>
    </div>
</div>
@endsection
