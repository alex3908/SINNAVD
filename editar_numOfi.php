<?php 	
	session_start();
	require 'conexion.php';
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idO=$_GET['id'];
	$noficio="SELECT num_oficio.id, departamentos.responsable , num_oficio.destinatario, num_oficio.asunto, num_oficio.fecha from num_oficio, departamentos where num_oficio.id='$idO' and num_oficio.respo=departamentos.id";

	$enoficio=$mysqli->query($noficio);
	
	if(!empty($_POST['registrar'])){
		$dest=$_POST['dest'];
		$asunto=$_POST['asunto'];

		$ins="UPDATE num_oficio set destinatario='$dest', asunto='$asunto' where id='$idO'";
		$eins=$mysqli->query($ins);
		if ($eins>0) {
			header("Location:numoficio.php");
		}else {
			echo $ins;
		}
		
	}	

	
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
	
	</head>
	<body>
		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">
						<div class="inner"><br> <br> 	
		<div class="uniform row">
			
			<div class="12u 12u$(xsmall)">
				<div class="box">
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					
					<?php while ($row=$enoficio->fetch_assoc()) { ?>
					<h2>Numero de oficio: <?php echo $row['id']; ?></h2>			
					<div class="uniform row">								
						<div class="5u 12u$(xsmall)">Responsable
							<input id="respo" name="respo" type="text" value="<?php echo $row['responsable']; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
						</div>	
						<div class="5u 12u$(xsmall)">Destinatario
							<input id="dest" name="dest" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['destinatario']; ?>" required>
						</div>
						<div class="2u 12u$(xsmall)">Fecha
							<input value="<?php echo $row['fecha']; ?>" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"  disabled>
						</div>	
						<div class="12u 12u$(xsmall)">Asunto
							<textarea name="asunto" rows="4" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?php echo $row['asunto']; ?></textarea>
						</div>	
						<div class="12u 12u$(xsmall)">
							<input class="button special fit" name="registrar" type="submit" value="Actualizar" >
						</div>
					</div>
					<?php } ?>
					</form>
				</div>
			</div>
			
		</div>

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
										<li><a href="logout.php" >Cerrar sesión</a></li>

									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										
										<li><a href="welcome.php" >Inicio</a></li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
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
										<li><a href="logout.php" >Cerrar sesión</a></li>
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
