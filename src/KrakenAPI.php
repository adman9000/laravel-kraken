<?php 
namespace adman9000\kraken;

class KrakenAPI {
    /**
     * API Parameters
     * @var array
     */
    protected $params = array();
	
	protected $curl;
	protected $url;
	protected $version;
	
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
				
		
		$this->curl = curl_init();
        curl_setopt_array($this->curl, array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'Kraken PHP API Agent',
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true)
        );
		
		$this->url = "https://api.kraken.com";
		$this->version = 0;
    }
	
	/**
     * Get asset info
     *
     * @return array of asset names and their info
     */
    public function getAssetInfo()
    {
        return $this->queryPublic('Assets');
    }
	
	 /**
     * Get tradable asset pairs
     *
     * @return array of pair names and their info
     */
    public function getAssetPairs(array $pairs=null, $info='info')
    {
        $csv = empty($pairs) ? null : implode(',', $pairs);
        return $this->queryPublic('AssetPairs', array(
            'pair' => "XBTEUR"
        ));
    }
	
	 /**
     * Get ticker
     *
     * @return array of pair names and their info
     */
    public function getTicker(array $pairs=null, $info='info')
    {
        $code = implode('', $pairs);
        return $this->queryPublic('Ticker', array(
            'pair' => $code
        ));
    }
	
	    /**
     * Query public methods
     *
     * @param string $method method name
     * @param array $request request parameters
     * @return array request result on success
     * @throws \Exception
     */
    private function queryPublic($method, array $request = array())
    {
        // build the POST data string
        $postdata = http_build_query($request, '', '&');
        // make request
        curl_setopt($this->curl, CURLOPT_URL, $this->url . '/' . $this->version . '/public/' . $method);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array());
        $result = curl_exec($this->curl);
        if($result===false)
            throw new \Exception('CURL error: ' . curl_error($this->curl));
        // decode results
        $result = json_decode($result, true);
        if(!is_array($result))
            throw new \Exception('JSON decode error');
        return $result;
    }
	
	   /**
     * Query private methods
     *
     * @param string $path method path
     * @param array $request request parameters
     * @return array request result on success
     * @throws KrakenAPIException
     */
    function queryPrivate($method, array $request = array())
    {
        if(!isset($request['nonce'])) {
            // generate a 64 bit nonce using a timestamp at microsecond resolution
            // string functions are used to avoid problems on 32 bit systems
            $nonce = explode(' ', microtime());
            $request['nonce'] = $nonce[1] . str_pad(substr($nonce[0], 2, 6), 6, '0');
        }

        // build the POST data string
        $postdata = http_build_query($request, '', '&');

        // set API key and sign the message
        $path = '/' . $this->version . '/private/' . $method;
        $sign = hash_hmac('sha512', $path . hash('sha256', $request['nonce'] . $postdata, true), base64_decode($this->secret), true);
        $headers = array(
            'API-Key: ' . $this->key,
            'API-Sign: ' . base64_encode($sign)
        );

        // make request
        curl_setopt($this->curl, CURLOPT_URL, $this->url . $path);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($this->curl);
        if($result===false)
            throw new KrakenAPIException('CURL error: ' . curl_error($this->curl));

        // decode results
        $result = json_decode($result, true);
        if(!is_array($result))
            throw new KrakenAPIException('JSON decode error');

        return $result;
    }
}