<?php
require_once('connection.php');
session_start();//CSRF対策のための記述。これを実行することで、セッション管理ができるようになります。

function e($text)//CSRF対策のための記述 エスケープ処理、本来エスケープ処理とは＜＞などの記号そのものを文字として表示できないので特殊な記号に置き換えることによってそれぞれ表示することができる。
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');//ENT_QUOTESはシングルクオートとダブルクオートを共に変換する。
    //エスケープ処理・エンティティ化・・＞や＜や””などの特殊な意味を持つ特殊文字を特殊な意味を持たない単なる文字列に変換することフォームなどでユーザーが悪意のあるスクリプトを送信しようとするのを防いでくれたりするので、セキュリティ上必須となっています。　ソースを表示した時に特殊文字を変換して表示してくれる。またリンクタグが文字列として表示されクリックしても操作しない。
}

// SESSIONに暗号化したtokenを入れる
function setToken()//CSRF対策のための記述
{
    $_SESSION['token'] = sha1(uniqid(mt_rand(), true));//uniqid()13文字。被らないID・乱数を生成するメソッド。マイクロ秒単位の現在時刻にもとづいた１つだけのID。sha1ハッシュ化するメソッド 暗号化との違い
} //ハッシュ化とは、元のデータから一定の計算手順に従ってハッシュ値と呼ばれる規則性のない固定値(固定長のランダムに見える値)を求め、その値によって元のデータを置き換えること。暗号化とは元のデータを暗号化に従って適切な「鍵」を使用することによって「復元可能な値」へ組み換えする行為。ハッシュ化との違い→ハッシュ化は不可逆変換なので本もデータを復元できない、暗号化は秘匿変換なので元のデータに戻すことができます。ハッシュ化にはパスワードなど復元する必要がない状況にて使い、暗号化は秘匿目的なのど機密情報の秘匿等にて使用される。パスワードは復元されて第三者に知られてしまったらいけないし、機密情報が復元できなかったら困ります。スーパーグローバル関数といい、スクリプトのコード中どこからでも使用できる。

// SESSIONに格納されてたtokenのチェックを行いCSRF対策を行う
function checkToken($token)//CSRF対策のための記述
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) { //empty(からかどうかを確認する変数)
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
    }
}

function unsetSession()//CSRF対策のための記述
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()//CSRF対策のための記述 リダイレクト
{
    header('Location: '.$_SERVER['HTTP_REFERER']); //header関数はHTTPヘッダー情報を送信するときに使用します。header('Location: URL')で指定したページにリダイレクトします。今回はURLじゃなくて変数なので、header('Location: '.$**)の形になる。自動的に他のページに転送する。
    exit();//必須ではないがつけないと誤作動を起こす可能性がある。
}

function getTodoList()
{
    return getAllRecords();
}
//ここで書いたgetTodoList()をinde.phpで呼び出してTODOリスト一覧の表示を行います。

function getSelectedTodo($id)
{
    return getTodoTextById($id);
}

function savePostedData($post)
{
    validate($post);//追記
    $path = getRefererPath();//　リファラ。POSTのリクエスト元のURLを文字列で取得してそのパスを返す関数
    switch ($path) {
        case '/new.php': //新規製作ページからPOSTされたら、createTodoData関数を実行。
            checkToken($post['token']);//CSRF対策のための記述
            createTodoData($post['todo']);
            break;
        case '/edit.php': //編集ページからPOSTされたら、updataTodoData関数を実行。
            checkToken($post['token']);//CSRF対策のための記述
            updateTodoData($post);
            break;
        case '/index.php': //一覧ページで削除ボタンを押すので、削除処理のリクエスト元はindex.php
            deleteTodoData($post['id']);
            break;
        default:
            break;
    }
}

//追記 バリデーション機能
function validate($post)
{
    if (isset($post['todo']) && $post['todo'] === '') { //入力した内容がnameがkeyになり内容が値になる.&&（かつ）左のほうがダメだったら右のほうは見ない
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}

function getRefererPath()//リファラ
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']); //parse_url()で今いるブラウザ$_SERVER['HTTP_REFERER']のリンクを分解する。scheme:http,host:www.,path:/hoge.html,queryにわけて連想配列で変数代入します！！
    return $urlArray['path'];//アレイ
}