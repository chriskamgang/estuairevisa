@extends('backend.layout.master')

@section('content')
    <div class="main-content">
        <div class="manage-frontend-images">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Frontend Images Management') }}</h4>
                </div>

                <div class="card-body p-0 table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('Preview') }}</th>
                                <th>{{ __('Section') }}</th>
                                <th>{{ __('Label') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentSection = null;
                            @endphp
                            @forelse ($images as $image)
                                @if($currentSection !== $image->section)
                                    <tr class="table-secondary">
                                        <td colspan="5"><strong>{{ strtoupper($image->section ?? 'General') }}</strong></td>
                                    </tr>
                                    @php
                                        $currentSection = $image->section;
                                    @endphp
                                @endif
                                <tr>
                                    <td>
                                        <img src="{{ getFile('frontend', $image->image) }}"
                                             alt="{{ $image->label }}"
                                             style="max-width: 100px; max-height: 60px; object-fit: cover;">
                                    </td>
                                    <td><span class="badge badge-info">{{ $image->section ?? 'General' }}</span></td>
                                    <td>{{ $image->label }}</td>
                                    <td>{{ \Str::limit($image->description ?? 'N/A', 50) }}</td>
                                    <td>
                                        <a href="{{ route('admin.frontend.images.edit', $image->id) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="fa fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('No images available.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
