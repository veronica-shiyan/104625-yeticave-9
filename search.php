<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$this_time = time();
$search = isset($_GET['search']) ? trim(esc($_GET['search'])) : '';

if ($search) {
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page_items = 9;
    $items = get_search_items($link, $this_time, $search);
    $pages_count = ceil($items / $page_items);
    $offset = ($current_page - 1) * $page_items;
    $pages = range(1, $pages_count);
    $lots = get_search_lots($link, $this_time, $search, $page_items, $offset);

    $content = include_template('search.php', [
        'categories' => $categories,
        'lots' => $lots,
        'search' => $search,
        'pages_count' => $pages_count,
        'pages' => $pages,
        'current_page' => $current_page
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => 'Результаты поиска',
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => $categories,
        'lots' => $lots,
        'main_classname' => '',
        'search' => $search
    ]);
} else {
    $content = include_template('search.php', [
        'categories' => $categories,
        'search' => $search,
    ]);

    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => 'Результаты поиска',
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => $categories,
        'main_classname' => '',
        'search' => $search
    ]);

}
print($layout_content);