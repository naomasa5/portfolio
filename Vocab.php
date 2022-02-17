<?php

class Vocab
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        Token::create();
    }

    public function processPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Token::validate();
            $action = filter_input(INPUT_GET, 'action');
    
            switch ($action) {
                case 'add': 
                    $this->add();
                    break;
                case 'toggle':
                    $this->toggle();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                default:
                    exit;
            }
    
            header('Location: https://webtan.me/index.php');
            exit;
        }
    
    }

    private function add()
    {
        $vocab = trim(filter_input(INPUT_POST, 'vocab'));
        if ($vocab === '') {
            return;
        }

        $japanese = trim(filter_input(INPUT_POST, 'japanese'));
        if ($japanese === '') {
            return;
        }

        $id = $_SESSION['id'];

        $stmt = $this->pdo->prepare("INSERT INTO vocabs (word, japanese, user_id) VALUES (:word, :japanese, :user_id)");
        $stmt->bindValue(':word', $vocab, PDO::PARAM_STR);
        $stmt->bindValue(':japanese', $japanese, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }



    private function toggle()
    {
        $id = filter_input(INPUT_POST, 'id');
        if(empty($id)) {
            return;
        }

        $stmt = $this->pdo->prepare("UPDATE vocabs SET is_done = NOT is_done WHERE word_id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function delete()
    {
        $id = filter_input(INPUT_POST, 'id');
        if(empty($id)) {
            return;
        }

        $stmt = $this->pdo->prepare("DELETE FROM vocabs WHERE word_id = :id");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }


    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM vocabs ORDER BY word_id DESC");
        $vocabs = $stmt->fetchAll();
        return $vocabs;
    }
}
?>