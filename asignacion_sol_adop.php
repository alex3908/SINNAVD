<?php 
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
    $idSol= filter_input(INPUT_POST, 'idSolicitud');
    $tipo= filter_input(INPUT_POST, 'tipo');
    $asignado= filter_input(INPUT_POST, 'asignado');
    $fecha = date('Y-m-d H:i:s', time());
    $asignacion = "CALL asignar_solicitud_adop('$idSol', '$asignado', '$fecha', '$idDEPTO', '$tipo' )";
		$asignacion = $mysqli->query($asignacion);
if($asignacion){
	 echo json_encode(array('success' => 1));
	} else {
	    echo json_encode(array('success' => 0));
	}
	
  ?>
