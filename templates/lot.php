<section class="lot-item container">
    <h2><?= esc($lot['title']) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= esc($lot['image']) ?>" width="730" height="548" alt="<?= esc($lot['title']) ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= esc($lot['name']) ?></span></p>
            <p class="lot-item__description"><?= esc($lot['description']) ?></p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <?php if (!isset($_SESSION['user'])) : ?>
                    <?= 'Сделать ставку может только авторизованный пользователь' ?>
                    <ul class="user-menu__list">
                        <li class="user-menu__item">
                            <a href="sign_up.php">Регистрация</a>
                        </li>
                        <li class="user-menu__item">
                            <a href="login.php">Вход</a>
                        </li>
                    </ul>
                <?php elseif (strtotime($lot['completed_at']) < time()) : ?>
                    <?= 'Время участия лота в торгах истекло' ?>
                <?php else: ?>
                    <div class="lot-item__timer timer <?= check_warning_time(esc($lot['completed_at'])) ? 'timer--finishing' : '' ?>">
                        <?= calculate_time_lot_ending(esc($lot['completed_at']), 'minute'); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format(esc($lot['starting_price']), null) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?= price_format(((int)esc($lot['starting_price']) + (int)esc($lot['bet_step'])),
                                    'р') ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (isset($bets[0]) && isset($_SESSION['user']) && $bets[0]['bets_user_id'] == $_SESSION['user']['id']) : ?>
                    <?= 'Ваша ставка была последней' ?>
                <?php elseif (isset($_SESSION['user']) && ($_SESSION['user']['login'] !== $lot['login']) && (strtotime($lot['completed_at']) > time())) : ?>
                    <form class="lot-item__form <?= isset($errors) ? "form--invalid" : ""; ?>" action=""
                          method="post"
                          autocomplete="off">
                        <p class="lot-item__form-item form__item <?= isset($errors['price']) ? "form__item--invalid" : ""; ?>">
                            <label for="cost">Ваша ставка</label>
                            <?php $value = isset($_POST['price']) ? $_POST['price'] : "" ?>
                            <input id="cost" type="text" name="price"
                                   placeholder="<?= price_format(((int)esc($lot['starting_price']) + (int)esc($lot['bet_step'])),
                                       null) ?>" value="<?= esc($value); ?>">
                            <?php if (isset($errors['price'])) : ?>
                                <span class="form__error"><?= $errors['price']; ?></span>
                            <?php endif; ?>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="history">
                <h3>История ставок (<span><?= count($bets) ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bets as $index => $item) : ?>
                        <tr class="history__item">
                            <td class="history__name"><?= esc($item['login']) ?></td>
                            <td class="history__price"><?= price_format(esc($item['price']), 'р') ?></td>
                            <td class="history__time"><?= calculate_time_last_bets(esc($item['bets_created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
