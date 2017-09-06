# laravelkraken
Kraken trading API for Laravel

## Install

#### Install via Composer

```
composer require adman9000/laravelkraken
```

Add the following lines to your `config/app.php`

```php
'providers' => [
        ...
        adman9000\kraken\KrakenServiceProvider::class,
        ...
    ],


 'aliases' => [
        ...
        'Kraken' => adman9000\kraken\KrakenAPIFacade::class,
    ],
```

## Version

0.0000000000000000001!
