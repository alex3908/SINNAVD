<?php 
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$res=$_GET['ddlOrigen'];
	if($res=='NACIONAL'){
		
		$qEstados = "SELECT estado FROM estados where id!=13";
		$rEstados = $mysqli->query($qEstados);
		$estados = array();
		while($row=$rEstados->fetch_object()){ $estados[]=$row; }
		print "<option value=''>-- ESTADO --</option>";
		foreach ($estados as $s) {
			print "<option value='$s->estado'>$s->estado</option>";
		}
	} elseif($res=='INTERNACIONAL'){
		$qPaises= "SELECT id_pais, pais  FROM cat_paises where id_pais!=303 and id_pais!=1 order by pais";
		$rPaises= $mysqli->query($qPaises);
		$paises= array();
		while ($row= $rPaises->fetch_object()) {
			$paises[]=$row;
		}
		print "<option value=''>-- PAÍS --</option>
		<option value='SIN ESPECIFICAR'>SIN ESPECIFICAR</option>";
		foreach ($paises as $p) {
			print "<option value='$p->pais'>$p->pais</option>";
		}
	}


?>