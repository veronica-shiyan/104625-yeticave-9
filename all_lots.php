<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$this_time = time();
$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 1;
if ($id > count($categories)) {
    $id = 1;
}
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;;
$page_items = 9;
$items_count = get_items($link, $this_time, $id);
$pages_count = ceil($items_count / $page_items);
$offset = ($current_page - 1) * $page_items;
$pages = range(1, $pages_count);
$lots = get_lots_in_category($link, $this_time, $id, $page_items, $offset);

$content = include_template('all_lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'id' => $id,
    'pages_count' => $pages_count,
    'pages' => $pages,
    'current_page' => $current_page
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Главная',
    'is_auth' => isset($_SESSION['user']) ? true : false,
    'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
    'categories' => $categories,
    'lots' => $lots,
    'main_classname' => ''
]);
print($layout_content);