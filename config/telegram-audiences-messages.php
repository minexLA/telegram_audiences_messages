<?php

return [
    /*
    | ------------------------------------------------------------------
    | List of audiences types
    |------------------------------------------------------------------
    |
    | Each audience has:
    | class - Model class
    | bot_token - token of telegram bot
    | filters - array of filters that this audience support
    |
    */

    'audiences' => [
        'example' => [
            'class' => 'App\\Models\\Model.php',
            'bot_token' => 'bot_token',
            'filters' => [
                'example',
            ],
        ],
    ],

    /*
    | ------------------------------------------------------------------
    | Map of filters keys and classes
    |------------------------------------------------------------------
    |
    | Format:
    | [ key => class ]
    |
    */
    'filters' => [
        'example' => 'App\\Filters\\Filter.php',
    ],
];
