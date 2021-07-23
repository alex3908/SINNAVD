<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$fecha= date ("j/n/Y");
	$bandera = false;
	if(!empty($_POST))
	{
		$mes = mysqli_real_escape_string($mysqli,$_POST['mes']);
		$fechai = mysqli_real_escape_string($mysqli,$_POST['fechai']);
		$fechac = mysqli_real_escape_string($mysqli,$_POST['fechac']);
		
		$mensaje =$_POST['mensaje'];
		
		$error = '';
		
			
			$sqlNino = "UPDATE cortes set mes='$mes',, fechai='$fechai', fechac=$fechac', mensaje='$mensaje', fecha_reg='$fecha', respo_reg='$idDEPTO' where id=1";

			$resultNino = $mysqli->query($sqlNino);
			
			
			if($resultNino>0)
			header("Location:fechas_corte.php");
			else
			$error = "Error al Registrar";
		
		}
	
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
		
	</head>
	<body>

		
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<br> <br> 
		<div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
			</div>
		</div>
	<h1>Registro de NNA</h1>
		<div class="box" >
			<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
				<div class="row uniform">
					<div class="4u 12u$(xsmall)">
						<input id="mes" name="mes" type="text" placeholder="Mes de corte"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
					</div>
					<div class="4u 12u$(xsmall)">
						<input id="fechai" name="fechai" type="text"  placeholder="Fecha de inicio" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
					</div>
					<div class="4u 12u$(xsmall)">
						<input id="fechac" name="fechac" type="text"  placeholder="Fecha de cierre"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
					</div>
					<div class="12u 12u$(xsmall)">
						<textarea name="mensaje" cols="" rows="" placeholder="Mensaje" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
					</div>
									
					<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Registrar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
						</ul>
					</div>
				</div>
			</form>
		</div>
				
		

		<?php if($bandera) { 
			header("Location: welcome.php");

			?>
						
			<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>
		
						</div>
					</div>

				
			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>