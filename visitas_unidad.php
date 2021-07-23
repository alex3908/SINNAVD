<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	/*if(!isset($_SESSION["id"])){
		header("Location: lista_unidad.php");
	}*/
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable, id_personal FROM departamentos WHERE id= '$idDEPTO'";

	$result=$mysqli->query($sql);
	$result2=$mysqli->query($sql);

	$sqlAsunto="SELECT id, asunto FROM cat_asuntos_unidad";
	$rAsuntos=$mysqli->query($sqlAsunto);

	
	while($row=$result2->fetch_assoc()){
		$mi=$row['id_personal'];
	}

	$idUsuario = $_GET['id'];
	//Establecemos zona horaria por defecto
    date_default_timezone_set('America/Mexico_City');
    

$fecha= date ("j/n/Y");


	$nombreU="SELECT nombre, apellido_p, apellido_m FROM benef_unidad WHERE id='$idUsuario'";
	$r=$mysqli->query($nombreU);
	
	$bandera = false;
	
	if(!empty($_POST['registrar']))
	{
		$asunto =$_POST['asunto'];			
		$responsable = $_POST['responsable'];
		$tipo = $_POST['tipo'];
		$nuc = $_POST['nuc'];	
		

		$sqlUsuario = "INSERT INTO visitas_unidad (id_benef, responsable, asunto, tipo, fecha, respo_reg, nuc) VALUES ('$idUsuario', '$responsable', '$asunto', '$tipo', '$fecha','$idDEPTO', '$nuc')";
			
			$resultUsuario = $mysqli->query($sqlUsuario);
			echo $sqlUsuario;
			if($resultUsuario>0)
			$bandera = true;
			else
			$error = "Error al Registrar";
			
}
		
	
?>


<!DOCTYPE HTML>

<html>
	<head>
		<title>Visitas a UIENNAVD</title>
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
						
			 <h1>Registrar visita a UIENNVD</h1>
			  
		
				<form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
		<div class="row uniform">
			<div class="5u 12u$(xsmall)">
			<?php while($row = $r->fetch_assoc()){ ?>
			<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m']; ?>" disabled >
			<?php }?>
			</div>
			
				<div class="4u 12u$(xsmall)">
			
					<div class="select-wrapper">
						<select id="responsable" name="responsable" required>
							<option value="TRABAJO SOCIAL">TRABAJO SOCIAL</option>
							<option value="PSICOLOGIA">PSICOLOGÍA</option>
							<option value="MEDICINA">MEDICINA</option>
							<option value="MINISTERIO PUBLICO">MINISTERIO PÚBLICO</option>
						</select>
						</div>
					</div>
				<div class="3u 12u$(xsmall)">
					<input id="fecha" name="fecha" type="text" value="<?php echo $fecha; ?>" disabled>
				</div>

				<div class="4u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="asunto" name="asunto" required>					
					<?php while($row = $rAsuntos->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['asunto']; ?></option>
					<?php }?>
				</select>
				</div>
				</div>
				
			<div class="4u 12u$(xsmall)">
			
			<div class="select-wrapper">
				<select id="tipo" name="tipo" required>
					<option value="INICIAL">INICIAL</option>
					<option value="SUBSECUENTE">SUBSECUENTE</option>
				</select>
				</div>
			</div>

			

			<div class="4u 12u$(xsmall)">
			<input id="nuc" name="nuc" type="text" placeholder="NUC" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
			</div>
			
			  
			
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registrar" type="submit" value="Registrar" >
            <input class="button fit" type="button" name="cancelar" value="Cancelar" onClick="location='lista_unidad.php'" >
			</ul></div>
			</div>
		</form>
		</div>
					<?php if($bandera) { 
			header("Location:lista_unidad.php");

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
									<p class="copyright">&copy; Ing. Ivan Flores Navarro. </p>
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