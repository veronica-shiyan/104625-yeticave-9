<?php
require_once('helpers.php');
session_start();

$link = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($link, "utf8");

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

// Функция для выведения ошибки при запросе к БД
function show_queries_error($error)
{
    $content = include_template('error.php', ['error' => $error]);
    print($content);
    die;
}

// Функция для получения массива данных из БД
function get_data_array($link, $sql)
{
    $result = mysqli_query($link, $sql);

    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        show_queries_error(mysqli_error($link));
        return null;
    }
}

;

// Функция для получение списка категорий
function get_categories($link)
{
    if (!$link) {
        show_queries_error(mysqli_connect_error());
        return null;
    } else {
        $result = mysqli_query($link, 'SELECT * FROM categories');

        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            show_queries_error(mysqli_error($link));
            return null;
        }
    }
}

;

//Функция для получение списка лотов
function get_data($link, $sql)
{
    if (!$link) {
        show_queries_error(mysqli_connect_error());
        return null;
    } else {
        $result = mysqli_query($link, $sql);

        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);
        } else {
            show_queries_error(mysqli_error($link));
            return null;
        }
    }
}

;

// Создание layout
function create_layout($layout_data)
{
    $layout_content = include_template('layout.php', $layout_data);
    print($layout_content);
}

;