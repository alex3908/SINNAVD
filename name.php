<?php 
	
	session_start();
	require 'conexion.php';
    $idDEPTO = $_SESSION['id'];
	$idNNA = $_GET['id'];
	$folio= $_GET['idPc'];
	$fecha=date("Y-m-d");

    $qNna="SELECT nombre from nna_reportados where id='$idNNA'";
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
        $nombre=$row['nombre'];
       
    }	

    $qName="SELECT num_control_coespo, activo from relacion_names where id_nna_reportado='$idNNA'";
    $rName=$mysqli->query($qName);
    $numName=$rName->num_rows;
    if($numName>0){
        while ($rowName=$rName->fetch_assoc()) {
			$idCaso=$rowName['num_control_coespo'];
			$activo=$rowName['activo'];
		}
    }

	if(!empty($_POST))
	{
		if($_POST['esName']) //determina si el checkbox esta marcado
		$aux=1;
		else 
		$aux=0;
		$idCasoN = mysqli_real_escape_string($mysqli,$_POST['id_coespo']);
		if($numName==0 and $aux==1){ //si no hay un registro y esta marcado el checkbox inserta nuevo registro
			$insertar="INSERT INTO relacion_names (id_nna_reportado, num_control_coespo, fecha_registro, id_persona_reg) values ('$idNNA', '$idCasoN', '$fecha', '$idDEPTO')";
			$rInsertar=$mysqli->query($insertar);
		}else if($numName>0 and $activo=='0' and $aux==1){ //Si hay un registro desactivado y esta marcado el checkbox, activar
			$activar="UPDATE relacion_names set activo=1 where id_nna_reportado=$idNNA";
			$rActivar=$mysqli->query($activar);
		} else if ($numName>0 and $activo=='1' and $aux==0){//si hay un registro activado y se quiere desactivar 
			$desactivar="UPDATE relacion_names set activo=0 where id_nna_reportado=$idNNA";
			$rDesactivar=$mysqli->query($desactivar);
		}
		header("Location: reg_nna_reportados.php?idPosibleCaso=$folio");
	}
	
?>

<html>
	<html lang="es-ES" class="no-js">
	<head>
		<title>Registrar como NAME</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">			
			<h3>Añadir a  <?php echo $nombre." como name "?> </h3>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
					<div class="box">
						<div class="row uniform">
							<div class="12u">
								<?php if($numName==0 or $activo==0) {?>
								<input type="checkbox" id="esName" name="esName" >
								<label for="esName">¿NAME?</label>
								<?php } else if($activo==1 and $numName>0) {?>
								<input type="checkbox" id="esName" name="esName" checked>
								<label for="esName">¿NAME?</label>
								<?php } ?>
							</div>
						</div>
					</div>
					<div class="box">
					<div class="row uniform">
						<div class="12u">Si tiene un numero de caso, favor de anotarlo en el siguiente campo, sino solo presione el boton aceptar.
						<?php if($numName>0 and $activo==1){?>	
							<input id="id_coespo" name="id_coespo" type="text" maxlength="11" value="<?php echo $idCaso;?>">
						<?php } else { ?>
							<input id="id_coespo" name="id_coespo" type="text" maxlength="11" value="0">
							<?php } ?>
						</div>
					</div>
					</div>

						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Aceptar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='reg_nna_reportados.php?idPosibleCaso=<?php echo $folio?>'" >
						</ul>
					</div>
				</form>				
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		