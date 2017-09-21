<?php

use MathieuViossat\Util\ArrayToTextTable;

function printTable($data)
{
    if (count($data) === 0) {
        echo "No data\n";
        return;
    }
    $renderer = new ArrayToTextTable($data);
    $renderer->setDecorator(new \Zend\Text\Table\Decorator\Ascii());
    echo $renderer->getTable();
}
