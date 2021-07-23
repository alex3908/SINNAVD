<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];

	$idFP=$_GET['id'];
	$bandera = false;

	if(!empty($_POST))
	{
		$password = mysqli_real_escape_string($mysqli,$_POST['password']);
		$con_password = mysqli_real_escape_string($mysqli,$_POST['con_password']);

		if ($password==$con_password) {
			$sha1_pass = sha1($password);
		}else{
			echo "Las contraseñas no coinciden";
		}

		$sql="UPDATE departamentos set password='$sha1_pass' WHERE id='$idFP'";
		$ejecucion=$mysqli->query($sql);
		if($ejecucion>0)
			$bandera = true;
			else
			$error = "Error al Registrar";
	}
?>

<!DOCTYPE HTML>

<html>
	<head>
		
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>	
						<div class="box">
						
				<form id="registro" enctype="multipart/form-data" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
		<div class="row uniform">

<div class="6u 12u$(xsmall)">
			<input id="password" name="password" type="password" class="password" placeholder="Nueva contraseña" required>
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="con_password" name="con_password" type="password" class="password" placeholder="Confirmar nueva contraseña" required>
			</div>
			<div class="6u 12u$(xsmall)">
			<input class="button special fit" name="registar" type="submit" value="Registrar">
			</div>
			<div class="6u 12u$(xsmall)">
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
			</div>
			</div>
			</form>
			</div>
			</div>
			</div>
			

			<?php if($bandera) { 
			header("Location:welcome.php");

			?>	<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>
		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>