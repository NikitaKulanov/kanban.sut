<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Data to work the application
    |--------------------------------------------------------------------------
    |
    | This data is required for the application to work.
    |
    */

    'rights' => [
        [
            'id' => 1,
            'title' => 'Нет доступа.'
        ],
        [
            'id' => 2,
            'title' => 'Доступ на чтение.'
        ],
        [
            'id' => 3,
            'title' => 'Доступ на чтение. Доступ на размещение сообщений, файлов и ссылок, а также их редактирование.'
        ],
        [
            'id' => 4,
            'title' => 'Полный доступ.'
        ],
        [
            'id' => 5,
            'title' => 'Полный доступ как у администратора.'
        ],
    ],

    'rights_for_binding' => [3, 4, 5]
];
