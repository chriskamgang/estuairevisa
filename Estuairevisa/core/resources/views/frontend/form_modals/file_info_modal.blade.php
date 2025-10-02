@php
$fields = \App\Models\VisaFileField::active()->get();
$content = content('visa_info.content');
$elements = element('visa_info.element')
@endphp
<div class="modal fade" id="fileInfoModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <h4 class="mb-1">{{__($content->data->title)}}</h4>
                    <p class="mb-5">{{__($content->data->subtitle)}}</p>
                </div>

                <div class="row justify-content-center gy-4">
                    @foreach($fields as $key=>$field)
                    <div class="col-lg-3">
                        <div class="visa-modal-item">
                            <img src="{{getFile('field',$field->image)}}" alt="image">
                            <h6 class="title">{{$key+1}} {{$field->title}}</h6>
                            <span class="line"></span>
                            <p>{{$field->short_description}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <p class="fw-semibold display-color mt-4">{{__("Please confirm that you have read and agreed to the
                    following:")}}</p>

                @foreach($elements as $k=>$element)

                <div class="form-check style-check">
                    <input class="form-check-input" type="checkbox" name="file_condition_check[]" id="terms-{{$k}}">
                    <label class="form-check-label text-sm" for="terms-{{$k}}">
                        {{__($element->data->agreement_short_description)}} <a
                            href="{{route('pages',$element->data->agreement_page_slug)}}" target="_blank">{{__("know
                            more")}}</a>
                    </label>
                </div>

                @endforeach


                <div class="d-flex flex-wrap align-items-center gap-3 justify-content-between mt-sm-5 mt-4">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="{{route('home')}}" class="btn btn-md btn-secondary">{{__("Cancel")}}</a>
                        <button type="button" class="btn btn-md btn-primary fetch_document_upload-btn">{{__("Next")}} <i
                                class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(function () {
        'use strict';

        $(document).on('click', '.fetch_document_upload-btn', async function () {
            let allChecked = true;

         
            $('input[name="file_condition_check[]"]').each(function () {
                if (!$(this).is(':checked')) {
                    allChecked = false;
                }
            });

            if (!allChecked) {
                iziToast.error({
                    message: 'Please accept all conditions to continue.',
                    position: 'topRight'
                });
                return;
            }

            try {
                const response = await $.get("{{ route('visa.applay.documents') }}");
                if (!response.status) {
                    iziToast.error({
                        message: response.message || 'Unable to fetch document section.',
                        position: 'topRight'
                    });
                    return;
                }

                $('.document-slide').hide().removeClass('active');
                $('.modal').modal('hide');
                $('.modal-backdrop').hide();
                $('.root_modal').html(response.html);
                $('.' + response.slide_name).addClass('active');
            } catch {
                iziToast.error({
                    message: 'Document fetch failed.',
                    position: 'topRight'
                });
            }
        });
    });
</script>