<?php
require_once('database.php');
require_once('getwinner.php');

$sql = 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id 
WHERE unix_timestamp(completed_at) > ' . $this_time . '
ORDER BY created_at DESC LIMIT 6';

$content = include_template('index.php', [
    'categories' => get_categories($link),
    'lots' => get_data($link, $sql)
]);

$layout_data = [
    'content' => $content,
    'title' => 'Главная',
    'is_auth' => isset($_SESSION['user']) ? true : false,
    'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
    'categories' => get_categories($link),
    'lots' => get_data($link, $sql),
    'main_classname' => 'container'
];
create_layout($layout_data);