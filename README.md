# laravel-kraken
Laravel implementation of the Kraken crypto exchange trading API

## Install

#### Install via Composer

```
composer require adman9000/laravel-kraken
```

Utilises autoloading in Laravel 5.5+. For older versions add the following lines to your `config/app.php`

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

## Features

Price tickers, balances, trades, deposits and withdrawals

## Notes

Kraken API is quite unreliable. If the endpoint is down it returns a json encode error, retrying will sometimes work.
