<?php
require_once('config.php');//ここでconfig.phpの中に記載されているものが使用可能。

// PDOクラスのインスタンス化
function connectPdo() //この関数の中の記載に関しては、PDO接続を行う際の基本的なお作法の記述
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);//new PDOの中に定数を上の順番(config.phpで設定した定数)に書いていくnewの箇所はインスタンスの作成を行っています。
    } catch (PDOException $e) {//例外の名前PDOException今回はPDOが発するエラーを
        echo $e->getMessage();
        exit();
    }
}
// 新規作成処理
function createTodoData($todoText)
{
    $dbh  = connectPdo(); //$dbh データベースハンドラの略
    $sql  = 'INSERT INTO todos (todo) VALUES (:todoText)'; //ここでパラメータIDを使うのは変数を直接かくと中身が見られてしまうため。INSERT INTO テーブル名 (追加したい値のカラム名) VALUES (追加したい値)
    //SQL文に変数を入れると
    $stmt = $dbh->prepare($sql);//PDOStatementオブジェクトが返される。このオブジェクトはデータベースから引っ張ってきた情報をPHPで出力、つまりサイトの画面に表示したり、計算に使うために必要です。prepareメソッドを使うとPDOStatementをインスタンス化し、作成されたインスタンスを返します。
    $stmt->bindValue(':todoText', $todoText);//bindValue(名前つきプレースホルダー,バインドする値,データ型)PDO::PARAM_STR文字列型
    $stmt->execute();//実行する
}

function getAllRecords() //DBから登録したデータを全権取得する。
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';//ヌル
    return $dbh->query($sql)->fetchAll();//queryメソッド そのままSQL文を実行できるメソッド。PDOStatementオブジェクトを返してくれるのでPDOStatementからfetchAllメソッドを呼び出して全ての結果を配列に返してくれる。ユーザーの入力した情報を含める必要がないのでqueryメソッドを使う。fetchAll()全ての結果を含む配列を返す。PDOStatementは$dbh->query($sql)に返っている。
}
/*'SELECT * FROM todos WHERE deleted_at IS NULL';
SELECT 取得したいカラム名 FROM テーブル名 WHERE カラム名 = 値 WHEREはなくても構いませんが、あると指定したカラムが指定した値のレコードのみを、ないと全てのレコードを取得します。
SELECTの後に取得したいカラムを指定することができます。例えばidとか。今回は[*]アスタリスクが書いてあります。これはすべてのカラムという意味*/
/*今回はtodosテーブルから、削除をしていないデータをすべて取得するというSQL文を$sqlに格納し
$dbh->query($sql)でDBに対して上記のSQL文を実行し、fatchAll()で実行結果を全権配信で取得そしてその結果をreturnしています。*/
/* getAllRecords()この関数をconnection.phpに書いた関数をfunction.phpで呼び出してあげる。*/
//fetcAllはデータを配列として返してくれる
function updateTodoData($post)
{
    $dbh = connectPdo();
    $sql = 'UPDATE todos SET todo = :todoText WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(':todoText', $post['todo']);
    $stmt->bindValue(':id', (int)$post['id']);//第三引数は無くてもいいが書くなら正しい記述で書く。
    $stmt->execute();
}
//上から順に説明します。
//最初はおきまりのDB接続です。
//SQL文ですが今回は更新を行うのでUPDATE文を書きます。
//UPDATE テーブル名 SET 更新するカラム名 = 更新する値 WHERE カラム名 = 値
//WHEREのあとにカラム名と値を設定することで、設定したカラムが設定した値のレコード(データ)のみを対象にUPDATE処理を行うことができます。
//今回の場合は、todosテーブルのidカラムがPOSTで受けとったidのところのデータを更新するという処理になっていますね。
function getTodoTextById($id)
{
    $dbh = connectPdo();
    $sql = 'SELECT todo FROM todos WHERE id = :id AND deleted_at IS NULL';
    $stmt = $dbh->prepare($sql); //ユーザーからの入力を受け取る準備、SQL文を実行する準備。
    $stmt-> bindValue(':id', (int)$id);
    //SELECT 取得したいカラム名 FROM テーブル名 WHERE カラム名 = 値 WHEREはなくても構いませんが、あると指定したカラムが指定した値のレコードのみを、ないと全てのレコードを取得します。
/*値をバインドする　bindValue(パラメータID, バインドする値, データ型);
第一引数にはパラメータIDを指定します。パラメータIDとは、名前つきプレースホルダの場合は「:名前」となります。
第二引数にはバインドする値を指定します。値は直接入力するか、変数を入れて指定します。型キャストを使用。(int)整数へのキャスト。
第三引数には、データ型を指定します。今回は整数型を指定。*/
  $stmt-> execute(); //プリペアドステートメントを実行することで結果をセットする。
  //データベースから取り出されたデータを一時的に保持する仮想的なテーブルのようなもの。
    $data = $stmt->fetch();//データの取得fetchメソッドで該当するデータを１件のみ配列として返す。
    return $data['todo'];
}
//・データの取得なので実行するSQL文はSELECT文です。
//・取得の１つ目の条件は、idカラムが更新したいTODOのid($id)であること
//.取得の２つ目の条件は、deleted_atカラムの値がNULLであること
//・returnする値＄data['todo']は、上記の条件に合致するTODOの内容であること。

function deleteTodoData($id)
{
    $dbh = connectPdo();
    $now = date('Y-m-d H:i:s');//date関数
    $sql = 'UPDATE todos SET deleted_at = :nowTime WHERE id = :id';
    //UPDATE テーブル名 SET 更新するカラム名 = 更新する値 WHERE カラム名 = 値
    $stmt = $dbh->prepare($sql); //ユーザーからの入力を受け取る準備、SQL文を実行する準備。
    $stmt->bindValue(':nowTime', $now, PDO::PARAM_STR);
    $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
    $stmt->execute();
}

//・論理削除なので実行するSQL文はUPDATE文。
//・現在の時刻はPHPのdateメソッドで取得することができます。(すでに取得する処理は書いある)
//・UPDATEを実行してデータの更新されればOKなので何か値をreturnする必要はありません。

// function deleteTodoData($id)
// {
//     // var_dump($id);
//     // exit();
//     $dbh = connectPdo();
//     $now = date('Y-m-d H:i:s');
//     $sql = 'UPDATE todos SET deleted_at = :nowTime WHERE id = :id';
//     $stmt = $dbh->prepare($sql);
//     $stmt->bindValue(':nowTime', $now, PDO::PARAM_STR);
//     $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
//     $stmt->execute();
// }