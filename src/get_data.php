<?php
//VERSÃO UTILIZADA, MOSTRANDO VÁRIAS REQUISISÕES AO MESMO TEMPO
$i = 0;
$database = $_REQUEST['database'];
//$DATABASE = INFORMAÇÕES DA CHECKBOX QUE SERÃO NECESSÁRIAS NA CONSULTA
$json = "";
$json.='{"type": "FeatureCollection","features": [';

if(!empty($database)){
	$database = explode(',', $database); 
	//SEPARA AS INFORMAÇÕES DA CHECKBOX EM UM ARRAY
	//database = [NOMEBANCO][NOMETABELA]
	$dbname= $database[0];
	$tablename = $database[1];
	//CONEXÃO COM O BANCO
 	$bdcon = pg_connect("host={$_ENV['gs_host']} port={$_ENV['gs_port']} dbname=".$dbname." user={$_ENV['gs_user']} password={$_ENV['gs_password']}");
	//SELECT GENÉRICA (APENAS PARA PONTOS) QUE REALIZA A CONVERSÃO DE UTM PARA LAT/LONG
 	$result = pg_exec($bdcon, "SELECT ST_X(the_geom), ST_Y(the_geom) FROM(SELECT ST_Transform
 	(the_geom, 4326) as the_geom from ".$tablename.") g");
	//VARIÁVEIS RESPONSÁVEIS PELO CONTROLE DO WHILE
 	$ct = pg_fetch_all($result);
 	$m = count($ct);
 	$i = 0;
	//REPETIÇÃO PARA PREENCHER O GEOJSON  VIA FETCH ASSOC
	while ($row = pg_fetch_assoc($result)) { 
       	$x =  $row['st_x'];
    	$y =  $row['st_y'];
		//CONDIÇÃO PARA CONCATENAR O GEOJSON POIS A ÚLTIMA LINHA SE DIFERE DAS DEMAIS
 		if($i<$m-1){
			$json.= '{ "type": "Feature","geometry": {"type": "Point","coordinates": ['.$x.','.$y.']},"properties": {"prop0": "value0","prop1": 0.0}},';
 		}else{
			$json.= '{ "type": "Feature","geometry": {"type": "Point","coordinates": ['.$x.','.$y.']},"properties": {"prop0": "value0","prop1": 0.0}}';
 		}
 		//die();
 		$i = $i+1;
 	}
}
	
 		
 
$json.="]}";
echo $json;
