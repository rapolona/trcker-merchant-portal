

<?php $__env->startSection('title', 'Trckr | Rewards Management'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Remaining Budget</h1><span> <?php echo e($remaining_budget); ?> </span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <p>View Rewards</p>
    <div class="row">
        <div class="col col-lg-9">
        </div>      
        <div class="col col-lg-3">
            <form method="POST" enctype="multipart/form-data" id="" action="javascript:void(0)" >
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">                    
                <button type="submit" class="btn btn-block btn-primary btn-lg pull-right">Prefund Wallet</button>  
            </form>
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
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $rewards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td> <?php echo e($r['no']); ?></td>
                        <td> <?php echo e($r['budget']); ?></td>
                        <td> <?php echo e($r['campaign_name']); ?></td>
                        <td> <?php echo e($r['duration']); ?></td>
                        <td> <?php echo e($r['status']); ?></td>
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
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\trckr\resources\views/merchant/rewards.blade.php ENDPATH**/ ?>