<?php
require_once('helpers.php');
session_start();

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

// Получение списка категорий
if (!$link) {
    show_queries_error(mysqli_connect_error());
}
else {
    $categories = get_data_array ($link, 'SELECT * FROM categories');
};

// Создание layout
function create_layout ($layout_data) {
    $layout_content = include_template('layout.php', $layout_data);
    print($layout_content);
};