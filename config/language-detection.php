<?php

/*
 * You can place your custom package configuration in here.
 */

use CheeCodes\LanguageDetection\Loader\FromAcceptLanguage;
use CheeCodes\LanguageDetection\Loader\FromCookie;
use CheeCodes\LanguageDetection\Loader\FromHeader;
use CheeCodes\LanguageDetection\Loader\FromQueryParameter;
use CheeCodes\LanguageDetection\Loader\FromSession;

return [
    /**
     * The default language if no loader was able to match
     */
    'default'         => 'en',

    /**
     * An array of allowed language keys
     */
    'allowed'         => ['en'],

    /**
     * Your Array of loaders.
     * Loaders are sequentially called from top to bottom,
     * and the first loader to return an allowed language will determine the language used.
     *
     * You can define multiple groups for different use-cases, like
     * different stacks for web and api requests, and specify them when using the middleware.
     */
    'loaders'         => [
        'default' => [
            FromHeader::class,
            FromQueryParameter::class,
            FromSession::class,
            FromCookie::class,
            FromAcceptLanguage::class,
        ],
    ],

    /**
     * Placeholder for now.
     */
    'storage'         => [
        'default' => [],
    ],

    /**
     * Configuration for the FromQueryParameter Loader
     */
    'url'             => [
        'parameter' => 'lang',
    ],

    /**
     * Configuration for the FromHeader Loader
     */
    'header'          => [
        'name' => 'x-lang',
    ],

    /**
     * Configuration for the FromSession Loader
     */
    'session'         => [
        'key' => 'language',
    ],

    /**
     * Configuration for the FromCookie Loader
     */
    'cookie'          => [
        'key' => 'language',
        // 'lifetime' => 10 * 60,
    ],

    /**
     * Configuration for the FromAcceptLanguage Loader
     */
    'accept-language' => [
        'strict' => true,
    ],
];
