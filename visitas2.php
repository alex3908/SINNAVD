<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable, id_personal FROM departamentos WHERE id= '$idDEPTO'";
	
	
	$result=$mysqli->query($sql);
	$result2=$mysqli->query($sql);

	
	while($row=$result2->fetch_assoc()){
		$mi=$row['id_personal'];
	}


    //Establecemos zona horaria por defecto
    date_default_timezone_set('America/Mexico_City');
    //preguntamos la zona horaria
    $zonahoraria = date_default_timezone_get();
   
	$idUsuario = $_GET['id'];

$ahora= date ("h:i");
$hoy= date("Y-m-d");

	$sql = "SELECT id, departamento FROM depto WHERE id!=16 && id!=7 and id!=12";
	$result=$mysqli->query($sql);

	$nombreU="SELECT nombre, apellido_p, apellido_m FROM usuarios WHERE id='$idUsuario'";
	$r=$mysqli->query($nombreU);
	
	$bandera = false;
	
	if(!empty($_POST))
	{
		$fechaS= $_POST['fecha'];
		$horaa= $_POST['hora'];
		$objfecha= date_create_from_format('Y-m-d', $fechaS);
		$fechaa=date_format($objfecha, "j/n/Y");
		$horaa= $_POST['hora'];
		//$fechahora=$fechaa." ".$horaa;  //usado en caso de que se requiera cambiar la fecha y la hora
		$fechahora=date("j/n/Y H:i", time());
		$asunto =$_POST['asunto'];				
		$responsable = $_POST['depto'];		
		if ($responsable==0) {
			$error = "Error al Registrar";
		}else{


		$sqlUsuario = "INSERT INTO historial
			(id_usuario, id_departamento, fecha_ingreso, responsable, asunto, respo_reg) 
			VALUES
			('$idUsuario', '$responsable', '$fechahora', '0', '$asunto','$idDEPTO')";
			echo $sqlUsuario;
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
		<title>Visitas</title>
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
						
			 <h1>Registrar visita</h1>
			  
		
				<form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
		<div class="row uniform">
			<div class="12u$">
			<?php while($row = $r->fetch_assoc()){ ?>
			<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m']; ?>" disabled >
			<?php }?>
			</div>
			<?php if ($mi==5) { 
			$valida="SELECT departamentos.id_depto, depto.departamento from depto, departamentos where departamentos.id='$idDEPTO' and departamentos.id_personal='5' and departamentos.id_depto=depto.id";
			$evalida=$mysqli->query($valida); ?>

				<div class="6u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="responsable" name="depto">					
					<?php while($row = $evalida->fetch_assoc()){ ?>
						<option value="<?php echo $row['id_depto']; ?>"><?php echo $row['departamento']; ?></option>
					<?php }?>
				</select>
				</div>
				</div>
			<?php } else { ?>
			<div class="6u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="responsable" name="depto" >
					<option value="">Departamento...</option>
					<?php while($row = $result->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['departamento']; ?></option>
					<?php }?>
				</select></div></div>

			<?php } ?>
			<div class="6u 12u$(xsmall)">
			
			<div class="select-wrapper">
				<select id="asunto" name="asunto" >
					<option value="INICIAL">INICIAL</option>
					<option value="SUBSECUENTE">SUBSECUENTE</option>
				</select>
				</div>
			</div>

			<div class="6u 12u$(xsmall)">
			<input id="fecha" name="fecha" type="date" min="2020-04-01" max="<?php echo $hoy; ?>" value="<?php echo $hoy; ?>" disabled>
			</div>

			<div class="6u 12u$(xsmall)">
			<input id="hora" name="hora" type="text" pattern="[0-9:]{5,5}$"  value="<?php echo $ahora; ?>" disabled>
			</div>

			
			
			  
			
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
			</ul></div>
			</div>
		</form>
		</div>
					<?php if($bandera) { 
			header("Location:welcome.php");

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
										<li><a href="logout.php" >Cerrar sesión</a></li>
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