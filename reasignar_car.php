<?php
ob_start();

	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	$idCarpeta= $_GET['id'];
	
	$tper="SELECT id, responsable from departamentos where id_depto in (10,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33)";
	$etper=$mysqli->query($tper);

	$car="SELECT nuc, asignado, fecha_ini from carpeta_inv where id='$idCarpeta'";
	$ecar=$mysqli->query($car);
	while ($row=$ecar->fetch_assoc()) {
		$nuc=$row['nuc'];
		$asignado=$row['asignado'];
		$fecha_ini=$row['fecha_ini'];
	}
	$fecha= date ("j/n/Y");

	if(!empty($_POST))
	{
		$respo=$_POST['respo'];
		$fecha= date("Y-m-d H:i:s", time()); 
		if ($respo=='0') {
			echo "Seleccione a un responsable";
		}else {
			/*$turnar="UPDATE carpeta_inv set asignado='$respo' where id='$idCarpeta'";
			$eturnar=$mysqli->query($turnar);

			$ins="INSERT into historial_carpetas (id_carpeta, asignado, fecha_ini, fecha_fin, respo_reg, fecha) values ('$idCarpeta','$asignado','$fecha_ini','$fecha','$idDEPTO','$fecha')";
			$eins=$mysqli->query($ins);*/

			$ReasignarCar = "CALL reasignar_carpeta($idCarpeta, '$respo',  $idDEPTO, '$fecha')";
			$qReasignarCar = $mysqli->query($ReasignarCar);
			if($qReasignarCar){
				header("Location: perfil_carpeta.php?id=$idCarpeta");
			} else {
				$errnov= mysqli_real_escape_string($mysqli,$mysqli->errno);
				$errorv= mysqli_real_escape_string($mysqli,$mysqli->error);
				$url= $_SERVER["REQUEST_URI"];
				$qError = "INSERT INTO historico_errores (archivo, var_errno, var_error, usuario) values ('$url','$errnov', '$errorv', '$idDEPTO')";
				$rError=$mysqli->query($qError);
				$qConError="SELECT max(id) from historico_errores where usuario=$idDEPTO and archivo='$url'";
				$rConError=$mysqli->query($qConError);
				$idError=implode($rConError->fetch_assoc());
				$error= "Error al registrar, identificador: ".$idError;
			}
		}
	}
?>
<!DOCTYPE HTML>

<html>
	<head lang="ES-mx">
		<title>Reasigna carpeta</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
	</head>
	<body>
	 <br><br><br>

		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >     
		<div class="box">
		
			<h3>Reasignar carpeta <?php echo $nuc; ?></h3>
		
		<br>
			<div class="row uniform">
			<div class="12u$">
				<div class="select-wrapper">
					<select id="respo" name="respo" required="true">
						<option value="0">Seleccione un nuevo responsable...</option>
						<?php while($row = $etper->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['responsable']; ?></option>
						<?php }?>
					</select>
				</div>
			</div>	
				<div class="12u$">
					<input type="submit" value="guardar" class="button special fit small" />
					<input type="button" value="cancelar" class="button fit small" onclick="history.go(-1);" />
				</div>
														
															
			</div></div>
		</form>
		
		
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		