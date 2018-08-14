# laravelkraken
Kraken trading API for Laravel

This is a fork from adman9000

## Install

#### Install via Composer

```
composer require decode9/laravelkraken
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

0.0000000000000000001!
