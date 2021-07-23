<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$sql = "SELECT id, responsable FROM departamentos WHERE id= '$idDEPTO'";
	$result=$mysqli->query($sql);
	
	$row = $result->fetch_assoc();

	$sql = "SELECT id, departamento FROM depto";
	$result=$mysqli->query($sql);

	$perfil="SELECT id, perfil FROM perfiles";
	$eperfil=$mysqli->query($perfil);

	$sql2 = "SELECT id, personal FROM personal";
	$result2=$mysqli->query($sql2);
	
	$bandera = false;
	
	if(!empty($_POST))
	{
		$responsable = mysqli_real_escape_string($mysqli,$_POST['responsable']);
		$per=$_POST['perfil'];
		$sexo = $_POST['sexo'];	
		$telefono = mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$extencion = mysqli_real_escape_string($mysqli,$_POST['extencion']);		
		$password = mysqli_real_escape_string($mysqli,$_POST['password']);
		$tipo_depto = $_POST['tipo_depto'];
		$cargo = $_POST['cargo'];

		$sha1_pass = sha1($password);
		
		$error = '';
		
		$sqlUser = "SELECT id FROM departamentos WHERE responsable = '$responsable'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;
		


		if($rows > 0) {
			$error = "El usuario ya existe";
			} else {
						
			$sqlUsuario = "INSERT INTO departamentos(id_depto, id_personal, responsable, perfil, sexo, telefono, password, extencion, casp, activo) VALUES ('$tipo_depto', '$cargo', '$responsable', '$per', '$sexo', '$telefono', '$sha1_pass', '$extencion','0','1')";
			$resultUsuario = $mysqli->query($sqlUsuario);
			
			if($resultUsuario>0)
			$bandera = true;
			else
			$error = "Error al Registrar";
		
			
		}
	}
	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Registro</title>
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
		<h1>Registro de personal</h1>
		<form id="registro" enctype="multipart/form-data" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
		<div class="row uniform">
			<div class="4u 12u$(small)">
				<input id="responsable" name="responsable" type="text" class="username" placeholder="Responsable" required >
			</div>
			<div class="2u 12u$(small)">
				<div class="select-wrapper">
					<select id="perfil" name="perfil">
						<option value="">Seleccione el perfil</option>
						<?php while ($row=$eperfil->fetch_assoc()) { ?>
							<option value="<?php echo $row['id']; ?>"><?php echo $row['perfil']; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="2u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="sexo" name="sexo" >
					<option value="M">Mujer</option>					
					<option value="H">Hombre</option>								
				</select>
				</div>
			</div>
			<div class="2u 12u$(xsmall)">
			<input id="telefono" name="telefono" type="text" class="telefono" placeholder="Teléfono " required>
			</div>
			<div class="2u 12u$(xsmall)">
			<input id="extencion" name="extencion" type="text" class="extencion" placeholder="Extención" required>
			</div>
			
				<div class="4u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="tipo_depto" name="tipo_depto" >
					<option value="0">Departamento...</option>
					<?php while($row = $result->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['departamento']; ?></option>
					<?php }?>
				</select></div></div>

				<div class="2u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="cargo" name="cargo" >
					<option value="0">Cargo...</option>
					<?php while($row = $result2->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['personal']; ?></option>
					<?php }?>
				</select></div></div>

			<div class="3u 12u$(xsmall)">
			<input id="password" name="password" type="password" class="password" placeholder="Contraseña" required>
			</div>
			<div class="3u 12u$(xsmall)">
			<input id="con_password" name="con_password" type="password" class="password" placeholder="Confirmar contraseña" required>
			</div>
			
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar">
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_personal.php'" >
			</ul></div>
			</div>
		</form>
		</div>
					<?php if($bandera) { 
			header("Location:lista_personal.php");

			?>	<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

							</div>
							</div>
				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
						<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
							</nav>	
								<section>
									<header class="major">
										<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
									</header>
									<p></p>
									<ul class="contact">
										<li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
										<li class="fa-home">Plaza Juarez #118<br />
										Col. Centro <br> Pachuca Hidalgo</li>
									</ul>
								</section>
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Sistema DIF Hidalgo </p>
								</footer>

						</div>
					</div>
					<!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>