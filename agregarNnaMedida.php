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
	$consulta="INSERT INTO benefmed (id_medida, id_nna, id_caso) values ('$idMed', '$idNna', '$idCaso')";
	$econsulta=$mysqli->query($consulta);
	if($econsulta>0)
	header("Location: editarmed.php?id=$idMed&idCaso=$idCaso");
	else 
	echo "Error: ".$consulta;
?>