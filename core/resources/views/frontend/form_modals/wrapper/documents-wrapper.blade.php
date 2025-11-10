<div class="application-slide document-slide">
    <div class="application-slide-dialog">
        <div class="application-slide-content">
            <div class="application-slide-body">
                <div class="visa-progress-area">
                    <div id="document-progress" class="single-progress active">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{__("Documents")}}</p>
                    </div>
                    <div id="details-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{__("Details")}}</p>
                    </div>
                    <div id="review-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{__("Review")}}</p>
                    </div>
                    <div id="checkout-progress" class="single-progress ">
                        <span class="circle"><i class="bi bi-check2"></i></span>
                        <p class="mb-0">{{__("Checkout")}}</p>
                    </div>
                </div>
        
                <div class="mt-5">
                    <div id="documents-wrapper">
                        <div class="d-flex flex-wrap align-items-center gap-2 justify-content-sm-between justify-content-center mb-sm-5 mb-4">
                            <p class="text-xl text-uppercase text-primary mb-0">{{ $plan->title }}</p>
                            <p class="mb-0 d-flex align-items-center gap-2 justify-content-end">{{ __('Price') }}: <b
                                    class="text-dark text-xl">{{ number_format($plan->price, 2).' '.$general->site_currency }}</b></p>
                        </div>
        
                        <form method="post" enctype="multipart/form-data" id="documentForm">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-9">
                                    <h6 class="text-center">{{__("Please Upload the Following Documents")}}</h6>
                                    <div class="d-flex align-items-center justify-content-center gap-2 mt-sm-5 mt-4">
                                        <p class="mb-0">{{__("Accepted Formats")}}: <span class="text-primary">{{__("PDF, JPG or
                                                PNG")}}</span>
                                        </p>
                                    </div>
        
                                    <div class="row row-cols-xl-5 row-cols-lg-3 row-cols-2 gy-4 mt-2 justify-content-center">
                                        @foreach($fields as $k=>$field)
                                        <div class="col">
                                            <div class="upload-system-item">
                                                <img src="{{getFile('field',$field->image)}}" alt="image"
                                                    class="upload-system-item-img">
                                                <p>{{$k+1}} {{$field->label}}</p>
                                                <div class="file-show-list">
        
                                                </div>
                                                <div class="text-end">
                                                    <input type="file" name="{{$field->name}}" id="file-upload-{{$k}}"
                                                        class="file-upload-field">
                                                    <label for="file-upload-{{$k}}" class="text-primary"><i
                                                            class="fa-solid fa-upload"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
        
                                    <div class="d-flex align-items-center justify-content-center gap-3 mt-sm-5 mt-4">
                                        <a href="{{route('home')}}"
                                            class="btn btn-md btn-outline-secondary cancel-btn">{{__("Cancel")}}</a>
                                        <button type="button" id="details-btn"
                                            class="btn btn-md btn-primary fetch-details-btn">{{__("Details")}}
                                            <i class="bi bi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        'use strict';

        // Handle file preview
        $(document).on('change', '.file-upload-field', function () {
            const files = this.files;
            const $item = $(this).closest('.upload-system-item');
            const $previewList = $item.find('.file-show-list');
            const $label = $item.find(`label[for="${$(this).attr('id')}"]`);
            const $previewImg = $item.find('.upload-system-item-img');

            // Clear preview
            $previewList.empty();

            const existingFiles = new Set();

            Array.from(files).forEach(file => {
                if (existingFiles.has(file.name)) return;
                existingFiles.add(file.name);

                const reader = new FileReader();
                reader.onload = function (e) {
                    const isImage = file.type.startsWith('image/');
                    const preview = isImage
                        ? `<img src="${e.target.result}" class="img-thumbnail mb-1" style="max-width: 100px;">`
                        : `<i class="fa fa-file-pdf fa-3x text-danger"></i>`;

                    $previewList.append(`
                        <div class="file-item mb-2">
                            ${preview}
                            <span class="d-block">${file.name}</span>
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

        // Handle delete preview
        $(document).on('click', '.delete-btn', function () {
            const $item = $(this).closest('.file-item');
            const $uploadItem = $item.closest('.upload-system-item');
            const $input = $uploadItem.find('.file-upload-field');
            const $label = $uploadItem.find(`label[for="${$input.attr('id')}"]`);
            const $img = $uploadItem.find('.upload-system-item-img');

            $item.remove();

            // Reset input if no files left
            if (!$uploadItem.find('.file-item').length) {
                $img.show();
                $label.show();
                $input.val('');
            }
        });

        // Submit documents
        $(document).on('click', '.fetch-details-btn', async function (e) {
    e.preventDefault();

    const form = $("#documentForm")[0];
    const formData = new FormData(form);

    // Check each file field individually
    const emptyFields = [];

    $('.file-upload-field').each(function () {
        if (!$(this).val()) {
            emptyFields.push($(this).attr('name'));
            $(this).closest('.upload-system-item').addClass('border border-danger rounded p-2');
        } else {
            $(this).closest('.upload-system-item').removeClass('border border-danger');
        }
    });

    if (emptyFields.length > 0) {
        iziToast.error({
            message: 'Please upload all required files before continuing.',
            position: 'topRight'
        });
        return;
    }

    try {
        $('#details-btn').prop('disabled', true).html('Submitting...');

        const response = await $.ajax({
            url: "{{ route('visa.applay.submit.documents') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false
        });

        if (response.status) {
            $('.modal').modal('hide');
            $('.modal-backdrop').remove();

            $('.application-slide').removeClass('active');
            $('.root_modal').html(response.html);
            $('.root_modal .details-slide').addClass('active');
        } else {
            iziToast.error({
                message: response.message || 'Something went wrong.',
                position: 'topRight'
            });
        }
    } catch (err) {
        iziToast.error({
            message: 'Upload failed. Please try again.',
            position: 'topRight'
        });
    } finally {
        $('#details-btn').prop('disabled', false).html('Details <i class="bi bi-chevron-right"></i>');
    }
});

    });
</script>