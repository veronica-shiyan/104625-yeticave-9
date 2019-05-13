<?php
require_once('database.php');

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $lots = get_data_array($link, 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id');
};

if (isset($_SESSION['user'])) {
    $content = include_template('add.php', [
        'categories' => get_categories($link),
        'lots' => $lots
    ]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST;

        $required = ['title', 'category_id', 'description', 'starting_price', 'bet_step', 'completed_at'];
        $errors = [];

        // Проверка, что указанная дата окончания лота больше текущей даты, хотя бы на один день
        if ((strtotime($_POST['completed_at']) - time()) <= 86400) {
            $errors['completed_at'] = 'Продлите срок действия лота';
        }

        // Проверка поля шаг ставки на целочисленность
        if (!is_numeric($_POST['bet_step']) || $_POST['bet_step'] % 1 !== 0) {
            $errors['bet_step'] = 'Содержимое поля должно быть целым числом больше ноля';
        }
// Проверка формата даты окончания лота
        if (!is_date_valid($_POST['completed_at'])) {
            $errors['completed_at'] = 'Введите дату в верном формате';
        }

// Проверка заполнения обязательных полей
        foreach ($required as $key) {
            if (empty($_POST[$key])) {
                $errors[$key] = 'Это поле надо заполнить';
            }
        }

// Проверка загрузки файла
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] !== "") {
            $tmp_name = $_FILES['image']['tmp_name'];
            $path = $_FILES['image']['name'];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $tmp_name);
            // Проверка типа файла
            if ($file_type !== "image/jpeg" && $file_type !== "image/png") {
                $errors['file'] = 'Загрузите картинку в формате jpg, jpeg или png';
            } else {
                move_uploaded_file($tmp_name, 'uploads/' . $path);
                $lot['image'] = 'uploads/' . $path;
            }
        } else {
            $errors['file'] = 'Вы не загрузили файл';
        }

        if (count($errors)) {
            $content = include_template('add.php', [
                'categories' => get_categories($link),
                'lots' => $lots,
                'errors' => $errors
            ]);
        } else {
            $sql = 'INSERT INTO lots (title, description, starting_price, completed_at, bet_step, category_id, image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

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

            $stmt = db_get_prepare_stmt($link, $sql, $data);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($link);

                header("Location: lot.php?tab=" . $lot_id);
            }
        }
    }

    $layout_data = [
        'content' => $content,
        'title' => 'Добавление лота',
        'is_auth' => true,
        'user_name' => $_SESSION['user']['login'],
        'categories' => get_categories($link),
        'lots' => $lots,
        'main_classname' => null
    ];
    create_layout($layout_data);
} else {
    header('HTTP/1.0 403 Forbidden');
    $content = include_template('403.php', []);
    $layout_data = [
        'content' => $content,
        'title' => 'Добавление лота',
        'is_auth' => false,
        'user_name' => '',
        'categories' => get_categories($link),
        'main_classname' => null
    ];
    create_layout($layout_data);
}