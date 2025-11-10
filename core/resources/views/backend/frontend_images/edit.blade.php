@extends('backend.layout.master')

@section('content')
    <div class="main-content">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ __('Edit Image') }}: {{ $image->label }}</h4>
                <a href="{{ route('admin.frontend.images.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.frontend.images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="label">{{ __('Label') }} <span class="text-danger">*</span></label>
                                <input type="text" name="label" id="label" class="form-control"
                                       value="{{ old('label', $image->label) }}" required>
                                @error('label')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $image->description) }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>{{ __('Section') }}</label>
                                <input type="text" class="form-control" value="{{ $image->section ?? 'General' }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Key') }}</label>
                                <input type="text" class="form-control" value="{{ $image->key }}" readonly>
                                <small class="form-text text-muted">{{ __('This is the unique identifier for this image') }}</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image">{{ __('Image') }}</label>
                                <div class="mb-3">
                                    <img src="{{ getFile('frontend', $image->image) }}"
                                         alt="{{ $image->label }}"
                                         class="img-thumbnail"
                                         style="max-width: 100%; height: auto;">
                                </div>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <small class="form-text text-muted">
                                    {{ __('Allowed formats: JPG, PNG, WEBP, SVG') }}<br>
                                    {{ __('Max size: 4MB') }}
                                </small>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> {{ __('Update Image') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    // Preview image before upload
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.img-thumbnail').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
