<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	$fecha= date ("j/n/Y");
	$bandera = false;

	$query="SELECT departamentos.id, departamentos.responsable, depto.departamento, departamentos.telefono, departamentos.extencion, personal.personal, departamentos.id_personal, departamentos.activo FROM departamentos, depto, personal WHERE depto.id=departamentos.id_depto && personal.id=departamentos.id_personal && departamentos.id='$idDEPTO'";
	$resultado=$mysqli->query($query);

	if(!empty($_POST))
	{
		$num_e = mysqli_real_escape_string($mysqli,$_POST['num_e']);
		$tel_o = mysqli_real_escape_string($mysqli,$_POST['tel_o']);
		$ext = mysqli_real_escape_string($mysqli,$_POST['ext']);		
		$tel_p = mysqli_real_escape_string($mysqli,$_POST['tel_p']);
		$fecha = mysqli_real_escape_string($mysqli,$_POST['fecha']);
		$rfc = mysqli_real_escape_string($mysqli,$_POST['rfc']);
		$curp = mysqli_real_escape_string($mysqli,$_POST['curp']);		
		
		$error = '';

			$sqlNino = "UPDATE departamentos set telefono='$tel_o', tel_part='$tel_p', fecha_nac='$fecha', rfc='$rfc', curp='$curp', num_empleado='$num_e', extencion='$ext' where id='$idDEPTO'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location:welcome.php");
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
							<br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="3u"><img src="images/crece.jpg" width="80px" height="75px" /></div>
					<div class="3u"><img src="images/dif.jpg" width="45px" height="75px" /></div>
					<div class="3u"><img src="images/armas.jpg" width="80px" height="80px" onclick="location='informemm.php'" /></div>
					<div class="3u"><img src="images/logo.png" width="120px" height="78px"></div>
		</div></div>
								<strong>Actualiza tu información</strong>
								<p>Antes de continuar, ayudanos a mantener tu información actualizada</p>
								<strong>*</strong>Datos para oficinas centrales, poner la extension mas cercana en la cual poder contactarte, Subprocuradurias escribir S/E
						<div class="box" >
							<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								<?php $row = $resultado->fetch_assoc(); ?>
			 					<div class="row uniform">
									<div class="4u 12u$(xsmall)">Responsable
										<input type="text" value="<?php echo $row['responsable']; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
									<div class="4u 12u$(xsmall)">Departamento
										<input type="text" value="<?php echo $row['departamento']; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
									<div class="2u 12u$(xsmall)">Cargo
										<input type="text" value="<?php echo $row['personal']; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
									<div class="2u 12u$(xsmall)">Numero de empleado
										<input id="num_e" name="num_e" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="2u 12u$(xsmall)">Telefono de oficina
										<input id="tel_o" name="tel_o" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="1u 12u$(xsmall)">Extension<strong>*</strong>
										<input id="ext" name="ext" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
									</div>
									<div class="2u 12u$(xsmall)">Telefono particular
										<input id="tel_p" name="tel_p" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="2u 12u$(xsmall)">Fecha de nacimiento
										<input id="fecha" name="fecha" type="text" placeholder="DD/MM/AAAA"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="2u 12u$(xsmall)">RFC
										<input id="rfc" name="rfc" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="3u 12u$(xsmall)">Curp
										<input id="curp" name="curp" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>									
									<div class="6u 12u$(xsmall)">										
										<input class="button special fit" name="registar" type="submit" value="actualizar" >
									</div>
									<div class="6u 12u$(xsmall)">										
										<input class="button fit" name="registar" type="submit" value="cancelar" onclick="location='logout.php'" >											
									</div>
								</div>

							</form>
						</div>

		<?php if($bandera) { 
			header("Location: lista_nna.php");

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