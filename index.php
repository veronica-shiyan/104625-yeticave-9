<?php
require_once('database.php');
require_once('getwinner.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$this_time = time();
$lots = get_lots($link, $this_time);

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Главная',
    'is_auth' => isset($_SESSION['user']) ? true : false,
    'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
    'categories' => $categories,
    'lots' => $lots,
    'main_classname' => 'container'
]);
print($layout_content);