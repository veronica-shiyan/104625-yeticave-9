<?php
require_once('helpers.php');
require_once('data.php');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

if (!$link) {
    show_queries_error(mysqli_connect_error());
}
else {
    $sql = 'SELECT * FROM categories';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        show_queries_error(mysqli_error($link));
        $content = include_template('error.php', ['error' => $error]);
    }
};

if (!$link) {
    show_queries_error(mysqli_connect_error());
}
else {
    $sql = 'SELECT * FROM lots 
INNER JOIN categories ON lots.category_id = categories.id';
    $result = mysqli_query($link, $sql);

    if ($result) {
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    else {
        show_queries_error(mysqli_error($link));
    }
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