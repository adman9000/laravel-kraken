# laravel-kraken

> **This package is abandoned and no longer maintained.**
> It has not been updated since August 2018 and is incompatible with modern PHP and Laravel versions.
>
> **Recommended alternatives:**
> - [`butschster/kraken-api-client`](https://packagist.org/packages/butschster/kraken-api-client) — actively maintained Laravel integration with REST and WebSocket support (PHP 8.1+, Laravel 8–11)
> - [`payward/kraken-api-client`](https://packagist.org/packages/payward/kraken-api-client) — the official Kraken PHP client library

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
