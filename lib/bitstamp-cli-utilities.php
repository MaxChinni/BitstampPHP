<?php
function echon($str = '')
{
    echo "$str\n";
}

function echone($str)
{
    fwrite(STDERR, $str);
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

function howMuchIsIncome($bitstamp, $price, $amount)
{
    $data = $bitstamp->balance();
    $feePercent = $data['fee'];
    $fee = $amount * $feePercent / 100;
    $available_to_sell = $amount - $fee;
    $income = $available_to_sell * $price;

    return $income;
}

function askConfirmation($prompt = null)
{
    if ($prompt !== null) {
        $prompt = "$prompt. ";
    }
    $response = readline("{$prompt}Are you sure? (Yes/[No]) ");
    return $response === 'Yes';
}

function printCSV($data)
{
    $first = 0;
    foreach ($data as $c => $line) {
        if ($first++ === 0) {
            echo implode(',', array_keys($line))."\n";
        }
        echo implode(',', $line)."\n";
    }
}

function printTable($data)
{
    if (count($data) === 0) {
        echo "No data\n";
        return;
    }
    $renderer = new MathieuViossat\Util\ArrayToTextTable($data);
    $renderer->setDecorator(new \Zend\Text\Table\Decorator\Ascii());
    echo $renderer->getTable();
}

function printOutput($data, $format)
{
    if ($format === 'php') {
        echo print_r($data, 1)."\n";
        return;
    }

    // Normalize data
    if (is_bool($data)) {
        $data = array(array($data ? 'true' : 'false'));
    } elseif (is_string($data)) {
        $data = array(array($data));
    } elseif (is_array($data) && count($data) === 0) {
        $data = array(array('No data'));
    } elseif (array_keys($data)[0] !== 0) {
        $data = array($data);
    }

    if ($format === 'table') {
        printTable($data);
    } elseif ($format == 'json') {
        echo json_encode($data)."\n";
    } elseif ($format == 'csv') {
        printCSV($data);
    } else {
        throw new \Exception("unknown format $format");
    }
}
