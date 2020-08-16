

<?php $__env->startSection('title', 'Trckr | View Campaigns'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Campaign</h1>
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
                    <a href="/campaign/create" type="button" class="btn btn-primary btn-lg pull-right">Add</a>
                    <button type="button" class="btn btn-primary btn-lg pull-right">Edit</button>    
                    <button type="button" class="btn btn-primary btn-lg pull-right">Delete</button>  
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
                        <th style="width: 10px">#</th>
                        <th>Campaign Name</th>
                        <th>Budget</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th style="width: 40px">Action?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $campaigns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td> <?php echo e($c['no']); ?></td>
                        <td> <?php echo e($c['campaign_name']); ?></td>
                        <td> <?php echo e($c['budget']); ?></td>
                        <td> <?php echo e($c['duration']); ?></td>
                        <td> <?php echo e($c['status']); ?></td>
                        <td><input type="checkbox" name="<?php echo e($c['no']); ?>" id="<?php echo e($c['no']); ?>" <?php echo e(($c['action'] === 1 ? 'checked' : '')); ?>> </td>
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
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\trckr\resources\views/campaign/campaign.blade.php ENDPATH**/ ?>