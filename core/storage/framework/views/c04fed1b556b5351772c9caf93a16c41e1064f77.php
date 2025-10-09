<?php
$fields = \App\Models\VisaFileField::active()->get();
$content = content('visa_info.content');
$elements = element('visa_info.element')
?>
<div class="modal fade" id="fileInfoModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <h4 class="mb-1"><?php echo e(__($content->data->title)); ?></h4>
                    <p class="mb-5"><?php echo e(__($content->data->subtitle)); ?></p>
                </div>

                <div class="row justify-content-center gy-4">
                    <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-3">
                        <div class="visa-modal-item">
                            <img src="<?php echo e(getFile('field',$field->image)); ?>" alt="image">
                            <h6 class="title"><?php echo e($key+1); ?> <?php echo e($field->title); ?></h6>
                            <span class="line"></span>
                            <p><?php echo e($field->short_description); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <p class="fw-semibold display-color mt-4"><?php echo e(__("Please confirm that you have read and agreed to the
                    following:")); ?></p>

                <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="form-check style-check">
                    <input class="form-check-input" type="checkbox" name="file_condition_check[]" id="terms-<?php echo e($k); ?>">
                    <label class="form-check-label text-sm" for="terms-<?php echo e($k); ?>">
                        <?php echo e(__($element->data->agreement_short_description)); ?> <a
                            href="<?php echo e(route('pages',$element->data->agreement_page_slug)); ?>" target="_blank"><?php echo e(__("know
                            more")); ?></a>
                    </label>
                </div>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                <div class="d-flex flex-wrap align-items-center gap-3 justify-content-between mt-sm-5 mt-4">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-md btn-secondary"><?php echo e(__("Cancel")); ?></a>
                        <button type="button" class="btn btn-md btn-primary fetch_document_upload-btn"><?php echo e(__("Next")); ?> <i
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
                const response = await $.get("<?php echo e(route('visa.applay.documents')); ?>");
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
</script><?php /**PATH /Users/redwolf-dark/estuairevisa/core/resources/views/frontend/form_modals/file_info_modal.blade.php ENDPATH**/ ?>