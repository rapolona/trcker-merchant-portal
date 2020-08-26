

<?php $__env->startSection('title', 'Trckr | View Profile'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>View Profile</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p>View Profile</p>
    <?php
    var_dump($profile);
    ?>
    <div class="row justify-content-md-center">
        <div class="col col-lg-3">
            <button type="button" id="merchant_information" class="btn btn-block btn-primary btn-lg">Merchant Information</button>
        </div>
        <div class="col col-lg-3">
            <button type="button" id="product_information" class="btn btn-block btn-primary btn-lg">Product Information</button>
        </div>
        <div class="col col-lg-3">
            <button type="button" id="branch_information" class="btn btn-block btn-primary btn-lg">Branch Information</button>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="row">
                            <div class="col col-lg-7">
                                <div class="form-group row">
                                    <label for="company_name" class="col-sm-2 col-form-label">Company Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_company_name" value="<?php echo e(( ! empty($profile->name) ? $profile->name : '')); ?>" placeholder="Enter Company Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="company_address" class="col-sm-2 col-form-label">Company Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input_company_address" value="<?php echo e(( ! empty($profile->address) ? $profile->address : '')); ?>" placeholder="Enter Company Address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="trade_name" class="col-sm-2 col-form-label">Trade Name</label>
                                    <div class="col-sm-10">
                                        <input type="emtextail" class="form-control" id="input_trade_name" value="<?php echo e(( ! empty($profile->trade_name) ? $profile->trade_name : '')); ?>" placeholder="Enter Trade Name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="sector" class="col-sm-2 col-form-label">Sector</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="input_sector">
                                            <option value="" selected disabled>Select Sector</option>
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                            <option>option 4</option>
                                            <option>option 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="business_structure" class="col-sm-2 col-form-label">Business Structure</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="input_business_structure">
                                            <option value="" selected disabled>Select Business Structure</option>
                                            <option>option 1</option>
                                            <option>option 2</option>
                                            <option>option 3</option>
                                            <option>option 4</option>
                                            <option>option 5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col col-auto">
                                <div class="form-group row">
                                    <label for="representative" class="col-sm-3 col-form-label">Authorized Representative</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input_representative" value="<?php echo e(( ! empty($profile->authorized_representative) ? $profile->authorized_representative : '')); ?>"placeholder="Enter Authorized Representative">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="position" class="col-sm-3 col-form-label">Position</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input_position" value="<?php echo e(( ! empty($profile->position) ? $profile->position : '')); ?>"placeholder="Enter Position">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="contact_number" class="col-sm-3 col-form-label">Contact Number</label>
                                    <div class="col-sm-9">
                                        <input type="emtextail" class="form-control" id="input_contact_number" value="<?php echo e(( ! empty($profile->contact_number) ? $profile->contact_number : '')); ?>" placeholder="Enter Contact Number">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email_address" class="col-sm-3 col-form-label">Email Address</label>
                                    <div class="col-sm-9">
                                        <input type="emtextail" class="form-control" id="input_email_address" value="<?php echo e(( ! empty($profile->email_address) ? $profile->email_address : '')); ?>"placeholder="Enter Email Address">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="/css/admin_custom.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script> console.log('Hi!'); </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\trckr\resources\views/merchant/view.blade.php ENDPATH**/ ?>