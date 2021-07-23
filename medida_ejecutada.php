<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$time= time();
	$fecha= date("Y-m-d H:i:s", $time);
	$idDEPTO = $_SESSION['id'];
	$id= $_GET['id'];	
	$idCaso= $_GET['idCaso'];	
	$consulta="UPDATE cuadro_guia set estado=1, fecha_ejecucion='$fecha' where id='$id'";
	$econsulta=$mysqli->query($consulta);
	$qmedidas="SELECT count(id) as total from cuadro_guia where activo=1 and id_caso=$idCaso";
	$medidas= $mysqli->query($qmedidas);
	$numdecretadas= implode($medidas->fetch_assoc());
	$ejecutadas="SELECT count(id) as total from cuadro_guia where activo=1 and id_caso=$idCaso and estado=1";
	$qejecutadas= $mysqli->query($ejecutadas);
	$numejecutadas= implode($qejecutadas->fetch_assoc());
	$porcentaje = intval($numejecutadas/$numdecretadas*100);
	if($porcentaje>=60){
		echo "<script>
                alert('El cuadro guia se ha completado en un ".$porcentaje."% favor de verificar si los derechos de los NNA involucrados han sido restituidos');   
                window.location= 'cuadro_guia.php?id=$idCaso'
             </script>";
	} else 
	header("Location: cuadro_guia.php?id=$idCaso");

	
?>