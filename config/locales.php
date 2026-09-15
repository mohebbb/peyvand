<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Every locale the application UI can be displayed in. The array key is
    | the Laravel locale code used by the translator, "name" is the label
    | shown in the language switcher (always written in its own language)
    | and "dir" controls the text direction of the rendered HTML.
    |
    | The default locale is configured via APP_LOCALE in the .env file and
    | can be switched at runtime through the /lang/{locale} route, which
    | remembers the choice in the visitor's session.
    |
    */

    'supported' => [
        'fa' => [
            'name' => 'فارسی',
            'dir' => 'rtl',
        ],
        'en' => [
            'name' => 'English',
            'dir' => 'ltr',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Key
    |--------------------------------------------------------------------------
    |
    | The session key used to remember the selected locale between requests.
    |
    */

    'session_key' => 'locale',

];
