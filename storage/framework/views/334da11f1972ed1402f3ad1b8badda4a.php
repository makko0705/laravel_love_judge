<?php $__env->startSection('title', '診断履歴'); ?>

<?php $__env->startSection('content'); ?>
    <h1>過去の診断結果</h1>
    <ul>
      <?php $__currentLoopData = $consultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consultation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
          <strong><?php echo e($consultation->partner_name); ?> さんの診断結果</strong><br>
          恋愛可能性: <?php echo e($consultation->compatibility); ?>%<br>
          診断日: <?php echo e($consultation->diagnosis_date); ?><br>
          判定: <?php echo e($consultation->go_or_wait); ?><br>
          <button onclick="showDetails('<?php echo e($consultation->diagnosis_content); ?>')">詳細を見る</button>
          <form action="<?php echo e(url('/history', ['id' => $consultation->id])); ?>" method="post">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit">削除</button>
          </form>
        </li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>

  <script>
    function showDetails(details) {
      alert(details);
    }
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/history.blade.php ENDPATH**/ ?>