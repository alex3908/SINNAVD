<?php 
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$idNNA = $_GET['idNNA'];
	$estado=$_GET['estado'];  
	$fecha= date("Y-m-d H:i:s", time());
	if($estado==3){
		$datos = "SELECT padre_fallecido_covid, madre_fallecida_covid from nna where id=$idNNA";
		$datos = $mysqli->query($datos);
		while ($row=$datos->fetch_assoc()) {
			$padre = $row['padre_fallecido_covid'];
			$madre = $row['madre_fallecida_covid'];
		}
		if($padre==1)
			$query="UPDATE nna set madre_fallecida_covid=1, fecha_mama = '$fecha', respo_mama=$idDEPTO where id=$idNNA";
		elseif($madre==1)
			$query="UPDATE nna set padre_fallecido_covid=1, fecha_papa = '$fecha', respo_papa=$idDEPTO where id=$idNNA";
		else
			$query="UPDATE nna set padre_fallecido_covid=1, fecha_papa = '$fecha', respo_papa=$idDEPTO, madre_fallecida_covid=1, fecha_mama = '$fecha', respo_mama=$idDEPTO where id=$idNNA";
	} elseif($estado==1)
		$query="UPDATE nna set padre_fallecido_covid=1, fecha_papa = '$fecha', respo_papa=$idDEPTO where id=$idNNA";
	else
		$query="UPDATE nna set madre_fallecida_covid=1, fecha_mama = '$fecha', respo_mama=$idDEPTO where id=$idNNA";
	$qquery=$mysqli->query($query);
	if($qquery)
	{
		header("Location: perfil_nna.php?id=$idNNA");
	}
	else echo $query;

?>