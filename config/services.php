<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'telegram' => [
        'base_uri' => env('TELEGRAM_BASE_URI'),
        'token' => env('TELEGRAM_TOKEN'),
        'bot_username' => env('TELEGRAM_BOT_USERNAME'),
        'webhook' => env('TELEGRAM_WEBHOOK'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        'allowed_updates' => '["message"]',
    ],
    'trello' => [
        'base_uri' => env('TRELLO_BASE_URI'),
        'token' => env('TRELLO_TOKEN'),
        'board_id' => env('TRELLO_BOARD_ID'),
        'key' => env('TRELLO_KEY'),
        'webhook' => env('TRELLO_WEBHOOK'),
    ],

];
