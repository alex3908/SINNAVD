<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idAud=$_GET['id'];
	$conso="SELECT id_carpeta from audiencias where id='$idAud'";
	$econso=$mysqli->query($conso);
	while ($row=$econso->fetch_assoc()) {
		$idCarpeta=$row['id_carpeta'];
	}

if(!empty($_POST))
	{
		$hora=mysqli_real_escape_string($mysqli,$_POST['hora']);
		$up="UPDATE audiencias set duracion='$hora' where id='$idAud'";
		$eup=$mysqli->query($up);
		header("Location: audienciaxcarpeta.php?id=$idCarpeta");
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
		<h2>Duración de la audiencia</h2>
		<h4>en horas</h4>

			<div class="row uniform">
				<div class="12u$">
					<input type="text" name="hora" id="hora" value="" placeholder="ejemplo: 20" />
				</div>				
			<br>
				<div class="12u$">
					<input type="submit" value="guardar" class="button special fit small" />
					<input type="button" value="cancelar" class="button fit small" onclick="location='audienciaxcarpeta.php?id=<?php echo $idCarpeta ?>'" />
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

	