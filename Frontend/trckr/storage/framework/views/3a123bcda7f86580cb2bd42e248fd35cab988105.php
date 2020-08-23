

<?php $__env->startSection('title', 'Trckr | View Profile'); ?>

<?php $__env->startSection('content_header'); ?>`
    <h1>Create New Task</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p>Create Task</p>

    <div class="row">
        <div class="col col-lg-12" >
            <div class="card">
                <form class="form-vertical" id="modify_merchant">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">                
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Task Action Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_action_name" name="action_name" value="" placeholder="Enter Task Action Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Task Action Description</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_action_description" name="action_description" value="" placeholder="Enter Task Action Description">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="company_name" class="col-sm-2 col-form-label">Subject Level</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="input_subject_level" name="subject_level" value="" placeholder="Enter Subject Level">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col col-lg-9">
                        </div>      
                        <div class="col col-lg-3 ">
                            <button type="submit" class="btn btn-block btn-primary btn-lg pull-right" id="submit">Save Details</button>  
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
    <script type="text/javascript">
        
        $(document).ready(function (e) { 
            $('#modify_merchant').submit(function(e) {
                alert(1);
                e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/merchant/modify_profile",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        console.log(data);
                    },
                    error: function(data){
                        console.log(data);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\trckr\resources\views/task/create.blade.php ENDPATH**/ ?>