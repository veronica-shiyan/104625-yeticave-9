<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($bets as $index => $item) : ?>
            <tr class="rates__item
            <?php if ($item['winner_id'] == $_SESSION['user']['id']) : ?>
                 <?= 'rates__item--win' ?>
            <?php elseif ((strtotime($item['completed_at']) < time())) : ?>
                <?= 'rates__item--end' ?>
            <?php endif; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $item['image'] ?>" width="54" height="40" alt="<?= $item['title'] ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?tab=<?= $item['lot_id'] ?>"><?= $item['title'] ?></a></h3>
                        <?php if ($item['winner_id'] == $_SESSION['user']['id']) : ?>
                            <p><?= $item['contact'] ?></p>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $item['name'] ?>
                </td>
                <td class="rates__timer">
                    <div class="timer
                    <?php if ($item['winner_id'] == $_SESSION['user']['id']) : ?>
                        <?= 'timer--win' ?>
                    <?php elseif ((strtotime($item['completed_at']) < time())) : ?>
                        <?= 'timer--end' ?>
                    <?php elseif (check_warning_time(strtotime($item['completed_at']))) : ?>
                        <?= 'timer--finishing' ?>
                    <?php endif; ?>">
                        <?php if ($item['winner_id'] == $_SESSION['user']['id']) : ?>
                            <?= 'Ставка выиграла' ?>
                        <?php elseif ((strtotime($item['completed_at']) < time())) : ?>
                            <?= 'Торги окончены' ?>
                        <?php else: ?>
                            <?= calculate_time_lot_ending(strtotime($item['completed_at']), 'second'); ?>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= $item['price'] ?>
                </td>
                <td class="rates__time">
                    <?= calculate_time_last_bets(strtotime($item['bets_created_at'])) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>