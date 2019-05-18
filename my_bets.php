<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$user_id = $_SESSION['user']['id'];
$bets = get_user_bets ($link, $user_id);

$content = include_template('my_bets.php', [
    'categories' => $categories,
    'bets' => $bets
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Мои ставки',
    'is_auth' => true,
    'user_name' => $_SESSION['user']['login'],
    'categories' => $categories,
    'bets' => $bets,
    'main_classname' => ''
]);
print($layout_content);