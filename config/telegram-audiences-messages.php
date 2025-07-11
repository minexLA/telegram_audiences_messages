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
    | Map of filters keys and their classes
    |------------------------------------------------------------------
    |
    | Format:
    | [ "key" => "App\\Filters\\Filter.php" ]
    |
    */
    'filters' => [
        'example' => 'App\\Filters\\Filter.php',
    ],

    /*
    | ------------------------------------------------------------------
    | Number of messages sent for per 1 second. Will be used to calculate delay for mass sending
    |------------------------------------------------------------------
    |
    */
    'messages_per_sec' => 15,

    /*
    | ------------------------------------------------------------------
    | Helper class that used when sending message. Must implement Minex\TelegramAudiencesMessages\Interfaces\ISendHelper interface.
    | Now supported only beforeEachSend method.
    |
    | Example: 'helper' => AppHelpers\SomeHelper::class,
    |------------------------------------------------------------------
    |
    */
    'helper' => null,
];
