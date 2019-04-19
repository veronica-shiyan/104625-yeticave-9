<?php
require_once('helpers.php');

$title = 'Главная';
$is_auth = rand(0, 1);
$user_name = 'Veronica';
$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [
    ['name' => '2014 Rossignol District Snowboard',
        'categories' => 'Доски и лыжи',
        'price' => 10999,
        'url' => 'img/lot-1.jpg'
    ],
    ['name' => 'DC Ply Mens 2016/2017 Snowboard',
        'categories' => 'Доски и лыжи',
        'price' => 159999,
        'url' => 'img/lot-2.jpg'
    ],
    ['name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'categories' => 'Крепления',
        'price' => 8000,
        'url' => 'img/lot-3.jpg'
    ],
    ['name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'categories' => 'Ботинки',
        'price' => 10999,
        'url' => 'img/lot-4.jpg'
    ],
    ['name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'categories' => 'Одежда',
        'price' => 7500,
        'url' => 'img/lot-5.jpg'
    ],
    ['name' => 'Маска Oakley Canopy',
        'categories' => 'Разное',
        'price' => 5400,
        'url' => 'img/lot-6.jpg'
    ]
];

function price_format($number)
{
    $number = ceil($number);

    if ($number > 1000) {
        $number = number_format($number, 0, '', ' ');
    };

    return $number . '  &#8381';
};

function esc($str)
{
    $text = strip_tags($str);
    return $text;
};

$content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $content,
    'title' => $title,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'categories' => $categories,
    'lots' => $lots
]);

print($layout_content);