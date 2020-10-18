

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Ticket Management</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p>Insert text here</p>
    <div class="row">
        <div class="col col-lg-9">
        </div>      
        <div class="col col-lg-3">
            <div class="row">
            <form method="POST" enctype="multipart/form-data" id="file_upload" action="javascript:void(0)" >
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="/ticket/export" type="button" class="btn btn-primary btn-lg pull-right">Export List</a>
                    <button type="button" class="btn btn-primary btn-lg pull-right">Approve</button>    
                    <button type="button" class="btn btn-primary btn-lg pull-right">Reject</button>
                </div>
            </form>
            </div>
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
                        <th>Trckr Username</th>
                        <th>Email</th>
                        <th>Mobile number</th>
                        <th>Campaign Name</th>
                        <th>Task</th>
                        <th>Date Submitted</th>
                        <th>Device ID</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th style="width: 40px">Action?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td> <?php echo e($t['trckr_username']); ?></td>
                        <td> <?php echo e($t['email']); ?></td>
                        <td> <?php echo e($t['mobile_number']); ?></td>
                        <td> <?php echo e($t['campaign_name']); ?></td>
                        <td> <?php echo e($t['tasks']); ?></td>
                        <td> <?php echo e($t['date_submitted']); ?></td>
                        <td> <?php echo e($t['device_id']); ?></td>
                        <td> <?php echo e($t['location']); ?></td>
                        <td> <?php echo e($t['status']); ?></td>
                        <td><input type="checkbox" name="<?php echo e($t['action']); ?>" id="<?php echo e($t['action']); ?>" <?php echo e(($t['action'] === 1 ? 'checked' : '')); ?>> </td>
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
    <script type="text/javascript">
      
        $(document).ready(function (e) { 
            $('#file_upload').submit(function(e) {
                //e.preventDefault();

                var formData = new FormData(this);
        
                $.ajax({
                    type:'POST',
                    url: "/merchant/products/upload",
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
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\trckr\resources\views/ticket/ticket.blade.php ENDPATH**/ ?>