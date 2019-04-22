<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <?php foreach ($categories as $value ) :?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?php echo esc($value) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $value ) :?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?php echo esc($value['url']) ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?php echo esc($value['categories']) ?></span>
                    <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?php echo esc($value['name']) ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?php echo price_format(esc($value['price'])) ?></span>
                        </div>
                        <div class="lot__timer timer <?=check_warning_time(strtotime('tomorrow')) ? 'timer--finishing' : '' ?>">
                            <?=calculate_time_lot_ending(strtotime('tomorrow'));?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>