<?php
require_once('database.php');

$link = db_connect($db_data);
$categories = get_categories($link);

$content = include_template('sign_up.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['email', 'password', 'login', 'contact'];
    $errors = [];

    //Проверка, что значение является корректным e-mail
    $errors = validation_email($errors);
    // Проверка заполнения обязательных полей
    $errors = validation_required_fields ($required, $errors);
    // Проверка загрузки файла
    $errors = validation_file_type ('avatar', $errors);
    if (isset($errors['file']) && $errors['file'] === 'Вы не загрузили файл') {
        unset($errors['file']);
        $form['avatar'] = 'img/user.png';
    } else {
        $tmp_name = $_FILES['avatar']['tmp_name'];
        $path = $_FILES['avatar']['name'];
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $form['avatar'] = 'uploads/' . $path;
    }
    // Проверка существования пользователя с email из формы
    $email = mysqli_real_escape_string($link, $form['email']);
    $res = validation_is_email ($link, $email, 'id');
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
        $data = [$form['email'], $form['login'], $password, $form['contact'], $form['avatar']];
        $res = add_user($link, $data);
        if ($res) {
            header("Location: login.php");
            exit();
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Регистрация',
    'is_auth' => false,
    'user_name' => '',
    'categories' => $categories,
    'main_classname' => ''
]);
print($layout_content);