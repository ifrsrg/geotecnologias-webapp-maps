<?php
//VERSÃO UTILIZADA, MOSTRANDO VÁRIAS REQUISISÕES AO MESMO TEMPO
//var_dump($_POST);
require ('gPoint.php');
$i = 0;
$database = $_REQUEST['database'];
$pedacos = explode(",", $database);
$json = "";
$json.='{"type": "FeatureCollection","features": [';
#file_put_contents('data.txt', '{"type": "FeatureCollection","features": [');
while($i<count($pedacos)){
	//CHECAGEM DE QUAIS BANCOS DE DADOS FORAM SELECIONADOS NO CHECKBOX
	if($pedacos[$i] == "Bairro"){
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
			//s
			$result2 = pg_exec($bdcon2, "SELECT ST_asText(the_geom) FROM bairros WHERE gid = " .$s);
			//PARTE QUE SEPARA OS PONTOS
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
				$i3 = $i31;
			}
			$txt = $kappa.$txt.$kappa2;
			if($i==count($pedacos)-1){
				if($s==$cont){
					$txt = substr($txt, 0, -1);
				}
			}
			$s = $s+1;
			array_push($p, $txt);
		}
		file_put_contents('data.txt', $p, FILE_APPEND);
	}
	if($pedacos[$i] == "Hepatite"){
		
		//CONEXÃO COM O BANCO
		$bdcon = pg_connect("host={$_ENV['gs_host']} port={$_ENV['gs_port']} dbname='ocorrencia_hepatite' user={$_ENV['gs_user']} password={$_ENV['gs_password']}");
		//SELECT QUE REALIZA A CONVERSÃO DE UTM PARA LAT/LONG
		$result = pg_exec($bdcon, "SELECT ST_X(the_geom), ST_Y(the_geom)  FROM(SELECT ST_Transform
			(the_geom, 4326) as the_geom from hepatitea) g");
		
		//VARIÁVEIS RESPONSÁVEIS PELO CONTROLE DO WHILE
		$ct = pg_fetch_all($result);
		$m = count($ct);
		$i = 0;
		while($i < $m){
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
	}
	if($pedacos[$i] == "Dengue"){
		//CONEXÃO COM O BANCO
		$bdcon = pg_connect("host={$_ENV['gs_host']} port={$_ENV['gs_port']} dbname={$_ENV['gs_dbname_dengue']} user={$_ENV['gs_user']} password={$_ENV['gs_password']}");
		//SELECT QUE REALIZA A CONVERSÃO DE UTM PARA LAT/LONG
		$result = pg_exec($bdcon, "SELECT ST_X(the_geom), ST_Y(the_geom), logradouro FROM(SELECT ST_Transform
			(the_geom, 4326) as the_geom, logradouro from armadilhas) g");
		
		//VARIÁVEIS RESPONSÁVEIS PELO CONTROLE DO WHILE
		$ct = pg_fetch_all($result);
		$m = count($ct);
		$i = 0;
		while($i < $m){
			//REPETIÇÃO PARA PREENCHER O GEOJSON  VIA FETCH ASSOC
			while ($row = pg_fetch_assoc($result)) { 
      		$l = $row['logradouro'];
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
	}
	$i = $i+1;
}
$json.="]}";
echo $json;
