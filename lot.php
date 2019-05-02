<?php
require_once('helpers.php');
require_once('data.php');
$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");
$sql_categories = 'SELECT * FROM categories';
$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 0;
$sql_lot = 'SELECT * FROM lots 
INNER JOIN categories ON lots.category_id = categories.id
WHERE lots.id = ' . $id;
if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $categories = get_data_array($link, $sql_categories);
    $lots = get_data_array($link, $sql_lot);
};
if ($lots) {
    $lot = $lots[0];
    $content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot
    ]);
    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => $lot['title'],
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories,
        'lot' => $lot
    ]);
} else {
    $content = include_template('404.php', [
        'categories' => $categories
    ]);
    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => '404 Страница не найдена',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'categories' => $categories
    ]);
}
print($layout_content);