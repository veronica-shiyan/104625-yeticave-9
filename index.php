<?php
require_once('helpers.php');
require_once('data.php');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

$sql_categories = 'SELECT * FROM categories';
$sql_lots = 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id';

if (!$link) {
    show_queries_error(mysqli_connect_error());
}
else {
    $categories = get_data_array ($link, $sql_categories);
    $lots = get_data_array ($link, $sql_lots);
};

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'lots' => $lots
]);

print($layout_content);