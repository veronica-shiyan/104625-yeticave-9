<?php
require_once('database.php');

$id = isset($_GET['tab']) ? (int)$_GET['tab'] : 0;

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $lots = get_data_array($link, 'SELECT * FROM lots 
INNER JOIN users ON users.id = lots.user_id 
INNER JOIN categories ON lots.category_id = categories.id 
WHERE lots.id = ' . $id);

    $bets = get_data_array($link, 'SELECT b.bets_created_at, b.price, b.bets_user_id, b.lot_id, u.login FROM bets as b 
INNER JOIN lots as l ON l.id = b.lot_id
INNER JOIN  users as u ON u.id = b.bets_user_id  
WHERE l.id = ' . $id . ' 
ORDER BY b.bets_created_at DESC');
};
$lot = $lots[0];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $required = ['price'];
    $errors = [];

    if (empty($_POST['price'])) {
        $errors['price'] = 'Укажите Вашу ставку';
    } else {
        if (!is_numeric($_POST['price']) || $_POST['price'] % 1 !== 0) {
            $errors['price'] = 'Ставка должна быть целым числом больше ноля';
        } else {
            if ($_POST['price'] < ($lot['starting_price'] + $lot['bet_step'])) {
                $errors['price'] = 'Увеличьте Вашу ставку';
            }
        }
    }

    if (count($errors)) {
        $content = include_template('lot.php', [
            'errors' => $errors
        ]);
    } else {
        $sql = 'INSERT INTO bets (price, bets_user_id, lot_id) VALUES (?, ?, ?)';
        $data = [$form['price'], $_SESSION['user']['id'], $id];
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $sql = 'UPDATE lots SET starting_price = ' . $form['price'] . ' WHERE id = ' . $id;
            $res = mysqli_query($link, $sql);
            header("Location: /my_bets.php");
            exit();
        }
    }
}

if ($lots) {
    $content = include_template('lot.php', [
        'categories' => get_categories($link),
        'lot' => $lot,
        'errors' => isset($errors) ? $errors : null,
        'bets' => $bets
    ]);
    $layout_data = [
        'content' => $content,
        'title' => $lot['title'],
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => get_categories($link),
        'lot' => $lot,
        'bets' => $bets,
        'main_classname' => null
    ];
} else {
    header('HTTP/1.0 404 Not Found');
    $content = include_template('404.php', []);
    $layout_data = [
        'content' => $content,
        'title' => '404 Страница не найдена',
        'is_auth' => isset($_SESSION['user']) ? true : false,
        'user_name' => isset($_SESSION['user']) ? $_SESSION['user']['login'] : '',
        'categories' => get_categories($link),
        'main_classname' => null
    ];
}

create_layout($layout_data);