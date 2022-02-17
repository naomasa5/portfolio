<?php
require_once(__DIR__. '/app/config.php');

if (isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])) {
	$form = $_SESSION['form'];
} else {
	$form = [];
}

$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$form['name'] = filter_input(INPUT_POST, 'name');
	if ($form['name'] === '') {
		$error['name'] = 'blank';
	}

	$form['email'] = filter_input(INPUT_POST, 'email');
	if ($form['email'] === '') {
		$error['email'] = 'blank';
	} else {
		$pdo = Database::getInstance();
		$stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email=:email');
		
		$stmt->bindValue(':email', $form['email'], PDO::PARAM_STR);
		$stmt->execute();

		$exist = $stmt->fetchColumn();
		
		if ($exist > 0) {
			$error['email'] = 'duplicate';
		}

	}

	$form['password'] = filter_input(INPUT_POST, 'password');
	if ($form['password'] === '') {
		$error['password'] = 'blank';
	} else if (strlen($form['password']) < 4) {
		$error['password'] = 'length';
	}

	if (empty($error)) {
		$_SESSION['form'] = $form;
		header('Location: https://webtan.me/check.php');
		exit;
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
	<h1>ユーザー登録画面</h1>
	<div class="join_form">
		<p>既にユーザー登録がお済みの方は<a href="login.php">こちら</a></p>
		<div>
			<p>次のフォームに必要事項をご記入ください。</p>
			<form action="" method="post">
				<dl>
					<dt>ユーザー名</dt>
					<dd>
						<input class="join_input" type="text" name="name" size="35" maxlength="255" value="<?= Utils::h($form['name']); ?>"/>
						<?php if($error['name'] === 'blank'): ?>
						<p class="error">*ユーザー名を入力してください</p>
						<?php endif; ?>
					</dd>
					<dt>メールアドレス</dt>
					<dd>
						<input class="join_input" type="email" name="email" size="35" maxlength="255" value="<?= Utils::h($form['email']); ?>"/>
						<?php if($error['email'] === 'blank'): ?>
						<p class="error">*メールアドレスを入力してください</p>
						<?php endif; ?>
						<?php if($error['email'] === 'duplicate'): ?>
						<p class="error">*指定されたメールアドレスは既に登録されています</p>
						<?php endif; ?>
					</dd>	
					<dt>パスワード</dt>
					<dd>
						<input type="password" name="password" size="10" maxlength="20" value="<?= Utils::h($form['password']); ?>"/>
						<?php if($error['password'] === 'length'): ?>
						<p class="error">*パスワードは4文字以上で入力してください</p>
						<?php endif; ?>
						<?php if($error['password'] === 'blank'): ?>
						<p class="error">*パスワードを入力してください</p>
						<?php endif; ?>
					</dd>
					
				</dl>
				<div><input type="submit" value="入力内容を確認する" /></div>
			</form>
		</div>
	</div>
</body>
</html>