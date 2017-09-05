<?php namespace adman9000\kraken;

/**
 * @author  adman9000
 */
use Illuminate\Support\ServiceProvider;

class KrakenServiceProvider extends ServiceProvider {

	public function boot() 
	{
		$this->publishes([
			__DIR__.'/config/kraken.php' => config_path('kraken.php')
		]);
	} // boot

	public function register() 
	{
		$this->app->bind('kraken', function($app) {
			return new KrakenAPI($app);
		});

		$this->mergeConfigFrom(
			__DIR__.'/config/kraken.php', 'kraken');

	} // register
}