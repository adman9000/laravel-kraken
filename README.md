# laravel-kraken
Kraken trading API for Laravel

## Install

#### Install via Composer

```
composer require adman9000/laravel-kraken
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

0.1

##Features

Price tickers, balances, trades, deposits and withdrawals