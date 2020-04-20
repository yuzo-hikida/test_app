<?php
require_once('functions.php');
setToken();//追記
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>編集</title>
</head>
<body>
  <?php if (!empty($_SESSION['err'])) : ?><!--追記-->
    <p><?= $_SESSION['err']; ?></p><!--追記-->
    <?php endif; ?><!--追記-->
  <form action="store.php" method="post">
    <input type="hidden" name="token" value="<?= e($_SESSION['token']); ?>"><!--追記-->
    <input type="hidden" name="id" value="<?= e($_GET['id']); ?>"><!--編集-->
    <!--ここの$_GETに更新の時に送られたidが渡される-->
    <input type="text" name="todo" value="<?= e(getSelectedTodo($_GET['id'])); ?>"><!--編集-->
    <!--ここの$_GETに更新の時に送られたidが渡される-->
    <input type="submit" value="更新">
  </form>
  <div>
    <a href="index.php">一覧へもどる</a>
  </div>
  <?php unsetSession(); ?><!--追記-->
</body>
</html>