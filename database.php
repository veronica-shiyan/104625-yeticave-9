<?php
require_once('helpers.php');

//Массив данных для создания БД
$db_data = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave'
];

/**
 * Создает соединение с БД
 * @param array $db_data Массив данных для создания БД
 * @return object Объект, представляющий подключение к серверу MySQL
 */
function db_connect ($db_data) {
    $link = mysqli_connect($db_data['host'], $db_data['user'], $db_data['password'], $db_data['database']);
    mysqli_set_charset($link, "utf8");
    return $link;
}

/**
 * Подключает шаблон error.php при получении ошибки соединения с БД
 * @param string $error Строка с описанием ошибки
 * @return string Итоговый HTML
 */
function show_queries_error($error)
{
    $content = include_template('error.php', ['error' => $error]);
    print($content);
    die;
}

/**
 * Получает массива данных из БД или сообщает об ошибке запроса
 * @param object $link Объект, представляющий подключение к серверу MySQL
 * @param string $sql Запрос к БД
 * @return array Массив данных
 */
function get_data_array($link, $sql)
{
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        return show_queries_error(mysqli_error($link));
    }
}

/**
 * Получает список категорий из БД
 * @param object $link Объект, представляющий подключение к серверу MySQL
 * @return array Массив со списком категорий
 */
function get_categories ($link) {
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT * FROM categories';
        return get_data_array($link, $sql);
    }
}

/**
 * Получает список актуальных лотов из БД
 * @param object $link Объект, представляющий подключение к серверу MySQL
 * @param int $time Время в формате unix, относительно которого рассматривается актуальность лота
 * @return array Массив со списком лотов
 */
function get_lots ($link, $time)
{
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id 
WHERE unix_timestamp(completed_at) > ' . $time . '
ORDER BY created_at DESC LIMIT 6';
        return get_data_array($link, $sql);
    }
}

/**
 * Получает из БД общее количество лотов для пагинации
 * @param object $link Объект, представляющий подключение к серверу MySQL
 * @param int $time Время в формате unix, относительно которого рассматривается актуальность лота
 * @param int $id Значение id категории
 * @return string Количество лотов
 */
function get_items ($link, $time, $id)
{
    $result = mysqli_query($link, 'SELECT COUNT(*) as cnt FROM lots 
INNER JOIN categories ON lots.category_id = categories.id 
WHERE unix_timestamp(lots.completed_at) > ' . $time . ' AND categories.id = ' . $id);
    return mysqli_fetch_assoc($result)['cnt'];
}

//Функция для получение списка лотов по категориям c пагинацией
function get_lots_in_category ($link, $time, $id, $page_items, $offset)
{
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT * FROM categories 
INNER JOIN lots ON lots.category_id = categories.id 
WHERE unix_timestamp(lots.completed_at) > ' . $time . ' AND categories.id = ' . $id . ' 
ORDER BY created_at DESC LIMIT ' . $page_items . ' OFFSET ' . $offset;
        return get_data_array($link, $sql);
    }
}

//Функция для получение лота по id
function get_lots_on_id ($link, $id)
{
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT * FROM lots 
INNER JOIN users ON users.id = lots.user_id 
INNER JOIN categories ON lots.category_id = categories.id 
WHERE lots.id = ' . $id;
        return get_data_array($link, $sql);
    }
}

//Функция для получение ставок по id лота
function get_bets ($link, $id)
{
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT b.bets_created_at, b.price, b.bets_user_id, b.lot_id, u.login FROM bets as b 
INNER JOIN lots as l ON l.id = b.lot_id
INNER JOIN  users as u ON u.id = b.bets_user_id  
WHERE l.id = ' . $id . ' 
ORDER BY b.bets_created_at DESC';
        return get_data_array($link, $sql);
    }
}

//Функция для получение списка лотов
function get_winners ($link, $time)
{
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT l.id, l.title, b.bets_user_id, u.login, u.email, b.price FROM lots as l 
INNER JOIN bets as b ON l.id = b.lot_id 
INNER JOIN  users as u ON u.id = b.bets_user_id 
WHERE unix_timestamp(l.completed_at) <= ' . $time . ' 
AND l.winner_id IS NULL 
AND  b.price = l.starting_price';
        return get_data_array($link, $sql);
    }
}

//Функция для получение списка ставок пользователя
function get_user_bets ($link, $user_id)
{
    if (!$link) {
        return show_queries_error(mysqli_connect_error());
    } else {
        $sql = 'SELECT * FROM bets as b 
INNER JOIN lots as l ON l.id = b.lot_id
INNER JOIN  users as u ON u.id = l.user_id 
INNER JOIN  categories as c ON c.id = l.category_id 
WHERE b.bets_user_id = ' . $user_id . ' 
ORDER BY b.bets_created_at DESC';
        return get_data_array($link, $sql);
    }
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}