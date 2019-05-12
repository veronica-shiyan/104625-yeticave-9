<?php
require_once('init.php');

$this_time = time();

if (!$link) {
    show_queries_error(mysqli_connect_error());
} else {
    $ended_lots = get_data_array($link, 'SELECT id FROM lots 
WHERE unix_timestamp(completed_at) <= ' . $this_time . ' 
AND winner_id IS NULL');

    if (isset($ended_lots)) {
        foreach ($ended_lots as $value) {
            $ended_lots_id[] = $value['id'];

            foreach ($ended_lots_id as $value) {
                $ended_lots_last_bet = get_data_array($link, 'SELECT bets_user_id, lot_id FROM bets 
WHERE lot_id = ' . $value . ' 
ORDER BY bets_created_at DESC LIMIT 1');

                foreach ($ended_lots_last_bet as $value) {
                    $sql = 'UPDATE lots SET winner_id = ' . $value['bets_user_id'] . ' WHERE id = ' . $value['lot_id'];
                    $res = mysqli_query($link, $sql);
                }
            }
        }
    }
}