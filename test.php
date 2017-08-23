<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require ('gPoint.php'); 
$json = file_get_contents("geojson.txt");
$data = json_decode($json, TRUE);
$init = "{ \"type \":  \"Feature \", \"geometry \": { \"type \":  \"MultiPolygon \", \"coordinates \": [[[";
$ponto = new gPoint();
$lats = ["395245.391400000080466","395246.195299999788404","395247.803299999795854","395248.406200000084937"];
$longs =["6456184.509499999694526","6456174.861999999731779","6456166.621399999596179","6456160.792600000277162"];
$mid="";
for ($i=0;$i<4;$i++){
$ponto->setUTM($lats[$i],$longs[$i]);
$ponto->convertTMtoLL();
echo $ponto->Lat();
echo $ponto->Long();
$mid += "[".$ponto->Lat().",".$ponto->Long()."],";
if($i==3){
	$mid += "[".$ponto->Lat().",".$ponto->Long()."]";
}
}
//
//$ponto->convertTMtoLL();
//echo $ponto->Lat();
//echo $ponto->Long();

//
$end = "]
			]
		]
	}
}";


echo $init.$mid.$end;
//echo $ponto.printLatLong();
 ?>