Current status: INCOMPLETE and not very well tested.

# BitstampPHP

A basic PHP API wrapper class and a CLI for the [Bitstamp REST API v2](https://www.bitstamp.net/api/). Please refer to [their documentation](https://www.bitstamp.net/api/) for all calls explained.

## Requirements

* PHP
* [composer](https://getcomposer.org/)

## Install

    git clone https://github.com/MaxChinni/BitstampPHP.git
    cd BitstampPHP/
    composer.phar update

## Configure

Create a `config-local.php` with your data; that's for private API calls (which require authentication).

```bash
<?php
$localConfig = array(
    'bitstamp' => array(
        'customerId' => '000000',
        'apiKey' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
        'secret' => 'yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy',
        'currency' => 'btceur'),
    'proxy' => array(
        'host' => '192.168.0.200',
        'port' => 8080,
        'username' => 'myUsername',
        'password' => 'mySecretPassword')
);
```

## The Bitstamp CLI

This part of the documentation has to be completed.

## The PHP class

```php
$cli = new Mx17\BitstampPHP\BitstampNet($localConfig);

$data = $cli->ticker();
echo print_r($data, 1)."\n";
```
### API v2 implementation

call                                      | method
----------------------------------------- | ------------------------------
Ticker                                    | `ticker()`
Hourly ticker                             | `tickerHour()`
Order book                                | `orderBook()`
Transactions                              | `transactions()`
EUR/USD conversion rate                   | `conversionRate()`
Account balance                           | `balance()`
User transactions                         | `userTransactions($offset, $limit, $sort)`
Open orders                               | `openOrders()`
Order status                              | `orderStatus($orderId)`
Cancel order                              | `cancelOrder($orderId)`
Cancel all orders                         | `cancelAllOrders()`
Buy limit order                           | `buy($amount, $price, $limitPrice = null, $dailyOrder = false)`
Buy market order                          |
Sell limit order                          | `sell($amount, $price, $limitPrice = null, $dailyOrder = false)`
Sell market order                         |
Withdrawal requests                       |
Bitcoin withdrawal                        |
Litecoin withdrawal                       |
Litecoin deposit address                  |
ETH withdrawal                            |
ETH deposit address                       |
Bitcoin deposit address                   |
Unconfirmed bitcoin deposits              |
Ripple withdrawal                         |
Ripple deposit address                    |
Transfer balance from Sub to Main account |
Transfer balance from Main to Sub Account |
XRP withdrawal                            |
XRP deposit address                       |
Open bank withdrawal                      |
Bank withdrawal status                    |
Cancel bank withdrawal                    |
New liquidation address                   |
Liquidation address info                  |

# Final

If this project helped you in any way, you can always leave me a tip at (BTC) `1AqoRjfksnh9pmSYM4Uejzd3WJ6Rm8gU13`
![Bitcoin tip](assets/bitcoin-tip.png)

# License

The MIT License (MIT)

Copyright (c) 2017 Massimiliano Chinni <bitstampphp@mx17.net>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
