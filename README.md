Current status: INCOMPLETE.

# BitstampPHP

A basic API wrapper class and a CLI for the [Bitstamp REST API](https://www.bitstamp.net/api/). Please refer to [their documentation](https://www.bitstamp.net/api/) for all calls explained. All in PHP. All for APIv2.

## bitstamp-cli

Create a `config-local.php` with your data, if you need authorized access.

```bash
<?php
$localConfig = array(
    'customerId' => '000000',
    'apiKey' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
    'secret' => 'yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy'
);
```

## bitstamp PHP class

```php
$cli = new Mx17\BitstampPHP\BitstampNet($localConfig);

$data = $cli->ticker();
echo print_r($data, 1)."\n";
```

# Requirements

* PHP

# Final

If this wrapper helped you in any way, you can always leave me a tip at (BTC) `1AqoRjfksnh9pmSYM4Uejzd3WJ6Rm8gU13`
![Bitcoin tip](assets/bitcoin-tip.png)

# License

The MIT License (MIT)

Copyright (c) 2017 Massimiliano Chinni <bitstampphp@mx17.net>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
