# Laravel Language Loader

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cheecodes/language-detection.svg?style=flat-square)](https://packagist.org/packages/cheecodes/language-detection)
[![Total Downloads](https://img.shields.io/packagist/dt/cheecodes/language-detection.svg?style=flat-square)](https://packagist.org/packages/cheecodes/language-detection)

This package allows you to set the application language based on request parameters like Accept-Language, a cookie, the session values.
You can easily extend the detection with your own loaders aswell.
## Installation

You can install the package via composer:

```bash
composer require cheecodes/language-detection
```

You should then publish the config

```bash
php artisan config:publish
```

## Usage

#### Globally

If you want to use the Middleware globally, you can register it in `App\Http\Kernel` like this

```php
protected $middleware = [
        // ...
        DetectLanguage::class,
        // ...
    ];
```

#### Aliasing

To use an alias for this middleware, register it in your `$routeMiddleware` in the Kernel

```php
protected $routeMiddleware = [
    // ...
    'language' => DetectLanguage::class,
    // ...
];
```

#### Different stack of loaders

The `DetectLanguage` Middleware accepts a parameter that determines the group of loaders to be used.
Those groups can be defined in the config like this:

```php
'loaders'   => [
    // Default group
    'default' => [
        FromHeader::class,
        FromQueryParameter::class,
        FromSession::class,
        FromCookie::class,
    ],
    // custom group
    'custom' => [
        FromHeader::class,
    ]
],
```

The names can be chosen as you see fit. 

To use your custom loader stack, I firstly recommend you alias the middleware to something like `language`.
You can then use it in your routes file like this:

```php
Route::group('/admin', function() {
    // Your routes go here
})->middleware('language:custom'); // Changing the loader stack to "custom"
```

#### Writing your own loader

Writing your own loader is straightforward. All you need to do is write a class that implements `CheeCodes\LanguageDetection\Loader\LoaderContract`.
This interface only exposes one single method:

```php
public function load(Request $request, array $config): ?string
```

All you need to do is return a language key if your loader matched, or `null` if it didn't.

If you return a string and that string is in the allowed languages array, loader processing is stopped.

If you return null, the next loader is called or the default language is used if no further loader is in the current stack.


To register your custom loader, just add the FQDN to the loader stack:
```php
'loaders' => [
    'default' => [
        // ...
        Acme\Loaders\MyCustomLoader::class,    
    ]
];
```

The loaders are resolved with Laravels IoC Container, so you can easily use constructor injection like you're used to.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email [fabian@chee.codes](mailto:fabian@chee.codes) instead of using the issue tracker.

## Credits

- [Fabian Bettag](https://github.com/cheecodes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
