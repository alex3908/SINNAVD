<?php 	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];

	$idC=$_GET['idC'];
	$idR=$_GET['idR'];
		
		$sel="SELECT id from carpetas_guardia where id_carpeta='$idC'";
		$esel=$mysqli->query($sel);
		$resel=$esel->num_rows;
		if ($resel>0) {
			header("location: perfil_repG.php?id=$idR");
		}else{
		$sql="INSERT into carpetas_guardia (id_guardia, id_carpeta, respo_reg) values ('$idR', '$idC', '$idDEPTO')";
		$esql=$mysqli->query($sql);
		header("location: perfil_repG.php?id=$idR");

	}
	

	?>