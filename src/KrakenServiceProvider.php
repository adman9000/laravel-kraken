<?php

namespace decode9\kraken;

use Illuminate\Support\ServiceProvider;

class KrakenServiceProvider extends ServiceProvider 
{
	public function boot()
	{
		$this->publishes([
			__DIR__.'/config/kraken.php' => config_path('kraken.php')
		]);
	}

	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/config/kraken.php', 'kraken');
		$this->app->bind('kraken', function() {
			return new KrakenAPI(config('kraken'));
		});
	}
}
