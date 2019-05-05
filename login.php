<?php
require_once('helpers.php');
require_once('data.php');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

$sql_categories = 'SELECT * FROM categories';

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $categories = get_data_array($link, $sql_categories);
};

$content = include_template('login.php', [
    'categories' => $categories
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Вход',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories
]);

print($layout_content);