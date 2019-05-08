<?php
require_once('init.php');

$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 0;
if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $lots = get_data_array($link, 'SELECT * FROM lots 
INNER JOIN categories ON lots.category_id = categories.id
WHERE lots.id = ' . $id);
};

if ($lots) {
    $lot = $lots[0];
    $content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot
    ]);
    $layout_data = [
        'content' => $content,
        'title' => $lot['title'],
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => $categories,
        'lot' => $lot,
        'main_classname' => null
    ];
} else {
    header('HTTP/1.0 404 Not Found');
    $content = include_template('404.php', []);
    $layout_data = [
        'content' => $content,
        'title' => '404 Страница не найдена',
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => $categories,
        'main_classname' => null
    ];
}

create_layout($layout_data);