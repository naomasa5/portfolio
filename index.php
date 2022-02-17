<?php

    require_once(__DIR__. '/app/config.php');


    $pdo = Database::getInstance();

    if (isset($_SESSION['id']) && isset($_SESSION['name'])) {
        $name = $_SESSION['name'];
    } else {
        header('Location: https://webtan.me/login.php');
        exit;
    }

    $vocab = new Vocab($pdo);
    $vocab->processPost();
    $vocabs = $vocab->getAll();

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Web単語帳</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <main>
        <h1>Web単語帳</h1>
        <p>Welcome back!　<?= Utils::h($name); ?>　さん</p>
        <div class="logout"><a href="logout.php">ログアウト</a></div>

        <div class="add_form">
            <form action="?action=add" method="post">
                <label>英語</label>
                <input type="text" name="vocab" class="add" placeholder="Add new words!">
                <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                <label>意味</label>
                <input type="text" name="japanese" class="add" placeholder="Add meaning!">
                <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">

                <input type="submit" value="追加">
            </form>
        </div>
        

        <ul>
            
            <?php foreach($vocabs as $vocab): ?>
                <?php if ($_SESSION['id'] === $vocab->user_id): ?>
                    <li>
                        <form action="?action=toggle" method="post">
                            <input type="checkbox" <?= $vocab->is_done ? 'checked' : ''; ?>>
                            <input type="hidden" name="id" value="<?= Utils::h($vocab->word_id); ?>">
                            <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                        </form>
                        


                        <span class="<?= $vocab->is_done ? 'done' : ''; ?> ">
                            <?= Utils::h($vocab->word); ?>
                            <div class="colon">:</div>
                            <?= Utils::h($vocab->japanese); ?>
                        </span>

                        <form action="?action=delete" method="post" class="delete-form">
                            <span class="delete">x</span>
                            <input type="hidden" name="id" value="<?= Utils::h($vocab->word_id); ?>">
                            <input type="hidden" name="token" value="<?= Utils::h($_SESSION['token']); ?>">
                        </form>

                        
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            
            
        </ul>
    </main>



    <script src="main.js"></script>
</body>
</html>




