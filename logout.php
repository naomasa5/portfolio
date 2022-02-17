<?php
require_once(__DIR__. '/app/config.php');

$_SESSION = array();
session_destroy();
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Web単語帳</title>
</head>
<body>
<p>ログアウトしました。</p>
<a href="login.php">&raquo;ログインへ</a>

</body>
</html>
