<?php $__env->startSection('title', '診断する'); ?>

<?php $__env->startSection('content'); ?>
    <h1>診断する</h1>
    <form action="<?php echo e(url('/analyze')); ?>" method="post" enctype="multipart/form-data">
      <?php echo csrf_field(); ?>
      <input type="text" name="userName" placeholder="あなたの名前を入力してください" required />
      <input type="text" name="line_name" placeholder="LINEの名前を入力してください" required />
      <input type="file" name="chatHistoryFile" required />
      <button type="submit">Analyze</button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/index.blade.php ENDPATH**/ ?>