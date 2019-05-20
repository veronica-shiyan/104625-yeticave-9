<?php
require_once('database.php');
session_start();
$link = db_connect($db_data);
$categories = get_categories($link);

if (isset($_SESSION['user'])) {
    $content = include_template('add.php', [
        'categories' => $categories
    ]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST;
        $required = ['title', 'category_id', 'description', 'starting_price', 'bet_step', 'completed_at'];
        $errors = [];

        // Проверка, что указанная дата окончания лота больше текущей даты, хотя бы на один день
        $errors = validation_duration('completed_at', $errors);
        // Проверка поля шаг ставки и начальная цена на целочисленность
        $errors = validation_integer(['bet_step', 'starting_price'], $errors);
        // Проверка полей шаг ставки и начальная цена на величину
        $errors = validation_numeric_fields(['bet_step', 'starting_price'], $errors);
        // Проверка формата даты окончания лота
        $errors = validation_date_format('completed_at', $errors);
        // Проверка заполнения обязательных полей
        $errors = validation_required_fields($required, $errors);
        // Проверка загрузки файла
        $errors = validation_file_type('image', $errors);
        if (!isset($errors['file'])) {
            $tmp_name = $_FILES['image']['tmp_name'];
            $path = $_FILES['image']['name'];
            move_uploaded_file($tmp_name, 'uploads/' . $path);
            $lot['image'] = 'uploads/' . $path;
        }

        if (count($errors)) {
            $content = include_template('add.php', [
                'categories' => $categories,
                'errors' => $errors
            ]);
        } else {
            $data = [
                $lot['title'],
                $lot['description'],
                $lot['starting_price'],
                $lot['completed_at'],
                $lot['bet_step'],
                $lot['category_id'],
                $lot['image'],
                $_SESSION['user']['id']
            ];
            $res = add_lot($link, $data);

            if ($res) {
                $lot_id = mysqli_insert_id($link);
                header("Location: lot.php?tab=" . $lot_id);
            }
        }
    }
    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => 'Добавление лота',
        'is_auth' => true,
        'user_name' => $_SESSION['user']['login'],
        'categories' => $categories,
        'main_classname' => null
    ]);
    print($layout_content);

} else {
    header('HTTP/1.0 403 Forbidden');
    $content = include_template('403.php', []);
    $layout_content = include_template('layout.php', [
        'content' => $content,
        'title' => 'Добавление лота',
        'is_auth' => false,
        'user_name' => '',
        'categories' => $categories,
        'main_classname' => null
    ]);
    print($layout_content);
}