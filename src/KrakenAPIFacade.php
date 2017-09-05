<?php namespace adman9000\kraken;

use Illuminate\Support\Facades\Facade;

class KrakenAPIFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'kraken';
	}
}