<?php //追記 上から説明する
require_once('functions.php');
setToken();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>新規作成</title>
</head>
<body>
  <?php if (!empty($_SESSION['err'])) : ?> <!--追記。エクスクラメーションマーク-->
    <p><?= $_SESSION['err']; ?></p><!--追記-->
  <?php endif; ?><!--追記-->
  <form action="store.php" method="post"><!--formタグ　データを保存するにあたってPOSTというhttp methodを使用してにゅう直されたデータを指定してデータを保存する機能の箇所に送信する記述をする　actionに送信先（今回はstore.php）method に送信方法POSTを指定POSTとは入力データを URLに載せずに情報を送る方法-->
    <input type="hidden" name="token" value="<?= e($_SESSION['token']); ?>"> <!--type="hiden" は、隠しデータをサーバーに送信する際に使用します。このタイプのデータは画面に表示されませんが、value属性で指定した値がサーバーへ送信されます。nameはこの文書内での位置を特定するもの-->
    <input type="text" name="todo"><!--1行テキストボックスをつくります。通常のテキストを入力するフィールドです。-->
    <input type="submit" value="作成"><!--送信ボタンを作ります。value属性を指定するとボタンの名前として表示されます。-->
  </form>
  <div>
    <a href="index.php">一覧へもどる</a> <!--一覧へ戻るをおすとindex.htmlに戻る-->
  </div>
  <?php unsetSession(); ?><!--追記-->
</body>
</html>