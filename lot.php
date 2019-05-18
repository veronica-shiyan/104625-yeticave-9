<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 0;
$lots = get_lots_on_id ($link, $id);
$bets = get_bets ($link, $id);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $lots[0];
    $form = $_POST;
    $required = ['price'];
    $errors = [];

    if (empty($_POST['price'])) {
        $errors['price'] = 'Укажите Вашу ставку';
    } else {
        if (!is_numeric($_POST['price']) || $_POST['price'] % 1 !== 0) {
            $errors['price'] = 'Ставка должна быть целым числом больше ноля';
        } elseif ($_POST['price'] < ($lot['starting_price'] + $lot['bet_step'])) {
                $errors['price'] = 'Увеличьте Вашу ставку';
        }
    }

    if (count($errors)) {
        $content = include_template('lot.php', [
            'errors' => $errors
        ]);
    } else {
        $sql = 'INSERT INTO bets (price, bets_user_id, lot_id) VALUES (?, ?, ?)';
        $data = [$form['price'], $_SESSION['user']['id'], $id];
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $sql = 'UPDATE lots SET starting_price = ' . $form['price'] . ' WHERE id = ' . $id;
            $res = mysqli_query($link, $sql);
            header("Location: /my_bets.php");
            exit();
        }
    }
}

if ($lots) {
    $lot = $lots[0];
    $content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot,
        'errors' => isset($errors) ? $errors : null,
        'bets' => $bets
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $lot['title'],
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => $categories,
        'lot' => $lot,
        'bets' => $bets,
        'main_classname' => null
    ]);
} else {
    header('HTTP/1.0 404 Not Found');
    $content = include_template('404.php', []);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => '404 Страница не найдена',
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => $categories,
        'main_classname' => null
    ]);
}

print($layout_content);