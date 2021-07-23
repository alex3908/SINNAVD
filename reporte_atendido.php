<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$id= $_GET['id'];		
	
	$consulta="UPDATE reportes_int set estado=1 where id='$id'";
	$econsulta=$mysqli->query($consulta);
	header("Location: lista_reportes_int.php");

	
?>