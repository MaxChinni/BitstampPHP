<?php

namespace Mx17\BitstampPHP;

class BitstampNet
{
    private $curl;
    private $nonce;
    private $options = array(
        'bitstamp' => array(
            'customerId' => null,
            'apiKey' => null,
            'secret' => null),
        'proxy' => array(
            'host' => null,
            'port' => null,
            'username' => null,
            'password' => null
        )
    );
    private $currentCurrency = 'btcusd';
    private $allowedCurrencyPair = array('btcusd', 'btceur', 'eurusd', 'xrpusd', 'xrpeur',
            'xrpbtc', 'ltcusd', 'ltceur', 'ltcbtc', 'ethusd', 'etheur', 'ethbtc');
    private $transactionTypeHumanReadable = array(0 => 'buy', 1 => 'sell');
    private $userTransactionTypeHumanReadable = array(
         0 => 'deposit',
         1 => 'withdrawal',
         2 => 'market trade',
        14 => 'sub account transfer'
    );
    private $orderTypeHumanReadable = array(
         0 => 'buy',
         1 => 'sell'
    );

    public function __construct($options = array())
    {
        $this->options = array_replace_recursive($this->options, $options);
        $this->curl = curl_init();

        // Proxy
        if ($this->options['proxy']['host'] && $this->options['proxy']['port']) {
            $proxy = $this->options['proxy']['host'].':'.$this->options['proxy']['port'];
            curl_setopt($this->curl, CURLOPT_PROXY, $proxy);
            if ($this->options['proxy']['username'] && $this->options['proxy']['password']) {
                $auth = $this->options['proxy']['username'].':'.$this->options['proxy']['password'];
                curl_setopt($this->curl, CURLOPT_PROXYUSERPWD, $auth);
            }
        }

        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($this->curl, CURLOPT_HEADER, 1);
    }

    public function setCurrency($currency)
    {
        if (! in_array($currency, $this->allowedCurrencyPair)) {
            throw new Exception('impossible parameter');
        }
        $this->currentCurrency = $currency;
    }

    public function ticker()
    {
        return $this->get("https://www.bitstamp.net/api/v2/ticker/{$this->currentCurrency}/");
    }

    public function tickerHour()
    {
        return $this->get("https://www.bitstamp.net/api/v2/ticker_hour/{$this->currentCurrency}/");
    }

    public function orderBook()
    {
        return $this->get("https://www.bitstamp.net/api/v2/order_book/{$this->currentCurrency}/");
    }

    public function transactions()
    {
        $url = "https://www.bitstamp.net/api/v2/transactions/{$this->currentCurrency}/";
        $data = $this->get($url);

        // Transaction type
        foreach ($data as $c => $transaction) {
            $transaction['type_human_readable'] =
                $this->transactionTypeHumanReadable[$transaction['type']];
            $data[$c] = $transaction;
        }

        return $data;
    }

    public function balance()
    {
        $url = "https://www.bitstamp.net/api/v2/balance/{$this->currentCurrency}/";

        return $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce));
    }

    public function userTransactions($offset, $limit, $sort)
    {
        $url = "https://www.bitstamp.net/api/v2/user_transactions/{$this->currentCurrency}/";

        if (! is_int($offset)) {
            throw new \Exception('impossible offset value "'.$offset.'"');
        }
        if (! is_int($limit) || $limit > 1000) {
            throw new \Exception('impossible limit value');
        }
        if (! in_array($sort, array('asc', 'desc'))) {
            throw new \Exception('impossible sort value');
        }

        $data = $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce,
            'offset' => $offset,
            'limit' => $limit,
            'sort' => $sort));

        // Transaction type
        foreach ($data as $c => $transaction) {
            $transaction['type_human_readable'] =
                $this->userTransactionTypeHumanReadable[$transaction['type']];
            $data[$c] = $transaction;
        }

        return $data;
    }

    public function openOrders()
    {
        $url = "https://www.bitstamp.net/api/v2/open_orders/{$this->currentCurrency}/";

        $data = $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce));

        // Order type
        foreach ($data as $c => $order) {
            $order['type_human_readable'] =
                $this->orderTypeHumanReadable[$order['type']];
            $data[$c] = $order;
        }

        return $data;
    }

    public function orderStatus($orderId)
    {
        $url = 'https://www.bitstamp.net/api/order_status/';

        return $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce,
            'id' => $orderId));
    }

    public function cancelOrder($orderId)
    {
        $url = 'https://www.bitstamp.net/api/v2/cancel_order/';

        return $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce,
            'id' => $orderId));
    }

    public function cancelAllOrders()
    {
        $url = 'https://www.bitstamp.net/api/cancel_all_orders/';

        return $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce));
    }

    public function buy($amount, $price, $limitPrice = null, $dailyOrder = false)
    {
        $url = "https://www.bitstamp.net/api/v2/buy/{$this->currentCurrency}/";

        return $this->post($url, array(
            'key' => $this->options['bitstamp']['apiKey'],
            'signature' => $this->signature(),
            'nonce' => $this->nonce,
            'amount' => $amount,
            'price' => $price,
            'limit_price' => $limitPrice,
            'daily_order' => $dailyOrder));
    }

    public function conversionRate()
    {
        return $this->get("https://www.bitstamp.net/api/eur_usd/");
    }

    private function get($url, $parameters = array(), $json = true)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        $body = curl_exec($this->curl);
        if ($body === false) {
            throw new \Exception('curl failed: '.curl_error($this->curl));
        }

        if (!!$json) {
            $body = json_decode($body, true);
        }

        return $body;
    }

    private function post($url, $parameters = array(), $json = true)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POST, true);
        if (count($parameters) > 0) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parameters);
        }
        $body = curl_exec($this->curl);
        if ($body === false) {
            throw new \Exception('curl failed: '.curl_error($this->curl));
        }

        if (!!$json) {
            $body = json_decode($body, true);
        }

        if (isset($body['status']) && $body['status'] == 'error') {
            if (isset($body['reason']['__all__'])) {
                throw new \Exception(implode("\n", $body['reason']['__all__']));
            } else {
                throw new \Exception(implode("\n", $body['reason']));
            }
        }

        return $body;
    }

    private function getNewNonce()
    {
        $tmpNonce = time();
        if ($this->nonce >= $tmpNonce) {
            $tmpNonce = $this->nonce + 1;
        }

        return $this->nonce = $tmpNonce;
    }

    private function signature()
    {
        $customerId = $this->options['bitstamp']['customerId'];
        $apiKey = $this->options['bitstamp']['apiKey'];
        $secret = $this->options['bitstamp']['secret'];
        $message = $this->getNewNonce() . $customerId . $apiKey;

        return strtoupper(hash_hmac('sha256', $message, $secret));
    }
}
