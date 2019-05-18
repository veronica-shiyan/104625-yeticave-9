<section class="lots">
    <div class="lots__header">
        <h2>Открытые лоты</h2>
    </div>
    <ul class="lots__list">
        <?php foreach ($lots as $value) : ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= esc($value['image']) ?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= esc($value['name']) ?></span>
                    <h3 class="lot__title"><a class="text-link"
                                              href="lot.php?tab=<?= esc($value['id']) ?>"><?= esc($value['title']) ?></a>
                    </h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?php echo price_format(esc($value['starting_price']), '&#8381') ?></span>
                        </div>
                        <div class="lot__timer timer <?= check_warning_time($value['completed_at']) ? 'timer--finishing' : '' ?>">
                            <?= calculate_time_lot_ending($value['completed_at'], 'minute'); ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</section>