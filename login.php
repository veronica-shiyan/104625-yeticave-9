<?php
require_once('database.php');

$content = include_template('login.php', [
    'categories' => get_categories($link)
]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];

    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    if (!count($errors)) {
        $email = mysqli_real_escape_string($link, $form['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($link, $sql);

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
            'categories' => get_categories($link),
            'form' => $form,
            'errors' => $errors
        ]);
    } else {
        header("Location: /index.php");
        exit();
    }
}

$layout_data = [
    'content' => $content,
    'title' => 'Вход',
    'is_auth' => false,
    'user_name' => '',
    'categories' => get_categories($link),
    'main_classname' => null
];
create_layout($layout_data);