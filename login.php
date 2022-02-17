<?php

require_once(__DIR__. '/app/config.php');

$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');

    if ($email === '' || $password === '') {
        $error['login'] = 'blank';
    } else {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email=:email limit 1');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch();

        if (password_verify($password, $user->password)) {
            session_regenerate_id();
            $_SESSION['id'] = $user->id;
            $_SESSION['name'] = $user->name;
            header('Location: https://webtan.me/index.php');
            exit;

        } else {
            $error['login'] = 'failed';
        }

    }
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <title>Web単語帳</title>
</head>
<body>
    <h1>ログイン画面</h1>
    <div>
        <div class="login_form">
            <div>
                <p>メールアドレスとパスワードを記入してログインしてください。</p>
                <p>ユーザー登録がまだの方はこちらからどうぞ。</p>
                <p>&raquo;<a href="join.php">ユーザー登録をする</a></p>
            </div>
            <form action="" method="post">
                <dl>
                    <dt>メールアドレス</dt>
                    <dd>
                        <input class="join_input" type="email" name="email" size="35" maxlength="255" value="<?= Utils::h($email); ?>"/>
                        <?php if ($error['login'] === 'blank'): ?>
                        <p class="error">* メールアドレスとパスワードをご記入ください</p>
                        <?php endif; ?>
                        <?php if ($error['login'] === 'failed'): ?>
                        <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
                        <?php endif; ?>
                    </dd>
                    <dt>パスワード</dt>
                    <dd>
                        <input class="join_input" type="password" name="password" size="35" maxlength="255" value="<?= Utils::h($password); ?>"/>
                    </dd>
                </dl>
                <div>
                    <input type="submit" value="ログインする"/>
                </div>
            </form>
         </div>
    </div>

</body>
</html>
