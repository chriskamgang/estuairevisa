@extends('backend.layout.master')

@section('content')
<div class="main-content">
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center gap-2">
            <h5 class="mb-0">{{ __("Visa Application Details") }}</h5>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="text-md">{{ __("Track Number") }}</h6>
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
                    <h6 class="text-md">{{ __("Duration") }}</h6>
                    <p>{{ $visa->plan->heading }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-md">{{ __("Status") }}</h6>
                    <p>
                        <?= $visa->status() ?>
                    </p>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-md">{{ __("Applied for") }}</h6>
                    <p>{{ $visa->plan->country->name }}</p>
                </div>
            </div>

            <hr>

            <h5 class="mb-3">{{__("Personal Info")}}</h5>
            <div class="row mb-3">
                @foreach($visa->personal_info as $name=>$value)
                <div class="col-md-6">
                    <h6 class="text-md">{{ ucwords(str_replace('_',' ',$name)) }}</h6>
                    <p>{{ $value }}</p>
                </div>
                @endforeach
            </div>


            <h5 class="mb-3">{{ __("Uploaded Files") }}</h5>
            
            <div class="row">
            @forelse ($visa->files as $file)
                @php
                    $file_name = $file;
                    $extension = explode(".",$file)[1];
                    $type = in_array(strtolower($extension),['jpg', 'jpeg', 'png', 'gif', 'webp']) ? "image" : "pdf";
                    
                @endphp
               
                <div class="col-md-3 mb-3">
                    <div class="card">
                        @if ($type == "image")
                            <img src="{{ getFile('visa_document', $file_name) }}" class="card-img-top" alt="Uploaded image">
                            
                            <a href="{{ route('admin.visa.download', $file_name) }}"
                                class="btn btn-sm btn-outline-primary w-100 mt-3">
                                <i class="bi bi-download me-1"></i> {{ __("Download") }}
                            </a>
                        @else
                            @php 
                                $path = asset(filePath('visa_document')."/".$file_name);
                            @endphp
                            <div class="p-3 text-center">
                                <a href="{{$path}}" target="_blank">
                                    <i class="fas fa-file-alt fa-3x text-muted mb-2"></i>
                                    <div>{{ __("Download") }}</div>
                                </a>
                            </div>
                        @endif
                        
                        
                    </div>
                </div>
            @empty
                <p class="text-muted">{{ __("No files uploaded.") }}</p>
            @endforelse

        </div>

            <hr>

            <h5 class="mb-3">{{ __("Additional Info") }}</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>{{ __("Created At") }}:</strong> {{
                    $visa->created_at->format('Y-m-d') }}</li>
                <li class="list-group-item"><strong>{{ __("Updated At") }}:</strong> {{
                    $visa->updated_at->format('Y-m-d') }}</li>

            </ul>
        </div>
    </div>
</div>



@endsection