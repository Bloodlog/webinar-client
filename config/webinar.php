<?php

return [
    'base_url' => 'https://userapi.webinar.ru',
    'token' => 'Token',
    'version_api' => 'v3', // Версия Webinar API
    'api' => [
        'registration' => [
            'form-transform' => [ // webinar.ru использует ключи вместо наименования полей. После получения ключей для регистрации нужно сопоставить поля
                'additionalFields' => [
                    //'62899cefc8855544723baae88cbfce9c' => 'lname', // Отчество
                ],
            ],
        ],
    ],
];