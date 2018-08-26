<?php 
namespace adman9000\kraken;

class KrakenAPI
{
    protected $key;     // API key
    protected $secret;  // API secret
    protected $url;     // API base URL
    protected $version; // API version
    protected $curl;    // curl handle

    /**
     * Constructor for KrakenAPI
     *
     * @param string $key API key
     * @param string $secret API secret
     * @param string $url base URL for Kraken API
     * @param string $version API version
     * @param bool $sslverify enable/disable SSL peer verification.  disable if using beta.api.kraken.com
     */
    function __construct($config=false, $url='https://api.kraken.com', $version='0', $sslverify=true)
    {
        if($config) {
        $this->key = $config['kraken_key'];
        $this->secret = $config['kraken_secret'];
    }
        $this->url = $url;
        $this->version = $version;
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_SSL_VERIFYPEER => $sslverify,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_USERAGENT => 'Kraken PHP API Agent',
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true)
        );
    }

    function __destruct()
    {
        curl_close($this->curl);
    }

    function setAPI($key, $secret) {

       $this->key = $key;
       $this->secret = $secret;
    }
	   
   

    /**
     ---------- PUBLIC FUNCTIONS ----------
    * getTicker
    * getTickers
    * getAssetInfo (for backwards compatibility)
    * getCurrencies (calls getAssetInfo)
    * getAssetPairs (for backwards compatibility)
    * getMarkets (calls getAssetPairs)
    *
    *
    *
    * 
     **/


     /**
     * Get ticker
     *
     * @param asset pair code
     * @return asset pair ticker info
     */
    public function getTicker($code)
    {
        return $this->queryPublic('Ticker', array(
            'pair' => $code
        ));
    }

     /**
     * Get tickers
     *
     * @param array $pairs
     * @return array of ticker info by pair codes
     */
    public function getTickers(array $pairs)
    {
        $codes = implode(',', $pairs);
        return $this->queryPublic('Ticker', [
            'pair' => $codes
        ]);
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
     * Get currencies listed on this exchange
     *
     * @return array of asset names and their info
     */
    public function getCurrencies() {
        return $this->getAssetInfo();
    }
	
	 /**
     * Get tradable asset pairs
     *
     * @return array of pair names and their info
     */
    public function getAssetPairs(array $pairs=null, $info='info')
    {
        if(is_array($pairs)) $code = implode('', $pairs);
        if(isset($code)) {
            return $this->queryPublic('AssetPairs', array(
                'pair' => $code,
                'info' => $info
        ));
    }
    else {
        return $this->queryPublic('AssetPairs');
        }
    }

    /**
     * getMarkets()
     * @return array of trading pairs available on this exchange
     **/
    public function getMarkets()
    {
        return $this->getAssetPairs();
    }
	


    //------ PRIVATE API CALLS ----------
    /*
    * getBalances
    * getRecentTrades
    * getOpenOrders
    * getClosedOrders
    * getAllOrders
    * addOrder (for backwards compatibility)
    * trade (calls addOrder)
    * marketSell
    * marketBuy
    * limitSell
    * limitBuy
    */

   /** Get Balances
     * 
     * @return array of asset balances by code
    **/
    public function getBalances() {

        return $this->queryPrivate("Balance");
    }

    /**
     * Get trades 
     *
     * @return mixed
     * @throws \Exception
     */
    public function getRecentTrades()
    {

        $b = $this->queryPrivate('TradesHistory');
        return $b;

    }

    /**
     * Get open orders 
     *
     * @return mixed
     * @throws \Exception
     */
    public function getOpenOrders()
    {


        $b = $this->queryPrivate('OpenOrders');

        return $b;

    }

    /**
     * Get closed orders 
     *
     * @return mixed
     * @throws \Exception
     */
    public function getClosedOrders()
    {


        $b = $this->queryPrivate('ClosedOrders');
        return $b;

    }

    /**
     * Get all orders - not available in API
     *
     * @return false
     */
    public function getAllOrders() {
        return false;
    }


    /** 
     * Add Order
     * 
     * @param  type = type of order (buy/sell)
     * @param    ordertype = order type:
                market
                limit (price = limit price)
                stop-loss (price = stop loss price)
                take-profit (price = take profit price)
                stop-loss-profit (price = stop loss price, price2 = take profit price)
                stop-loss-profit-limit (price = stop loss price, price2 = take profit price)
                stop-loss-limit (price = stop loss trigger price, price2 = triggered limit price)
                take-profit-limit (price = take profit trigger price, price2 = triggered limit price)
                trailing-stop (price = trailing stop offset)
                trailing-stop-limit (price = trailing stop offset, price2 = triggered limit offset)
                stop-loss-and-limit (price = stop loss price, price2 = limit price)
                settle-position
      * @param   price = price (optional.  dependent upon ordertype)
      * @param   price2 = secondary price (optional.  dependent upon ordertype)
      * @param   volume = order volume in lots
      **/

        public function addOrder($pair, $type, $ordertype, $price=false, $price2=false, $volume) {

            $code = implode('', $pair);
            return $this->queryPrivate('AddOrder', array(
                'pair' => $code,
                'type' => $type,
                'ordertype' => $ordertype,
                'price' => $price,
                'price2' => $price2,
                'volume' => $volume
            ));
        }

        /**
         * Make a trade
         * calls addOrder
        **/
        public function trade($pair, $type, $ordertype, $price=false, $price2=false, $volume) {
            return $this->addOrder($pair, $type, $ordertype, $price, $price2, $volume);
        }


        /** Buy Market
         * Buy asset at the market price
         * @param asset pair
         * @param volume
         * @return order info
         **/
        public function buyMarket($pair, $volume) {
            return $this->addOrder($pair, 'buy', 'market', false, false, $volume);
        }

        /** Sell Market
         * Sell asset at the market price
         * @param asset pair
         * @param volume
         * @return order info
         **/

         public function sellMarket($pair, $volume) {
            return $this->addOrder($pair, 'sell', 'market', false, false, $volume);
        }


/**
       * Deposit Address
       * @param string $symbol   Asset symbol
       * @param string $method   Asset name?? If not set, find a method from the API
       * @return mixed
       **/
      public function depositAddress($symbol, $method=false) {
          if(!$method) {
              $result = $this->queryPrivate("DepositMethods", ['asset' => $symbol]);
              $method = $result['result'][0]['method'];
          }
          return $this->queryPrivate("DepositAddresses", ['asset' => $symbol, 'method' => $method]);
       }
      /**
       * View Deposits
       * @param string $symbol   Asset symbol
       * @param string $method   Asset name?? If not set, find a method from the API
       * @return mixed
       **/
      public function viewDeposits($symbol, $method=false) {
          if(!$method) {
              $result = $this->queryPrivate("DepositMethods", ['asset' => $symbol]);
              $method = $result['result'][0]['method'];
          }
          return $this->queryPrivate("DepositStatus", ['asset' => $symbol, 'method' => $method]);
       }
       /**
       * Withdraw Info
       * @param string $symbol   Asset symbol
       * @param string $method   Asset name?? If not set, find a method from the API
       * @return mixed
       **/
      public function WithdrawInfo($symbol, $key, $amount=0) {
         return $this->queryPrivate("WithdrawInfo", ['asset' => $symbol, 'key' => $key, 'amount' => $amount]);
       }
       /**
       * Withdraw Funds
       * @param string $symbol   Asset symbol
       * @param string $method   Asset name?? If not set, find a method from the API
       * @return mixed
       **/
      public function WithdrawFunds($symbol, $key, $amount) {
         return $this->queryPrivate("Withdraw", ['asset' => $symbol, 'key' => $key, 'amount' => $amount]);
       }
      /**
       * View Deposits
       * @param string $symbol   Asset symbol
       * @param string $method   Asset name?? If not set, find a method from the API
       * @return mixed
       **/
       public function viewWithdraw($symbol, $key, $method=false) {
           if(!$method) {
              $result = $this->WithdrawInfo($symbol, $key);
              $method = $result['result']['method'];
          }
          return $this->queryPrivate("WithdrawStatus", ['asset' => $symbol, 'method' => $method]);
       }

       //To match other APIs
       public function withdrawalHistory($symbol, $key, $method=false) {
            return $this->viewWithdraw($symbol, $key, $method);
       }

        /**
        * Deposit History
        * @param string $symbol   Asset symbol
        * @return mixed
        **/
        public function depositHistory($symbol=false) {
            return $this->queryPrivate("DepositStatus", ['asset' => $symbol]);
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
            throw new \Exception('CURL error: ' . curl_error($this->curl));

        // decode results
        $result = json_decode($result, true);
        if(!is_array($result))
            throw new \Exception('JSON decode error');

        return $result;
    }



}