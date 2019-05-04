<?php
require_once('helpers.php');
require_once('data.php');

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

$sql_categories = 'SELECT * FROM categories';

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $categories = get_data_array($link, $sql_categories);
};

$content = include_template('sign_up.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password', 'login', 'contact'];
    $errors = [];

// Проверка заполнения обязательных полей
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    foreach ($_POST as $key => $value) {
        if ($key == 'email') {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = 'Некорректное значение email';
            }
        }
    }

    // Проверка загрузки файла
    if (isset($_FILES['avatar']['name']) && $_FILES['avatar']['name'] !== "") {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $path = $_FILES['avatar']['name'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        // Проверка типа файла
        if ($file_type !== "image/jpeg" && $file_type !== "image/png") {
            $errors['file'] = 'Загрузите картинку в формате jpg, jpeg или png';
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $path);
            $form['avatar'] = 'uploads/' . $path;
        }
    } else {
        $form['avatar'] = null;
    }

// Проверка существования пользователя с email из формы
    $email = mysqli_real_escape_string($link, $form['email']);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    } else {
        $password = password_hash($form['password'], PASSWORD_DEFAULT);
    }

    if (count($errors)) {
        $content = include_template('sign_up.php', [
            'categories' => $categories,
            'errors' => $errors
        ]);
    } else {
        $sql = 'INSERT INTO users (email, login, password, contact, avatar) VALUES (?, ?, ?, ?, ?)';
        $data = [$form['email'], $form['login'], $password, $form['contact'], $form['avatar']];
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $res = mysqli_stmt_execute($stmt);


        if ($res) {
            header("Location: login.php");
            exit();
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Регистрация',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories
]);

print($layout_content);