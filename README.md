# laravel-kraken
Kraken trading API for Laravel

## Install

#### Install via Composer

```
composer require decode9/laravel-kraken
```

Add the following lines to your `config/app.php`

```php
'providers' => [
        ...
        decode9\kraken\KrakenServiceProvider::class,
        ...
    ],


 'aliases' => [
        ...
        'Kraken' => decode9\kraken\KrakenAPIFacade::class,
    ],
```

## Version

0.1.1!
