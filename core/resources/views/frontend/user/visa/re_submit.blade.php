@extends('frontend.layout.master2')
@section('content2')
<div class="card mt-4">
    <div class="card-header d-flex flex-wrap gap-3 justify-content-between align-items-center gap-2">
        <h5 class="mb-0">{{ __($pageTitle) }}</h5>
        <a href="{{ route('user.visa.all') }}" class="btn btn-sm btn-secondary">{{ __("Back to Applications") }}</a>
    </div>
    @php
    $info = $visa->personal_info;
    @endphp
    <div class="card-body">
        <form method="post" enctype="multipart/form-data">
            @csrf
            <div class="row gy-4">
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-telephone"></i>
                        <input type="text" name="phone_number" value="{{$info->phone_number}}"
                            placeholder="{{ __(' Phone Number') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-envelope"></i>
                        <input type="text" name="email_address" value="{{$info->email_address}}"
                            placeholder="{{ __(' Email address') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-person"></i>
                        <input type="text" name="first_name" placeholder="{{ __(' First name') }}"
                            value="{{$info->first_name}}" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-person"></i>
                        <input type="text" name="last_name" placeholder="{{ __(' Last Name') }}"
                            value="{{$info->last_name}}" autocomplete="off">
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-flag"></i>
                        <input type="text" name="from" value="{{ $info->from_country }}" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-globe-americas"></i>
                        <input type="text" name="live" value="{{ $info->live_country }}" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-passport"></i>
                        <input type="text" name="passport_number" value="{{$info->passport_number}}"
                            placeholder="{{ __(' Passport number') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-briefcase"></i>
                        <input type="text" name="profession" value="{{$info->profession}}"
                            placeholder="{{ __(' Profession') }}" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-calendar4-week"></i>
                        <input type="date" name="travel_date" value="{{$info->travel_date}}"
                            placeholder="{{ __(' Travel date') }}" autocomplete="off" min="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="visa-icon-field">
                        <i class="bi bi-suitcase"></i>
                        <input type="text" name="travel_purpose" value="{{$info->travel_purpose}}" placeholder="{{ __(' Travel purpose') }}"
                            autocomplete="off">
                    </div>
                </div>

            </div>

            <h4 class="my-4">{{__("Files")}}</h4>
            <div class="row">
                @foreach($fields as $k => $field)
                <div class="col">
                    <div class="upload-system-item">

                        <p>{{ $field->label }}</p>

                        <div class="file-show-list">
                            @php
                            $file = property_exists($visa->files, $field->name) ? $visa->files->{$field->name} : null;
                            $fileUrl = $file ? getFile('visa_document', $file) : null;
                            $ext = $file ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : null;
                            @endphp

                            @if($file)
                            <div class="uploaded-file-preview mb-2">
                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <img src="{{ $fileUrl }}" alt="Uploaded Image"
                                    style="max-width: 100%; max-height: 200px;" class="rounded shadow-sm">
                                @elseif($ext === 'pdf')
                                <iframe src="{{ $fileUrl }}"
                                    style="width: 100%; height: 200px; border: 1px solid #ddd;"></iframe>
                                @else
                                <p class="text-muted">Uploaded file: <a href="{{ $fileUrl }}" target="_blank">{{ $file
                                        }}</a></p>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <div class="text-end">
                            <input type="file" name="documents[{{ $field->name }}]" id="file-upload-{{ $k }}"
                                class="file-upload-field" accept="image/*,application/pdf">
                            <label for="file-upload-{{ $k }}" class="text-primary" style="cursor: pointer;">
                                <i class="fa-solid fa-upload"></i>
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>

            <button type="submit" class="btn btn-primary">{{__("Submit")}}</button>
        </form>
    </div>
</div>
@endsection


@push('script')
<script>
    $(function () {
        'use strict';

        // File preview on upload
        $(document).on('change', '.file-upload-field', function () {
            const files = this.files;
            const $item = $(this).closest('.upload-system-item');
            const $previewList = $item.find('.file-show-list');
            const $label = $item.find(`label[for="${$(this).attr('id')}"]`);
            const $previewImg = $item.find('.upload-system-item-img');

            $previewList.empty(); // Clear existing preview

            const existingFiles = new Set();

            Array.from(files).forEach(file => {
                if (existingFiles.has(file.name)) return;
                existingFiles.add(file.name);

                const reader = new FileReader();
                reader.onload = function (e) {
                    const isImage = file.type.startsWith('image/');
                    const isPDF = file.type === 'application/pdf';
                    let previewHtml = '';

                    if (isImage) {
                        previewHtml = `<img src="${e.target.result}" class="img-thumbnail mb-1" style="max-width: 100px;">`;
                    } else if (isPDF) {
                        previewHtml = `<iframe src="${e.target.result}" style="width: 100%; height: 150px; border: 1px solid #ddd;"></iframe>`;
                    } else {
                        previewHtml = `<i class="fa fa-file fa-3x text-muted"></i>`;
                    }

                    $previewList.append(`
                        <div class="file-item mb-2">
                            ${previewHtml}
                            <span class="d-block small">${file.name}</span>
                            <button type="button" class="delete-btn btn btn-sm btn-danger mt-1">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `);

                    $previewImg.hide();
                    $label.hide();
                };
                reader.readAsDataURL(file);
            });
        });

        // Remove selected preview
        $(document).on('click', '.delete-btn', function () {
            const $fileItem = $(this).closest('.file-item');
            const $uploadItem = $fileItem.closest('.upload-system-item');
            const $input = $uploadItem.find('.file-upload-field');
            const $label = $uploadItem.find(`label[for="${$input.attr('id')}"]`);
            const $img = $uploadItem.find('.upload-system-item-img');

            $fileItem.remove();

            if (!$uploadItem.find('.file-item').length) {
                $img.show();
                $label.show();
                $input.val('');
            }
        });
    });
</script>
@endpush