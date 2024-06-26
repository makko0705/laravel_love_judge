<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー登録</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
</head>
<body>
  <nav>
    <a href="<?php echo e(url('/')); ?>">診断する</a>
    <a href="<?php echo e(url('/history')); ?>">過去のデータ</a>
  </nav>
  <div class="container">
    <h1>ユーザー登録</h1>
    <form action="<?php echo e(url('/register')); ?>" method="post">
      <?php echo csrf_field(); ?>
      <input type="text" name="name" placeholder="あなたの名前を入力してください" required />
      <input type="text" name="line_name" placeholder="LINEの名前を入力してください" required />
      <input type="email" name="email" placeholder="メールアドレスを入力してください" required />
      <input type="password" name="password" placeholder="パスワードを入力してください" required />
      <input type="password" name="password_confirmation" placeholder="パスワード確認" required />
      <button type="submit">登録</button>
    </form>
  </div>
  <?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
  <?php endif; ?>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/register.blade.php ENDPATH**/ ?>