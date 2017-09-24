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

function askConfirmation($prompt = null)
{
    if ($prompt !== null) {
        $prompt = "$prompt. ";
    }
    $response = readline("{$prompt}Are you sure? (Yes/[No]) ");
    return $response === 'Yes';
}

function printOutput($data, $format) {
    if ($format === 'table') {
        if (is_bool($data)) {
            $data = array(array($data ? 'true' : 'false'));
        } else if (is_string($data)) {
            $data = array(array($data));
        } else if (array_keys($data)[0] !== 0) {
            $data = array($data);
        }
        printTable($data);
    } elseif ($format == 'json') {
        echo json_encode($data)."\n";
    } else {
        throw new \Exception("unknown format $format");
    }
}
