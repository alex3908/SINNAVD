<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }
	
	$bandera = false;
	if(!empty($_POST))
	{
		$fecha= date("Y-m-d H:i:s", time());
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$titular = mysqli_real_escape_string($mysqli,$_POST['titular']);
		$rfc = mysqli_real_escape_string($mysqli,$_POST['rfc']);
		$telefono = mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$celular = mysqli_real_escape_string($mysqli,$_POST['celular']);
		$correo1 = mysqli_real_escape_string($mysqli,$_POST['correo1']);
		$correo2 = mysqli_real_escape_string($mysqli,$_POST['correo2']);
		$sup = mysqli_real_escape_string($mysqli,$_POST['supt']);
		$const = mysqli_real_escape_string($mysqli,$_POST['const']);
		$tipo = $_POST['tipo'];
		$numacta = mysqli_real_escape_string($mysqli,$_POST['numacta']);
		$fecha_acta = mysqli_real_escape_string($mysqli,$_POST['fecha_acta']);
		$notaria = mysqli_real_escape_string($mysqli,$_POST['notaria']);
		$repreL = mysqli_real_escape_string($mysqli,$_POST['repreL']);		
		$calle = mysqli_real_escape_string($mysqli,$_POST['calle']);
		$cp = mysqli_real_escape_string($mysqli,$_POST['cp']);
		$id_estado = $_POST['country_id'];
		$id_mun = $_POST['state_id'];
		$id_loc = $_POST['city_id'];
				
		$error = '';
		
		$sqlUser = "SELECT id FROM centros WHERE nombre='$nombre'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;
		
		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Ya existe');</script>
			
			<?php } else {
			
			$sqlNino = "INSERT INTO centros(nombre, titular, rfc, telefono, celular, correo1, correo2, sup, const, tipo, numacta, fecha_acta, notaria, repreL, calle, cp, id_estado, id_mun, id_loc, fecha_reg, respo_reg) VALUES ('$nombre', '$titular', '$rfc', '$telefono', '$celular', '$correo1', '$correo2', '$sup', '$const', '$tipo', '$numacta', '$fecha_acta', '$notaria', '$repreL', '$calle', '$cp', '$id_estado', '$id_mun', '$id_loc', '$fecha', '$idDEPTO')";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: cas.php");
			else
			$error = "Error al Registrar";
			
		}
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
							<br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div>
								<h1>Registro de CAS</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="7u 12u$(xsmall)">
										<input id="nombre" name="nombre" maxlength="200" type="text" placeholder="Nombre o razon social del cas"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="5u 12u$(xsmall)">
										<input id="titular" name="titular" maxlength="120" type="text" placeholder="titular o responsable"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
									</div>
									
									<div class="2u 12u$(xsmall)">
										<input id="rfc" name="rfc" type="text" maxlength="13" placeholder="rfc" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
									</div>									
									<div class="3u 12u$(xsmall)">
										<input id="telefono" name="telefono" maxlength="30" type="text" placeholder="Telefono fijo"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="3u 12u$(xsmall)">
										<input id="celular" name="celular" maxlength="30" type="text"  placeholder="Telefono celular" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
									</div>									
									<div class="4u 12u$(xsmall)">
										<input id="correo1" name="correo1" maxlength="50" type="text" placeholder="CORREO ELECTRONICO 1">	
									</div>
									<div class="4u 12u$(xsmall)">
										<input id="correo2" name="correo2" maxlength="50" type="text" placeholder="CORREO ELECTRONICO 2">
									</div>
									<div class="3u 12u$(xsmall)">
										<input id="supt" name="supt" maxlength="50" type="text" placeholder="superficie total (mts2)"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
									</div>
									<div class="3u 12u$(xsmall)">
										<input id="const" name="const" maxlength="50" type="text" placeholder="const. total (mts2)"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
									</div>
									<div class="2u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="tipo" name="tipo" required>
												<option value="">TIPO</option>
												<option value="PUBLICO">PUBLICO</option>
												<option value="PRIVADO CON CUOTA">PRIVADO CON CUOTA</option>
												<option value="PRIVADO SIN CUOTA">PRIVADO SIN CUOTA</option>
												<option value="CENTRO DE REHABILITACION">CENTRO DE REHABILITACION</option>
												<option value="PRIVADO CON CUOTA FUERA DEL ESTADO">PRIVADO CON CUOTA FUERA DEL ESTADO</option>
												<option value="PRIVADO SIN CUOTA FUERA DEL ESTADO">PRIVADO SIN CUOTA FUERA DEL ESTADO</option>
											</select>
										</div>
									</div>
									<div class="6u 12u$(xsmall)">
									<div class="box">Datos registrales del acta constitutiva
										<div class="row uniform">
										<div class="6u 12u$(xsmall)">
										<input id="numacta" name="numacta" type="text" maxlength="30" placeholder="numero de acta" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
										</div>
										<div class="6u 12u$(xsmall)">
										<input id="fecha_acta" name="fecha_acta" type="date"  value="1900-01-01" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
										</div>
									</div>
									</div>
								</div>
								<div class="6u 12u$(xsmall)">
									<input id="notaria" name="notaria" maxlength="100" type="text" placeholder="notaria publica"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
								</div>
								<div class="6u 12u$(xsmall)">
									<input id="repreL" name="repreL" maxlength="150" type="text" placeholder="representante legal"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >	
								</div>
									<div class="12u 12u$(xsmall)">
									<div class="box">Domicilio
										<div class="row uniform">
										<div class="9u 12u$(xsmall)">
										<input id="calle" name="calle" maxlength="300" type="text"  placeholder="calle y numero" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
										</div>
										<div class="3u 12u$(xsmall)">
										<input id="cp" name="cp" type="text" maxlength="5" placeholder="codigo postal" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
										</div>
										<div class="4u 12u(xsmall)">
											<div class="select-wrapper">
											<select id="country_id" class="form-control" name="country_id" required>
      											<option value="">-- ESTADO --</option>
												<?php foreach($countries as $c):?>
      												<option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
												<?php endforeach; ?>
    										</select>
											</div>
										</div>
										<div class="4u 12u(xsmall)">
											<div class="select-wrapper">
											<select id="state_id" class="form-control" name="state_id" required>
      											<option value="">-- MUNICIPIO --</option>
   											</select>
											</div> 
										</div>
										<div class="4u 12u(xsmall)">
											<div class="select-wrapper">
											<select id="city_id" class="form-control" name="city_id" required>
      											<option value="">-- LOCALIDAD --</option>
   											</select>
											</div> 
										</div>
									</div>
									</div>
								</div>
								
									</div>
						</div>
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
		</ul>
	</div>
</form>
<script type="text/javascript">
			$(document).ready(function(){
		$("#country_id").change(function(){
			$.get("get_states.php","country_id="+$("#country_id").val(), function(data){
				$("#state_id").html(data);
				console.log(data);
			});
		});

		$("#state_id").change(function(){
			$.get("get_cities.php","state_id="+$("#state_id").val(), function(data){
				$("#city_id").html(data);
				console.log(data);
			});
		});
	});
</script>

		<?php if($bandera) { 
			header("Location: welcome.php");

			?>
						
			<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>
		
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							
						<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
									<li><a href="welcome.php" ">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>

									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										
										<li><a href="welcome.php" ">Inicio</a></li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
									<li><a href="welcome.php" ">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>		
							
								<?php }
	
								?>
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