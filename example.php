<?php
require_once('lib/curlClient.php');
require_once('lib/InterfaceConnect.php');

$bmc = new \Bitmessage\InterfaceConnect("arakna:test1234@placeholder.com:8442");

//echo $bmc->testApiCall();

echo $bmc->clientStatus();
echo $bmc->sendMessage("BM-2cXwqcejhVzpXDijTYzaw9DKzefw8omhGm", "BM-2cWgRVMMJYRq9YHcuWErUqvggVJyk1Koj7", "test", "alladin");

//echo $bmc->newAddress("RandomLabelName", $eighteenByteRipe = false, $totalDifficulty = 1, $smallMessageDifficulty = 1);

