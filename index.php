<?php
require_once('init.php');
require_once('winners.php');

if (!$link) {
    show_queries_error(mysqli_connect_error());
}
else {
    $lots = get_data_array ($link, 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id 
WHERE unix_timestamp(completed_at) > ' . $this_time . '
ORDER BY created_at DESC LIMIT 6');
};

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_data = [
    'content' => $content,
    'title' => 'Главная',
    'is_auth' => isset($_SESSION['user']) ? true : false,
    'user_name' =>  isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
    'categories' => $categories,
    'lots' => $lots,
    'main_classname' => 'container'
];
create_layout ($layout_data);