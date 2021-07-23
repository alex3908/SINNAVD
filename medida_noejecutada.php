<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$time= time();
	$fec= date("Y-m-d H:i:s", $time);
	$idDEPTO = $_SESSION['id'];
	$id= $_GET['id'];	
	$idCaso= $_GET['idCaso'];	

	$consulta="UPDATE cuadro_guia set estado=0, fecha_ejecucion='$fec', observaciones=null where id='$id'";
	$econsulta=$mysqli->query($consulta);
	header("Location: cuadro_guia.php?id=$idCaso");
?>