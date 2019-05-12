<?php
require_once('init.php');

$this_time = time();

$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 0;

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;;
$page_items = 9;

$result = mysqli_query($link, 'SELECT COUNT(*) as cnt FROM lots 
INNER JOIN categories ON lots.category_id = categories.id 
WHERE unix_timestamp(lots.completed_at) > ' . $this_time . ' AND categories.id = ' . $id);
$items_count = mysqli_fetch_assoc($result)['cnt'];

$pages_count = ceil($items_count / $page_items);
$offset = ($current_page - 1) * $page_items;

$pages = range(1, $pages_count);


if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $lots = get_data_array($link, 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id 
WHERE unix_timestamp(lots.completed_at) > ' . $this_time . ' AND categories.id = ' . $id . ' 
ORDER BY created_at DESC LIMIT ' . $page_items . ' OFFSET ' . $offset);
};

$content = include_template('all_lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'id' => $id,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$layout_data = [
    'content' => $content,
    'title' => 'Главная',
    'is_auth' => isset($_SESSION['user']) ? true : false,
    'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
    'categories' => $categories,
    'lots' => $lots,
    'main_classname' => ''
];
create_layout($layout_data);