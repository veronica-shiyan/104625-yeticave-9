<?php
require_once('init.php');

if (!$link) {
    show_queries_error(mysqli_connect_error());
}
else {
    $bets = get_data_array ($link, 'SELECT * FROM bets as b 
INNER JOIN lots as l ON l.id = b.lot_id
INNER JOIN  users as u ON u.id = l.user_id 
INNER JOIN  categories as c ON c.id = l.category_id 
WHERE b.bets_user_id = ' . $_SESSION['user']['id'] . ' 
ORDER BY b.bets_created_at DESC');
};

$content = include_template('my_bets.php', [
    'categories' => $categories,
    'bets' => $bets
]);

$layout_data = [
    'content' => $content,
    'title' => 'Мои ставки',
    'is_auth' => true,
    'user_name' =>  $_SESSION['user']['login'],
    'categories' => $categories,
    'bets' => $bets,
    'main_classname' => ''
];
create_layout ($layout_data);