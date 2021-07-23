<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	$idCarpeta= $_GET['id'];
	$personas="SELECT id_depto from departamentos where id='$idDEPTO'";
	$epersonas=$mysqli->query($personas);
	while ($row=$epersonas->fetch_assoc()) {
		$idd=$row['id_depto'];
	}
	$tper="SELECT id, responsable from departamentos where id_depto='$idd'";
	$etper=$mysqli->query($tper);
	$car="SELECT nuc from carpeta_inv where id='$idCarpeta'";
	$ecar=$mysqli->query($car);
	$fecha= date ("j/n/Y");
	

	if(!empty($_POST))
	{
		$respo=$_POST['respo'];
		if ($respo=='0') {
			echo "Seleccione a un responsable";
		}else {
		$turnar="UPDATE carpeta_inv set asignado='$respo', respo_asig='$idDEPTO', fecha_asig='$fecha' where id='$idCarpeta'";
		
		$eturnar=$mysqli->query($turnar);

		header("Location: lista_carpeta.php");
}
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
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
		<?php while ($row=$ecar->fetch_assoc()) { ?>
			<h3>Responsable de la carpeta <?php echo $row['nuc']; ?></h3>
		<?php } ?>
		<br>
			<div class="row uniform">
			<div class="12u$">
				<div class="select-wrapper">
					<select id="respo" name="respo" >
						<option value="0">Seleccione un responsable...</option>
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