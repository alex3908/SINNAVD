<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
    //Establecemos zona horaria por defecto
    date_default_timezone_set('America/Mexico_City');
    //preguntamos la zona horaria
    $zonahoraria = date_default_timezone_get();
   



	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable, id_depto FROM departamentos WHERE id= '$idDEPTO'";
	$idHisto=$_GET['id'];
	
	$result=$mysqli->query($sql);
	while ($row=$result->fetch_assoc()) {
		
		$listaUsuarios="SELECT historial.id, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id='$idHisto'";
	$rUsuarios=$mysqli->query($listaUsuarios);
	}
	$dep="SELECT id_depto, id_personal FROM departamentos WHERE id='$idDEPTO'";
	$edep=$mysqli->query($dep);
	while ($row=$edep->fetch_assoc()) {
		$idd=$row['id_depto'];
		$idP=$row['id_personal'];
	}
	if ($idP==5) {
		$dept="SELECT id, responsable FROM departamentos WHERE id_depto='$idd'";
		$edept=$mysqli->query($dept);
	}else{
		$dept="SELECT id, responsable FROM departamentos WHERE id_depto='$idd' AND id!='$idDEPTO'";
		$edept=$mysqli->query($dept);
	}
	
$bandera = false;
if(!empty($_POST))
	{
		$despo= $_POST['respo'];
		
		$error = '';
		if($despo=="0"){
			$error="Seleccione un responsable";
		}else if($despo!="0"){
			$ss="SELECT fecha_salida FROM historial WHERE responsable='$despo' && fecha_salida is null";
			$sss=$mysqli->query($ss);
			
			$sqlup = "UPDATE historial SET responsable='$despo' WHERE id='$idHisto'";
			$resultUp = $mysqli->query($sqlup);
			
			if($resultUp>0)
			$bandera = true;
			else
			$error = "Error Terminar";
			
		} 
	}
	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Canalizar</title>
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
						<div class="inner">
							<br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>	
							<?php while($row=$rUsuarios->fetch_assoc()){ ?>
								<form id="registro" enctype="multipart/form-data" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 			
								<h2><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></h2>
											<ul class="alt">
												<li><strong>Departamento: </strong><?php echo $row['departamento'];?></li>		
												
												<li><strong>Asunto: </strong><?php echo $row['asunto'];?></li>
												
												<li><strong>Fecha de entrada: </strong><?php echo $row['fecha_ingreso'];?></li> <?php } ?>
												<li>
												
												<div class="select-wrapper">
												<select id="respo" name="respo" >
												<option value="0">Seleccione un responsable...</option>
												<?php while($row = $edept->fetch_assoc()){ ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['responsable']; ?></option>
												<?php }?>
												</select></div></li>
											</ul>	
															<input class="button special fit" name="registar" type="submit" value="Guardar">
											</form>			
									
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
							
						<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
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
										<li><span class="opener">Departamentos</span>
											<ul>
												<li><a href="registro_personal.php">Alta</a></li>
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
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
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