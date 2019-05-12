<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?= get_category_name($categories, $id) ?>»</span></h2>
        <ul class="lots__list">
            <?php foreach ($lots as $value) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $value['image'] ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $value['name'] ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="lot.php?tab=<?= $value['id'] ?>"><?= $value['title'] ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= price_format($value['starting_price']) ?></span>
                            </div>
                            <div class="lot__timer timer <?= check_warning_time(strtotime($value['completed_at'])) ? 'timer--finishing' : '' ?>">
                                <?= calculate_time_lot_ending(strtotime($value['completed_at']), 'second'); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php if ($pages_count > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a href="<?php if ($current_page > 1) : ?>all_lots.php?tab=<?= $lots[0]['category_id'] ?>&page=<?= ($current_page - 1); ?><?php endif; ?>">Назад</a>
            </li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item <?php if ($page == $current_page): ?>pagination-item-active<?php endif; ?>">
                    <a href="all_lots.php?tab=<?= $lots[0]['category_id'] ?>&page=<?= $page; ?>"><?= $page; ?></a>
                </li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <a href="<?php if ($current_page < count($pages)) : ?>all_lots.php?tab=<?= $lots[0]['category_id'] ?>&page=<?= ($current_page + 1); ?><?php endif; ?>">Вперед</a>
            </li>
        </ul>
    <?php endif; ?>
</div>