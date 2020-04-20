<?php
    require_once('functions.php');
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>Home</title>
</head>
<body>
  welcome hello world
  <div>
    <a href="new.php">
      <p>新規作成</p>
    </a>
  </div>
  <div>
    <table>
      <tr>
        <th>ID</th>
        <th>内容</th>
        <th>更新</th>
        <th>削除</th>
      </tr>
      <?php foreach (getTodoList() as $todo) : ?><!--getTodoListメソッドは何かしらの返り値を返している。１レコードずつ出していく-->
        <tr>
          <td><?= e($todo['id']); ?></td><!--編集エスケープ＜？＝？＞はechoの省略形-->
          <td><?= e($todo['todo']); ?></td><!--エスケープ処理編集-->
          <td>
              <a href="edit.php?id=<?= e($todo['id']); ?>">更新</a><!--エスケープ処理編集-->
              <!--?id=以下はクエリパラメータといってこれによりedit.phpに遷移し、かつクエリパラメータのデータをGETでedit.phpに送ることができます。つまり今回はこれでedit.phpに更新がクリックされたTODOのidをGETで送ることができます。そしてこのクエリパラメータの情報をもとに編集画面（edit.php）で更新対象の保存されているデータの表示も行えるようにしていきます。-->
          </td>
          <td>
            <form action="store.php" method="post">
              <input type="hidden" name="id" value="<?= e($todo['id']); ?>"><!--エスケープ処理編集-->
              <button type="submit">削除</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</body>
</html>