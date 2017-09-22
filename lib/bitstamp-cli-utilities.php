<?php
function echon($str = '')
{
    echo "$str\n";
}

function howManyBTCCanIBuyWithAllMyBalance($bitstamp, $price)
{
    $data = $bitstamp->balance();
    // try to guess EUR vs USD
    $yourCurrency = substr($bitstamp->getCurrency(), 3);
    $available = $data["{$yourCurrency}_available"];
    $feePercent = $data['fee'];
    if ($available == 0) {
        throw new \Exception("insufficient funds");
    }
    // Round fee to 2 decimals
    $fee = ceil($available * ($feePercent / 100) * 100) / 100;
    $available_for_buying = $available - $fee;
    $btc = round($available_for_buying / $price, 8);

    return $btc;
}
