<?php
require_once('database.php');
session_start();

$link = db_connect($db_data);
$categories = get_categories($link);

$content = include_template('login.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['email', 'password'];
    $errors = [];
    $errors = validation_required_fields($required, $errors);

    if (!count($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $res = validation_is_email($link, $email, '*');
        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

        if (!count($errors) and $user) {
            if (password_verify($form['password'], $user['password'])) {
                $_SESSION['user'] = $user;
            } else {
                $errors['password'] = 'Вы ввели неверный пароль';
            }
        } else {
            $errors['email'] = 'Такой пользователь не найден';
        }
    }

    if (count($errors)) {
        $content = include_template('login.php', [
            'categories' => $categories,
            'form' => $form,
            'errors' => $errors
        ]);
    } else {
        header("Location: /index.php");
        exit();
    }
}

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => 'Вход',
    'is_auth' => false,
    'user_name' => '',
    'categories' => $categories,
    'main_classname' => null
]);
print($layout_content);