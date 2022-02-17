<?php 

require_once(__DIR__. '/app/config.php');

$pdo = Database::getInstance();



if (isset($_SESSION['form'])) {
    $form = $_SESSION['form'];
} else {
    header('Location: https://webtan.me/join.php');
     exit;
}

if ($_SERVER['REQUEST_METHOD'] ==='POST') {

    $password = password_hash($form['password'], PASSWORD_DEFAULT);

    $stmt =$pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    $stmt->bindValue(':name', $form['name'], PDO::PARAM_STR);
    $stmt->bindValue(':email', $form['email'], PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->execute();

    unset($_SESSION['form']);
    header('Location: https://webtan.me/thanks.php');
}


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
<div class="check_form">
    <h1>ユーザー登録</h1>

    <div>
        <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
        <form action="" method="post">
            <dl>
                <dt>ニックネーム</dt>
                <dd>
                <?= Utils::h($form['name']); ?>
                </dd>
                <dt>メールアドレス</dt>
                <dd>
                <?= Utils::h($form['email']); ?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                【表示されません】
                </dd>
            </dl>
            <div><a href="join.php?action=rewrite">書き直す</a> | <input type="submit" value="登録する" /></div>
        </form>
    </div>
</div>
</body>
</html>
