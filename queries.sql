INSERT INTO categories (name, symbol_code)
VALUES ('Доски и лыжи', 'boards'), ('Крепления', 'attachment'), ('Ботинки', 'boots'), ('Одежда', 'clothing'), ('Инструменты', 'tools'), ('Разное', 'other');

INSERT INTO users
SET created_at = NOW(), email = 'bigfoot@yandex.ru', login = 'Bigfoot', password = '%4ItlRYyekb^', avatar = '', contact = '';
INSERT INTO users
SET created_at = NOW(), email = 'main_Yeti@mail.ru', login = 'Main_Yeti', password = 'a%P7dXlZRA6^', avatar = '', contact = '';
INSERT INTO users
SET created_at = NOW(), email = 'skier@gmail.com', login = 'Skier', password = 'UoVB&*vv*@1i', avatar = '', contact = '';

INSERT INTO lots
SET created_at = NOW(), title = '2014 Rossignol District Snowboard', description = 'Сноуборд DISTRICT AMPTEK от известного французского производителя ROSSIGNOL, разработанный специально для начинающих фрирайдеров. Эта доска отлично подойдёт как для обычного склона, так и для парка, а также для обучения. В доступном по цене сноуборде DISTRICT AMPTEK применены современные технологии, которые удачно сочетаются, обеспечивая при этом отличные рабочие характеристики и комфорт. Он оптимален для тех, кто хочет быстро повысить свой уровень техники и мастерства. Классическая твин-тип форма позволяет кататься в разных стойках. За устойчивость и стабильность отвечает стандартный прогиб, он гарантирует жесткую хватку кантов. Высокие рокеры Amptek Auto-Turn обеспечивают легкость управления доской и четкое вхождение в повороты.', image = 'img/lot-1.jpg', starting_price = 10999, completed_at = (created_at + INTERVAL 25 HOUR), bet_step = 500, user_id = 1, category_id = 1;
INSERT INTO lots
SET created_at = NOW(), title = 'DC Ply Mens 2016/2017 Snowboard', description = 'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчком и четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд отличной гибкостью и отзывчивостью, а симметричная геометрия в сочетании с классическим прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от Шона Кливера еще никого не оставляла равнодушным.', image = 'img/lot-2.jpg', starting_price = 159999, completed_at = (created_at + INTERVAL 26 HOUR), bet_step = 300, user_id = 2, category_id = 1;
INSERT INTO lots
SET created_at = NOW(), title = 'Крепления Union Contact Pro 2015 года размер L/XL', description = 'Невероятно легкие универсальные крепления весом всего 720 грамм готовы порадовать прогрессирующих райдеров, практикующих как трассовое катание, так и взрывные спуски в паудере. Легкая нейлоновая база в сочетании с очень прочным хилкапом, выполненным из экструдированного алюминия, выдержит серьезные нагрузки, а бакли, выполненные из магния не только заметно снижают вес, но и имеют плавный механизм. Система стрепов 3D Connect обеспечивает равномерное давление на верхнюю часть ноги, что несомненно добавляет комфорта как во время выполнения трюков, так и во время катания в глубоком снегу.', image = 'img/lot-3.jpg', starting_price = 8000, completed_at = (created_at + INTERVAL 28 HOUR), bet_step = 100, user_id = 3, category_id = 2;
INSERT INTO lots
SET created_at = NOW(), title = 'Ботинки для сноуборда DC Mutiny Charocal', description = 'Описание отсутствует', image = 'img/lot-4.jpg', starting_price = 10999, completed_at = (created_at + INTERVAL 30 HOUR), bet_step = 150, user_id = 2, category_id = 3;
INSERT INTO lots
SET created_at = NOW(), title = 'Куртка для сноуборда DC Mutiny Charocal', description = 'Куртка DC Mutiny Charocal – это бескомпромиссная защита с полным функционалом для самых экстремальных погодных условий. Благодаря влагостойкой мембране DRILITE® Loft ll и утеплителю Primaloft Gold, вы будете на все 100% комфортно себя чувствовать при самых суровых капризах природы.', image = 'img/lot-5.jpg', starting_price = 7500, completed_at = (created_at + INTERVAL 25 HOUR), bet_step = 50, user_id = 2, category_id = 4;
INSERT INTO lots
SET created_at = NOW(), title = 'Маска Oakley Canopy', description = 'Горнолыжная маска Oakley Canopy идеально подходит для катания в солнечную погоду.', image = 'img/lot-6.jpg', starting_price = 5400, completed_at = (created_at + INTERVAL 48 HOUR), bet_step = 100, user_id = 1, category_id = 6;

INSERT INTO bets
SET created_at = FROM_UNIXTIME(1555761000), price = 11499, user_id = 2, lot_id = 1;
INSERT INTO bets
SET created_at = FROM_UNIXTIME(1555761300), price = 11999, user_id = 3, lot_id = 1;

/*Получить все категории*/
SELECT * FROM categories;
/*или только названия*/
SELECT name FROM categories;

/*Получить самые новые, открытые лоты.
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории.*/
SELECT lots.title, lots.starting_price, lots.image, lots.bet_step, categories.name FROM lots
INNER JOIN categories ON lots.category_id = categories.id
ORDER BY created_at DESC LIMIT 3;

/*Показать лот по его id*/
SELECT lots.title, categories.name FROM lots
INNER JOIN categories ON lots.category_id = categories.id
WHERE lots.id = 1;

/*Обновить название лота по его идентификатору*/
UPDATE lots SET title = 'DC Ply Mens 2018/2019 Snowboard' WHERE id = 2;

/*Получить список самых свежих ставок для лота по его идентификатору*/
SELECT bets.id, bets.created_at, bets.price, users.login, lots.title FROM bets
INNER JOIN lots ON bets.lot_id = lots.id
INNER JOIN users ON bets.user_id = users.id
WHERE lots.id = 1
LIMIT 1;