<?php
function echon($str = '')
{
    echo "$str\n";
}
function printUsage() {
    echon("Usage:");
    echon("  ".basename(__FILE__)." <action>");
    echon();
    echon("actions:");
    echon("  - ticker");
    echon("  - tickerHour");
    echon("  - orderBook");
    echon("  - transactions");
}
