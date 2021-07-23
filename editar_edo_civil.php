<?php
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	
	$id_estado_civil = filter_input(INPUT_POST, 'estado_civil');
	$idUsuario = filter_input(INPUT_POST, 'idUsuario');

		$fecha = date("Y-m-d H:i:s", time());
	
		$actualizar = "CALL act_estado_civil_usuarios($idUsuario, '$fecha', $idDEPTO, $id_estado_civil)";
		$resultActualiza = $mysqli->query($actualizar);

if($resultActualiza){
	 echo json_encode(array('success' => 1));
	} else {
	    echo json_encode(array('success' => 0));
	}
?>
