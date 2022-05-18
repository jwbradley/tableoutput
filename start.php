<?php

ini_set("error_log", "./logs/tablesOutput.log");

include('./php/webContents.php');
include('./php/DtaTblCls.php');
include('./html/datatable_header.html');
echo "\n<script>\n\tdocument.getElementById(\"pagetitle\").innerHTML = \"DataTable Output\";\n</script>\n";

$TokenStorage =  __dir__  . '/../../.env';
$tokenData    =  json_decode(file_get_contents($TokenStorage), true);
$webKey       =  $tokenData["key"];

$interval     =  '5min';
$stockSymbol  =  'BUD';
$webFunction  =  'TIME_SERIES_INTRADAY';
$counter      =  0;
$header       =  array( 'Date & Time', 'open price','high price','low price','close price','volume');

$outputData   =  new getContents('https://www.alphavantage.co/query?function='.$webFunction.'&symbol='.$stockSymbol.'&interval='.$interval.'&apikey='.$webKey);
$thisneedsvar =  json_decode($outputData->getFileContents(), true);
foreach ($thisneedsvar['Time Series (5min)'] as $key => $value) {
	$buildArray[$counter]['dattim']       = $key;
	$buildArray[$counter]['open price']   = $value['1. open'];
	$buildArray[$counter]['high price']   = $value['2. high'];
	$buildArray[$counter]['low price']    = $value['3. low'];
	$buildArray[$counter]['close price']  = $value['4. close'];
	$buildArray[$counter]['volume']       = $value['5. volume'];
	$counter++;
}

echo "\n<!--- \$buildArray is set to be\n";var_export($buildArray); echo " -->\n";

$tabledata    =  new DtaTbleOut('stockchart', $buildArray, $header);

?>