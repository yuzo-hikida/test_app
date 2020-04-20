<?php
require_once('functions.php');

savePostedData($_POST);//functions.phpのsavePostedData関数が実行した後に一覧ページに遷移しています。
header('Location: ./index.php'); //header関数ではLocationと書いてから、飛び先のURLを書きます。