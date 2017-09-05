<?php 
namespace adman9000\kraken;

class KrakenAPI {
    /**
     * API Parameters
     * @var array
     */
    protected $params = array();
    /**
     * API Url String
     * @var string
     */
    protected $urlString = null;
    /**
     * @var string
     */
    protected $response;
 
    /**
     * Constructor
     */
    public function __construct() 
    {
       $this->params = [
                'key' => config('kraken.kraken_key'),
                'secret' => config('kraken.kraken_secret'),
                'responseType' => 'JSON'
                ];
    }
	
	function displayConfig() {
		var_dump($this->params);
	}
}