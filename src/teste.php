<?php
// echo $_ENV['gs_host'];
//**********************************PLOTANDO PONTOS***************************************************
//VERSÃO BÁSICA, APENAS DEMOSTRANDO AS FUNCIONALIDADES
require ('gPoint.php');
$g = new gPoint();
$bdcon = pg_connect("host={$_ENV['gs_host']} port={$_ENV['gs_port']} dbname={$_ENV['gs_dbname_dengue']} user={$_ENV['gs_user']} password={$_ENV['gs_password']}");
$result = pg_exec($bdcon, "SELECT x, y, logradouro FROM armadilhas");
$ct = pg_fetch_all($result);
$m = count($ct);
$i = 0;
$texto = array();
$ini1 = '{"type": "FeatureCollection","features": [';
$fim1 = ']}';
while($i < $m){
	$arr = pg_fetch_array($result, $i, PGSQL_NUM);
	$l = $arr[2];
	$g->setUTM($arr[0], $arr[1], "22S");
	$g->convertTMtoLL();
	if($i<$m-1){
		$points[$i] = '{ "type": "Feature","geometry": {"type": "Point","coordinates": ['.$g->Long().','.$g->Lat().']},"properties": {"prop0": "value0","prop1": 0.0}},';
	}else{
		$points[$i] = '{ "type": "Feature","geometry": {"type": "Point","coordinates": ['.$g->Long().','.$g->Lat().']},"properties": {"prop0": "value0","prop1": 0.0}},';
	}
	array_push($texto, $points[$i]);
	$i = $i+1;
}
echo $points[$i-1];

//*********************************PLOTANDO POLÍGONOS GEOJSON*************************************
$bdcon2 = pg_connect("host={$_ENV['gs_host']} port={$_ENV['gs_port']} dbname={$_ENV['gs_dbname']} user={$_ENV['gs_user']} password={$_ENV['gs_password']}");
$lel = pg_exec($bdcon2, "SELECT count(*) FROM public.bairros");
$ct3 = pg_fetch_all($lel);
$cont = intval($ct3['0']['count']);
$s = 1;
$p = array();
$txt = '';
$ini = '{"type": "FeatureCollection","features": [';
$fim = ']}';
$kappa = ' { "type": "Feature","geometry": {"type": "Polygon","coordinates": [[';
$kappa2 = ']]},"properties": {"color": "yellow","prop1": 0.0}},';
$kappa3 = ']]},"properties": {"prop0": "value0","prop1": 0.0}}';
while($s<=$cont){
$result2 = pg_exec($bdcon2, "SELECT ST_asText(the_geom) FROM bairros WHERE gid = " .$s);

//RESPONSÁVEL PELA SEPARAÇÃO DOS PONTOS
$arr2 = pg_fetch_array($result2, 0, PGSQL_NUM);
$rest = substr($arr2[0], 15, -3);
$pieces = explode(" ", $rest);
$c = 0;
$k = '';
$pe = count($pieces);
while($c<$pe){
  if($c<$pe-1){
  $k = $k.$pieces[$c].',';
}
$c = $c + 1;
}
$true = explode(",", $k);
$m2 = count($true);
$g2 = new gPoint();
$i2 = 0;
$i4 = 0;
$vt = array();
while($i2 < $m2-2){
  $g2->setUTM($true[$i2], $true[$i2+1], "22S");
  $g2->convertTMtoLL();
  if($i2 == $m2-4){
  $vt[$i4] = '['.$g2->Long().', '.$g2->Lat().']';
}else{
  $vt[$i4] = '['.$g2->Long().', '.$g2->Lat().'],';
}
  $i2 = $i2 + 2;
  $i4 = $i4 + 1;
}
$txt = '';
$i3 = 0;
while($i3 < count($vt)){
	$txt = $txt.$vt[$i3];
	$i3 = $i3+1;
}
$txt = $kappa.$txt.$kappa2;
if($s==$cont){
	$txt = substr($txt, 0, -1);
}
$s = $s+1;
array_push($p, $txt);
}
array_push($p, $fim);

//LINHA QUE GRAVA AS COISAS NO ARQUIVIO
//file_put_contents('data.txt', $p, FILE_APPEND);

//HTML BÁSICO COM OS MAPAS
?>

<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>

    <div id="map"></div>
    <script>

var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -32.040223 ,lng: -52.088797},
    zoom: 15
  });
  map.data.loadGeoJson('data.txt');
  map.data.setStyle(function(feature) {
    var color = feature.getProperty('color');
    return {
      fillColor: color,
      strokeWeight: 1
    };
});
}

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6Wg5KXrHUXh_iNlSwN69JR2kPQkXkkb8&callback=initMap"
        async defer></script>
  </body>
</html>
