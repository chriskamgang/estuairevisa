<form action="<?php echo e(route('contact')); ?>" method="post" class="php">
    <?php echo csrf_field(); ?>
    <div class="row">
        <div class="col-md-6 form-group">
            <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
        </div>
        <div class="col-md-6 form-group mt-3 mt-md-0">
            <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
        </div>
    </div>
    <div class="form-group mt-3">
        <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
    </div>
    <div class="form-group mt-3">
        <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
    </div>

    <div class="mt-3">
        <button class="btn btn-primary w-100" type="submit"><?php echo e(__('Send Message')); ?></button>
    </div>
</form>
<?php /**PATH /Users/redwolf-dark/Documents/Estuaire/IMMIGRATION/Estuairevisa/core/resources/views/backend/frontend/not_editable/contact.blade.php ENDPATH**/ ?>