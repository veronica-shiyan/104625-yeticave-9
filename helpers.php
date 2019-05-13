<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

// Функция для форматирования цены
function price_format($number)
{
    $number = ceil($number);

    if ($number > 1000) {
        $number = number_format($number, 0, '', ' ');
    };

    return $number . '  &#8381';
}

;

function price_format_no_currency($number)
{
    $number = ceil($number);

    if ($number > 1000) {
        $number = number_format($number, 0, '', ' ');
    };

    return $number;
}

;


// Функция для обрезки тегов в получаемом от пользователя тексте
function esc($str)
{
    $text = strip_tags($str);
    return $text;
}

;

// Функции для выведения времени до окончания лота
function calculate_time_lot_ending($ending_time, $format)
{
    $seconds_to_ending = $ending_time - time();
    $hours = floor($seconds_to_ending / 3600);
    $minutes = floor(($seconds_to_ending % 3600) / 60);
    $seconds = $seconds_to_ending - 3600 * $hours - $minutes * 60;
    if ($format === 'minute') {
        return sprintf('%02d:%02d', $hours, $minutes);
    } else {
        if ($format === 'second') {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
    }
}

function check_warning_time($ending_time)
{
    $seconds_to_ending = $ending_time - time();
    if ($seconds_to_ending < 3600) {
        return true;
    } else {
        false;
    }
}

// Функции для выведения времени, прошедшего с последней ставки
function calculate_time_last_bets($last_bets_time)
{
    $seconds_to_now = time() - $last_bets_time;
    $days = floor($seconds_to_now / 86400);
    $hours = floor($seconds_to_now / 3600);
    $minutes = floor(($seconds_to_now % 3600) / 60);
    $seconds = $seconds_to_now - 3600 * $hours - $minutes * 60;

    if ($days == 1) {
        return 'Вчера, в ' . date('H:i', $last_bets_time);
    } else {
        if ($days > 1) {
            return date('d.m.y в H:i', $last_bets_time);
        } else {
            if ($hours == 1) {
                return 'Час назад';
            } else {
                if ($hours && $hours < 24) {
                    return $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
                } else {
                    if ($minutes) {
                        return $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' назад';
                    } else {
                        if ($seconds) {
                            return 'Сейчас';
                        } else {
                            return date('d.m.y в H:i', $last_bets_time);
                        }
                    }
                }
            }
        }
    }
}

// Выведение названия категории в сортировке лотов по категории
function get_category_name($categories, $id)
{
    foreach ($categories as $value) {
        if ($value['id'] === strval($id)) {
            return $category_name = $value['name'];
        }
    }
}