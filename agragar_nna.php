<?php 

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id_usuario"])){
		header("Location: welcome.php");
	}
$idnna=$_GET['idNNA'];
$idCaso=$_GET['idCaso'];
$error = '';



$validacion="SELECT id FROM nna_caso WHERE id_caso='$idCaso' AND id_nna='$idnna'";
$eje=$mysqli->query($validacion);
$cuenta=$eje->num_rows;

if ($cuenta>0) {
	?>
			<script type="text/javascript">alert('Niño ya agregado en este caso');</script>
			<?php
			 header("Location: ag_nna_caso.php?idCaso=$idCaso");
}else{

$agregar="INSERT INTO nna_caso (id_caso, id_nna) VALUES ('$idCaso', '$idnna')";

$ejeagregar=$mysqli->query($agregar);


if($ejeagregar>0)
            header("Location: ag_nna_caso.php?idCaso=$idCaso");
            else
            $error = "Error al Registrar";
}
?>