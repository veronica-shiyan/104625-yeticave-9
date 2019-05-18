<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);
$this_time = time();

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $search = isset($_GET['search']) ? trim(esc($_GET['search'])) : '';

    if ($search) {
        $sql = 'SELECT * FROM lots WHERE MATCH(title, description) AGAINST(?) AND unix_timestamp(lots.completed_at) > ' . $this_time;
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page_items = 9;
        $items_count = count($items);
        $pages_count = ceil($items_count / $page_items);
        $offset = ($current_page - 1) * $page_items;
        $pages = range(1, $pages_count);

        $sql = 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id 
WHERE MATCH(title, description) AGAINST(?) AND unix_timestamp(lots.completed_at) > ' . $this_time . '
ORDER BY created_at DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
}
print($layout_content);