<?php
require_once('database.php');
require_once('vendor/autoload.php');

$this_time = time();

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $sql = 'SELECT l.id, l.title, b.bets_user_id, u.login, u.email, b.price FROM lots as l 
INNER JOIN bets as b ON l.id = b.lot_id 
INNER JOIN  users as u ON u.id = b.bets_user_id 
WHERE unix_timestamp(l.completed_at) <= ' . $this_time . ' 
AND l.winner_id IS NULL 
AND  b.price = l.starting_price';
    $winners = get_data_array($link, $sql);

    if (isset($winners)) {

        foreach ($winners as $item) {
            $sql = 'UPDATE lots SET winner_id = ' . $item['bets_user_id'] . ' WHERE id = ' . $item['id'];
            $res = mysqli_query($link, $sql);
        }

        $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
        $transport->setUsername('keks@phpdemo.ru');
        $transport->setPassword('htmlacademy');

        $mailer = new Swift_Mailer($transport);

        $message = new Swift_Message();
        $message->setSubject('Ваша ставка победила');
        $message->setFrom(['keks@phpdemo.ru']);

        foreach ($winners as $item) {
            $message->setBcc($item['email']);
            $msg_content = include_template('email.php', [
                'item' => $item
            ]);
            $message->setBody($msg_content, 'text/html');

            $mailer->send($message);
        }
    }
}
