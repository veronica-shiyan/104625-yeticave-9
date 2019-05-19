<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 0;
$lot = get_lots_on_id ($link, $id);
$bets = get_bets ($link, $id);
$bet_price = $lot['starting_price'] + $lot['bet_step'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['price'];
    $errors = [];

    if (empty($_POST['price'])) {
        $errors['price'] = 'Укажите Вашу ставку';
    } else {
        $errors = validation_bet_value ('price', $errors, $bet_price);
        $errors = validation_integer (['price'], $errors);
    }

    if (count($errors)) {
        $content = include_template('lot.php', [
            'errors' => $errors
        ]);
    } else {
        $data = [$form['price'], $_SESSION['user']['id'], $id];
        $res = add_bets ($link, $data);
        if ($res) {
            $res = update_price ($link, $id);
            header("Location: /my_bets.php");
            exit();
        }
    }
}

if ($lot) {
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