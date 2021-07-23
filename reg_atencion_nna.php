<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idNNA=$_GET['id'];
	
	$bandera = false;

	$fecha= date ("j/n/Y");
	$buscaf="SELECT id_depto, responsable  from departamentos where id='$idDEPTO'";
	$ebf=$mysqli->query($buscaf);
	$ebf2=$mysqli->query($buscaf);
	$nna="SELECT nombre, apellido_p, apellido_m from nna where id='$idNNA'";
	$enna=$mysqli->query($nna);

	$sele="SELECT casos.id, casos.folio_c from casos, nna_caso where nna_caso.id_nna='$idNNA' and nna_caso.estado='NE' and nna_caso.id_caso=casos.id";
	$esele=$mysqli->query($sele);

	$aten="SELECT id, brindada from brindadas";
	$eten=$mysqli->query($aten);

	while ($row=$ebf2->fetch_assoc()) {
		$deptoo=$row['id_depto'];
	}

	if(!empty($_POST))
	{
		$caso =$_POST['caso'];
		$atencion = $_POST['atencion'];
		$radio=$_POST['demo-priority'];
		$observacion = mysqli_real_escape_string($mysqli,$_POST['observacion']);
		
		$error = '';
		
		
			$sqlNino = "INSERT INTO atenciones_nna (id_nna, estado_nna, id_depto, respo_reg, fecha, area, id_caso, tipo_ate, observacion) values ('$idNNA', 'NE', '$deptoo', '$idDEPTO', '$fecha', '$radio', '$caso', '$atencion', '$observacion')";
			$esql=$mysqli->query($sqlNino);
			
			if($esql>0)
			header("Location:perfil_nna.php?id=$idNNA");
			else
			$error = "Error al Registrar";
			
		
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Atenciones</title>
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
		</div></div>
								<h1>Registro de atencion</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
			 					<?php  while ($row=$enna->fetch_assoc()) { ?>
									<div class="12u$">Nombre del NNA:
										<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
									<?php  } while ($row=$ebf->fetch_assoc()) {
										
									  ?>
									<div class="6u 12u$(xsmall)">Servidor publico:
										<input id="fp_encargado" style="text-transform:uppercase;" name="fp_encargado" type="text"  disabled value="<?php echo $row['responsable']; }?>">
									</div>
									
									<div class="6u 12u$(xsmall)">Fecha de resgitro:
										<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fecha; ?>" disabled>	
									</div>
								
									<div class="4u 12u$(small)">
										<input type="radio" id="demo-priority-low" name="demo-priority" value="PSICOLOGIA" checked>
											<label for="demo-priority-low">PSICOLOGIA</label>
									</div>
									<div class="4u 12u$(small)">
										<input type="radio" id="demo-priority-normal" name="demo-priority" value="TRABAJO SOCIAL">
										<label for="demo-priority-normal">TRABAJO SOCIAL</label>
									</div>
									<div class="4u$ 12u$(small)">
										<input type="radio" id="demo-priority-high" name="demo-priority" value="JURIDICO">
										<label for="demo-priority-high">JURIDICO</label>
									</div>
									<div class="6u 12u$(small)">
										<div class="select-wrapper">
											<select id="caso" name="caso" >
												<option value="0">Caso...</option>
												<?php while ($row=$esele->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['folio_c']; } ?></option>
												<option value="0">NINGUNO</option>
											</select>
										</div>
									</div>
									<div class="6u 12u$(small)">
										<div class="select-wrapper">
											<select id="atencion" name="atencion" >
												<option value="0">Atencion...</option>
												<?php while ($row=$eten->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['brindada']; } ?></option>
												
											</select>
										</div>
									</div>
									<div class="12u$">OBSERVACION:
										<textarea name="observacion" rows="3" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
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