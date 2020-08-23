

<?php $__env->startSection('title', 'Users Management'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Users Management</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p>Users Management</p>
    <div class="row">
        <div class="col col-lg-8">
        </div>      
        <div class="col col-lg-4">
            <div class="row">
                <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="file" name="file" id="file">
                    <div class="btn-group" role="group" aria-label="Basic example">                
                        <button type="submit" class="btn btn-block btn-primary btn-lg pull-right">Upload CSV</button>  
                        <a href="/product/add" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                        <button type="button" class="btn btn-primary btn-lg pull-right">Edit</button>    
                        <button type="button" class="btn btn-primary btn-lg pull-right">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-12">
            <div class="card" style="width:100%">
                <!-- /.card-header -->
                <table class="table table-bordered">
                    <thead>                  
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Brand</th>
                        <th>Description</th>
                        <th style="width: 40px">Action?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td> <?php echo e($u['no']); ?></td>
                        <td> <?php echo e($u['name']); ?></td>
                        <td> <?php echo e($u['email_address']); ?></td>
                        <td> <?php echo e($u['type']); ?></td>
                        <td><input type="checkbox" name="<?php echo e($u['no']); ?>" id="<?php echo e($u['no']); ?>" <?php echo e(($u['action'] === 1 ? 'checked' : '')); ?>> </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="/css/admin_custom.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script> 
        $(document).ready(function (e) { 
            $('#file_upload').submit(function(e) {
                //e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/merchant/users/upload",
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: (data) => {
                        this.reset();
                        alert('File has been uploaded successfully');
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
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\trckr\resources\views/merchant/users.blade.php ENDPATH**/ ?>