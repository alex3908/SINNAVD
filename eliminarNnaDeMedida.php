<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idNna= $_GET['idNna'];	
	$idMed= $_GET['idMed'];	
	$idCaso= $_GET['idCaso'];	
	$consulta="DELETE from benefmed where id_medida='$idMed' and id_nna=$idNna";
	$econsulta=$mysqli->query($consulta);
	if($econsulta)
	header("Location: editarmed.php?id=$idMed&idCaso=$idCaso");
	else 
	echo $consulta;
?>
