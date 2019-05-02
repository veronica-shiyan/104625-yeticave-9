<?php
require_once('helpers.php');
require_once('data.php');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

$sql_categories = 'SELECT * FROM categories';
$sql_lots = 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id';

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $categories = get_data_array($link, $sql_categories);
    $lots = get_data_array($link, $sql_lots);
};

$content = include_template('add.php', [
    'categories' => $categories,
    'lots' => $lots
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST['lot'];

    $required = ['title', 'category_id', 'description', 'starting_price', 'bet_step', 'completed_at'];
    $errors = [];

    // Проверка, что указанная дата окончания лота больше текущей даты, хотя бы на один день
    if ((strtotime($_POST['lot']['completed_at']) - time()) <= 86400) {
        $errors['completed_at'] = 'Продлите срок действия лота';
    }

    // Проверка поля шаг ставки на целочисленность
    if (!is_numeric($_POST['lot']['bet_step']) || $_POST['lot']['bet_step'] % 1 !== 0) {
        $errors['bet_step'] = 'Содержимое поля должно быть целым числом больше ноля';
    }
// Проверка формата даты окончания лота
    if (!is_date_valid($_POST['lot']['completed_at'])) {
        $errors['completed_at'] = 'Введите дату в верном формате';
    }

// Проверка заполнения обязательных полей
    foreach ($required as $key) {
        if (empty($_POST['lot'][$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

// Проверка загрузки файла
    if (isset($_FILES['lot']['name']['image']) && $_FILES['lot']['name']['image'] !== "") {
        $tmp_name = $_FILES['lot']['tmp_name']['image'];
        $path = $_FILES['lot']['name']['image'];
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
            'categories' => $categories,
            'lots' => $lots,
            'errors' => $errors
        ]);
    } else {
        $sql = 'INSERT INTO lots (title, description, starting_price, completed_at, bet_step, category_id, image, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, 1)';

        $data = [$lot['title'], $lot['description'], $lot['starting_price'], $lot['completed_at'], $lot['bet_step'], $lot['category_id'], $lot['image']];

        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $lot_id = mysqli_insert_id($link);

            header("Location: lot.php?tab=" . $lot_id);
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'lots' => $lots
]);

print($layout_content);