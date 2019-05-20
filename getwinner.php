<?php
require_once('database.php');
require_once('vendor/autoload.php');

$link = db_connect($db_data);
$this_time = time();
$winners = get_winners($link, $this_time);

if (isset($winners)) {
    $res = update_winner_id($link, $winners);

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