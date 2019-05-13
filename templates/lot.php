<section class="lot-item container">
    <h2><?php echo $lot['title'] ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?php echo $lot['image'] ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?php echo $lot['name'] ?></span></p>
            <p class="lot-item__description"><?php echo $lot['description'] ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['login'] !== $lot['login']) : ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?= check_warning_time(strtotime($lot['completed_at'])) ? 'timer--finishing' : '' ?>">
                        <?= calculate_time_lot_ending(strtotime($lot['completed_at']), 'minute'); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?php echo price_format_no_currency($lot['starting_price']) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?php echo price_format_no_currency($lot['starting_price'] + $lot['bet_step']) . ' р' ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form <?= isset($errors) ? "form--invalid" : ""; ?>" action="" method="post"
                          autocomplete="off">
                        <p class="lot-item__form-item form__item <?= isset($errors['price']) ? "form__item--invalid" : ""; ?>">
                            <label for="cost">Ваша ставка</label>
                            <?php $value = isset($_POST['price']) ? $_POST['price'] : "" ?>
                            <input id="cost" type="text" name="price" placeholder="12 000" value="<?= $value; ?>">
                            <?php if (isset($errors['price'])) : ?>
                                <span class="form__error"><?= $errors['price']; ?></span>
                            <?php endif; ?>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets as $index => $item) : ?>
                        <tr class="history__item">
                            <td class="history__name"><?= $item['login'] ?></td>
                            <td class="history__price"><?= $item['price'] ?></td>
                            <td class="history__time"><?= calculate_time_last_bets(strtotime($item['bets_created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
