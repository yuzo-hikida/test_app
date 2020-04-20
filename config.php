<?php
//今回はPDO接続というDB接続を行います。この方法でDBからデータを取得したりデータを保存、更新、編集を行うことが可能です。
//本来ならばclassを作成して接続したりするのですが今回は比較的簡単に操作を行っていきたいと思います。

//DB接続を為に設定fileの用意
define('DSN', 'mysql:dbname=php_lesson;host=localhost;unix_socket=/tmp/mysql.sock'); //このデータの名前と情報。mysql:dbname=データベースの名前。host=接続場所。以下クエリ
define('DB_USER', 'root');//ログインした時の
define('DB_PASSWORD', '');
//これは定数の定義を行う記述になっています。
//difine()は第一引数に定数の名前、第二引数に定数の値をとることで定数を定義することができるメソッドです。
//今回、接続情報(MYSQLのDBの名前、ユーザー名、パスワードなど)は一定で変化することはないので定数として定義します。

