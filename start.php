<?php

ini_set("error_log", "./logs/tablesOutput.log");

include('./php/webContents.php');
include('./php/DtaTblCls.php');
include('./html/datatable_header.html');
echo "\n<script>\n\tdocument.getElementById(\"pagetitle\").innerHTML = \"DataTable Output\";\n</script>\n";



// $TokenStorage =  __dir__  . '../.env';
// $tokenData    =  json_decode(file_get_contents($TokenStorage), true);
// $webKey       =  $tokenData["key"];
$webKey       =  'KGOFSXPNMA7WZL1V';
$interval     =  '5min';
$stockSymbol  =  'BUD';
$webFunction  =  'TIME_SERIES_INTRADAY';
$counter      =  0;
$header       =  array( 'Date & Time', 'open price','high price','low price','close price','volume');

$outputData   =  new getContents('https://www.alphavantage.co/query?function='.$webFunction.'&symbol='.$stockSymbol.'&interval='.$interval.'&apikey='.$webKey);
$thisneedsvar =  json_decode($outputData->getFileContents(), true);
foreach ($thisneedsvar['Time Series (5min)'] as $key => $value) {
		echo "\n<!--- give me the key \n";var_export($key); echo " -->\n";
	// foreach ($value as $key2 => $value2) {

		// echo "\n<!--- give me the key2 \n";var_export($key2); echo " -->\n";
		// $buildArray[$counter++]
		$buildArray[$counter]['dattim']       = $key;
		$buildArray[$counter]['open price']   = $value['1. open'];
		$buildArray[$counter]['high price']   = $value['2. high'];
		$buildArray[$counter]['low price']    = $value['3. low'];
		$buildArray[$counter]['close price']  = $value['4. close'];
		$buildArray[$counter]['volume']       = $value['5. volume'];
		$counter++;
	// }
}

// echo "\n<!--- \$thisneedsvar['Meta Data'] is set to be\n";var_export($thisneedsvar['Meta Data']); echo " -->\n";
// echo "\n<!--- \$thisneedsvar['Time Series (5min)'] is set to be\n";var_export($thisneedsvar['Time Series (5min)']); echo " -->\n";


echo "\n<!--- \$buildArray is set to be\n";var_export($buildArray); echo " -->\n";


$tabledata    =  new DtaTbleOut('stockchart', $buildArray, $header);


// echo "\n<!--- header?\n";

// var_export($tabledata->headers);
// // var_export(json_decode($outputData->getFileContents()->"Time Series (5min)", true));
// echo "\nheader?   -->\n";

// $json = file_get_contents('https://www.alphavantage.co/query?function=KAMA&symbol=IBM&interval=weekly&time_period=10&series_type=open&apikey=demo');
// $json = file_get_contents('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=TSLA&interval=5min&apikey=KGOFSXPNMA7WZL1V');

// $data = json_decode($json,true);

// print_r($data);


?>