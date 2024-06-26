<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>相談履歴</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
  <nav>
    <a href="<?php echo e(url('/admin')); ?>">管理画面トップ</a>
    <a href="<?php echo e(url('/admin/history')); ?>">相談履歴</a>
  </nav>
  <div class="container">
    <h1>相談履歴</h1>
    <table>
      <thead>
        <tr>
          <th>相談者</th>
          <th>相談相手</th>
          <th>恋愛可能性(%)</th>
          <th>診断日</th>
          <th>WAIT/GO</th>
          <th>診断内容</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $consultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $consultation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr>
            <td><?php echo e($consultation->user->name); ?></td>
            <td><?php echo e($consultation->partner_name); ?></td>
            <td><?php echo e($consultation->compatibility); ?></td>
            <td><?php echo e($consultation->diagnosis_date); ?></td>
            <td><?php echo e($consultation->go_or_wait); ?></td>
            <td><?php echo e($consultation->diagnosis_content); ?></td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/admin/history.blade.php ENDPATH**/ ?>