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

/**
 * Форматирует стартовую цену лота (округляет, отделяет разряды, добавляет валюту)
 * @param string $number Стартовая цена лота
 * @param string $currency HTML-спецсимвол знака валюты
 * @return string Отформатированная цена
 */
function price_format($number, $currency)
{
    $number = ceil($number);

    if ($number > 1000) {
        $number = number_format($number, 0, '', ' ');
    };

    return $number . ' ' . $currency;
}

/**
 * Обрезает теги в получаемом от пользователя тексте
 * @param string $str Данные полученые от пользователя
 * @return string Отформатированые пользовательские данные
 */
function esc($str)
{
    $text = strip_tags($str);
    return $text;
}

/**
 * Вычисляет время до окончания лота и выводит его в заданом формате
 * @param string $ending_time Дата и время окончания лота (в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС)
 * @param string $format Принимает два заначения - 'minute' (возвращает оставшееся время с точностью до минут - ЧЧ:ММ) и 'second' (возвращает оставшееся время с точностью до секунд - ЧЧ:ММ:СС)
 * @return string Время до окончания лота в заданом формате
 */
function calculate_time_lot_ending($ending_time, $format)
{
    $ending_time = strtotime($ending_time);
    $seconds_to_ending = $ending_time - time();
    $hours = floor($seconds_to_ending / 3600);
    $minutes = floor(($seconds_to_ending % 3600) / 60);
    $seconds = $seconds_to_ending - 3600 * $hours - $minutes * 60;
    if ($format === 'minute') {
        $ending_time = sprintf('%02d:%02d', $hours, $minutes);
    } elseif ($format === 'second') {
        $ending_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
    return $ending_time;
}


/**
 * Проверяет сколько времени осталось до окончания лота - больше или меньше часа
 * @param string $ending_time Дата и время окончания лота (в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС)
 * @return bool true - если осталось меньше часа, иначе - false
 */
function check_warning_time($ending_time)
{
    $ending_time = strtotime($ending_time);
    $seconds_to_ending = $ending_time - time();
    return ($seconds_to_ending < 3600) ? true : false;
}

/**
 * Вычисляет время прошедшее с момента совершения ставки и выводит его в нужном формате
 * @param string $last_bets_time Дата совершения ставки (в формате ГГГГ-ММ-ДД ЧЧ:ММ:СС)
 * @return string Время прошедшее с момента совершения ставки в нужном формате
 */
function calculate_time_last_bets($last_bets_time)
{
    $last_bets_time = strtotime($last_bets_time);
    $seconds_to_now = time() - $last_bets_time;
    $days = floor($seconds_to_now / 86400);
    $hours = floor($seconds_to_now / 3600);
    $minutes = floor(($seconds_to_now % 3600) / 60);
    $seconds = $seconds_to_now - 3600 * $hours - $minutes * 60;

    if ($days == 1) {
        return 'Вчера, в ' . date('H:i', $last_bets_time);
    } elseif ($days > 1) {
        return date('d.m.y в H:i', $last_bets_time);
    } elseif ($hours == 1) {
        return 'Час назад';
    } elseif ($hours && $hours < 24) {
        return $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов') . ' назад';
    } elseif ($minutes) {
        return $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . ' назад';
    } elseif ($seconds) {
        return 'Сейчас';
    } else {
        return date('d.m.y в H:i', $last_bets_time);
    }
}

/**
 * Получает имя категории по id, полученому из ассоциативного массива $_GET
 * @param array $categories Массив данных, содержащий имя категории и ее id
 * @param int $id Значение id, полученное из ассоциативного массива $_GET
 * @return string Имя категории соответствующее id, полученому из ассоциативного массива $_GET
 */
function get_category_name($categories, $id)
{
    foreach ($categories as $value) {
        if ($value['id'] === strval($id)) {
            $category_name = $value['name'];
        }
    }
    return $category_name;
}

/**
 * Проверяет заполнение обязательных полей форм
 * @param array $required Массив, содержащий атрибуты name обязатеных полей
 * @param array $errors Массив для записи ошибки, возникающей при получении пустого поля
 * @return array Список ошибок, полученных при валидации
 */
function validation_required_fields($required, $errors)
{
    foreach ($required as $key) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }
    return $errors;
}

/**
 * Проверяет значения числовых полей формы на то, что их значения больше нуля
 * @param array $field Массив, содержащий атрибуты name полей
 * @param array $errors Массив для записи ошибки, возникающей при получении числа меньше или равного нулю
 * @return array Список ошибок, полученных при валидации
 */
function validation_numeric_fields($field, $errors)
{
    foreach ($field as $key) {
        if ($_POST[$key] <= 0) {
            $errors[$key] = 'Содержимое поля должно быть целым числом больше ноля';
        }
    }
    return $errors;
}