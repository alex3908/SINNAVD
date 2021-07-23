<?php
ob_start();
?>
<?php

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idVisita = $_GET['idVisita'];
	

$visitaP="SELECT usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, historial.id_departamento, depto.departamento, historial.responsable, historial.asunto from depto, usuarios, historial where historial.id_usuario=usuarios.id and historial.id_departamento=depto.id and historial.id='$idVisita'";
$evisitaP=$mysqli->query($visitaP);
$evisitaP2=$mysqli->query($visitaP);
	

	

	
	$bandera = false;
	
	if(!empty($_POST))
	{
		

		$asunto = mysqli_real_escape_string($mysqli,$_POST['asunto']);		
		
		$responsable = $_POST['responsable'];
		
		
		$sqlUsuario = "UPDATE historial SET responsable='$responsable', asunto='$asunto' WHERE id='$idVisita'";
			
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
		<title>Editar departamento</title>
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
						
			 <h1>Editar departamento</h1>
			  
		
				<form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
		<div class="row uniform">
			<div class="12u$">
			<?php while($row = $evisitaP->fetch_assoc()){ ?>
			<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m']; ?>" disabled >
		
			</div>
			<div class="6u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="depto" name="depto" disabled >
					<option value="<?php echo $row['id_departamento']; ?>"><?php echo $row['departamento']; }?></option>
					
				</select></div>
			</div>

			<?php while($row = $evisitaP2->fetch_assoc()){ 
				$idD=$row['id_departamento'];
				
				?>
			<div class="6u 12u$(xsmall)">
			<input id="asunto" name="asunto" type="text" value="<?php echo $row['asunto']; ?>" placeholder="Asunto" required="required">
			</div>
			
			<div class="12u$">
				<div class="select-wrapper">
				<select id="responsable" name="responsable">
					<option value="<?php echo $row['responsable']; ?>"><?php echo $row['responsable'];
					$re=$row['responsable']; }?></option>
					<?php
					 $Consult="SELECT responsable from departamentos where id_depto='$idD' and responsable!='$re'";
				$eConsult=$mysqli->query($Consult);
				while($row = $eConsult->fetch_assoc()){ ?>
						<option value="<?php echo $row['responsable']; ?>"><?php echo $row['responsable']; ?></option>
						
					<?php }?><option value="null">Ninguno</option>
				</select></div>
			</div>
			
			  
			
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Actualizar" >
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
							
						<?php if($_SESSION['departamento']==7) { ?> 
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
									<ul>
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