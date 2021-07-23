<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	if (isset($_POST['cancelar'])) {
	 	unset($_SESSION['idP1']);
	 	unset($_SESSION['idP2']);
	 	unset($_SESSION['fecha']);

		header("Location: welcome.php");
	}
	$idDEPTO = $_SESSION['id'];
	@$idP1=$_SESSION['idP1'];
	@$idP2=$_SESSION['idP2'];
	@$fecha=$_SESSION['fecha'];

	
	if (isset($_POST['ag'])) {
	 	$idP1=mysqli_real_escape_string($mysqli,$_POST['idP1']);
		$_SESSION['idP1'] = $idP1;
		$fecha=mysqli_real_escape_string($mysqli,$_POST['fecha']);
		$_SESSION['fecha'] = $fecha;
	}
	if (isset($_POST['ag2'])) {
	 	$idP2=mysqli_real_escape_string($mysqli,$_POST['idP2']);
		$_SESSION['idP2'] = $idP2;
		$fecha=mysqli_real_escape_string($mysqli,$_POST['fecha']);
		$_SESSION['fecha'] = $fecha;
	}
	if (isset($_POST['registrar'])) {
	$fecha_reg= date ("j/n/Y");
	$fecha=mysqli_real_escape_string($mysqli,$_POST['fecha']);	
	$pfo="SELECT max(id) from expeAdop";
	$epfo=$mysqli->query($pfo);
	while ($row=$epfo->fetch_assoc()) {
		$rr=$row['max(id)'];
	}

	$rrr=$rr+1;
	$dos2=sprintf("%'03d", $rrr);
	$folion='ADOP'.$dos2;
	$fecha2=date("d/m/Y", strtotime($fecha));
		$sql="INSERT into expeAdop (folio, fecha, fecha_reg, idP1, idP2) values ('$folion', '$fecha2' ,'$fecha_reg','$idP1','$idP2')";
		$esql=$mysqli->query($sql);
	
	header("Location: welcome.php");
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
			<h2>Expediente de adopcion</h2>
				<div class="box" >
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						
							<div class="row uniform">

									<?php if (empty($idP1)) { ?>
										<div class="8u 12u$(xsmall)">
										<section id="search" class="alt">	
											Papas
										<input type="search" name="palabra1" id="query" placeholder="Buscar" />
										</section>
										</div>
									<?php } else if(empty($idP2)){ ?>
										<div class="8u 12u$(xsmall)">
										<section id="search" class="alt">
										Papas	
										<input type="search" name="palabra2" id="query" placeholder="Buscara" />
										</section>
										</div>
									<?php }else {} ?>
										<div class="4u 12u$(xsmall)">
										Fecha de inicio del expediente
										<input type="date" name="fecha" value="<?php echo $fecha; ?>" required>
										
										</div>
									
									
							</form>		
									
									<?php 
										@$buscar1 = $_POST["palabra1"];

										if (empty($buscar1)) {
											
										}else {
									?>
									<div class="12u$">
									<div class="box">
									<table>
										<tr>
											<td><b>Folio</b></td>
											<td><b>Nombre</b></td>
											<td><b>CURP</b></td>
											<td></td>
										</tr>
									<tbody>
									<?php
										@$buscar1 = $_POST["palabra1"];
	
										$query="SELECT id, nombre, apellido_p, apellido_m, curp FROM usuarios where (id like '%$buscar1%' OR nombre like '%$buscar1%' OR apellido_p like '%$buscar1%' OR apellido_m like '%$buscar1%')";
										$resultado=$mysqli->query($query);
										while($row=$resultado->fetch_assoc()){ ?>
										
										<tr>
											<td><?php echo $row['id'];?></td>
											<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
											<td><?php echo $row['curp'];?></td>
											<input type="hidden" name="idP1" value="<?php echo $row['id']; ?>">
											<td><input type="submit" name="ag" value="seleccionar"></td>
											
										</tr>
									
										<?php } ?>
										
									</tbody>
									</table>
									</div>
									</div>
									<?php  } ?>		


									<?php 
										@$buscar2 = $_POST["palabra2"];
									
										if (empty($buscar2)) {
											
										}else {
									?>
									<div class="12u$">
									<div class="box">
									<table>
										<tr>
											<td><b>Folio</b></td>
											<td><b>Nombre</b></td>
											<td><b>CURP</b></td>
											<td></td>
										</tr>
									<tbody>
									<?php
										@$buscar2 = $_POST["palabra2"];
	
										$query="SELECT id, nombre, apellido_p, apellido_m, curp FROM usuarios where (id like '%$buscar2%' OR nombre like '%$buscar2%' OR apellido_p like '%$buscar2%' OR apellido_m like '%$buscar2%')";
										$resultado=$mysqli->query($query);
										while($row=$resultado->fetch_assoc()){ ?>
										
										<tr>
											<td><?php echo $row['id'];?></td>
											<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
											<td><?php echo $row['curp'];?></td>
											<input type="hidden" name="idP2" value="<?php echo $row['id']; ?>">
											<td><input type="submit" name="ag2" value="seleccionar"></td>
											
										</tr>
									
										<?php } ?>
										
									</tbody>
									</table>
									</div>
									</div>
									<?php  } ?>			
									
								</div>

								

			 					<br>
			 					<div class="box">
			 					<table class="alt">
			 						<tr>
											<td><b>Folio</b></td>
											<td><b>Nombre</b></td>
											<td><b>CURP</b></td>
											<td></td>
										</tr>
									<tbody>										
										<tr>
											<?php $query="SELECT id, nombre, apellido_p, apellido_m, curp, id_sexo FROM usuarios where id in('$idP1','$idP2')";
										$resultado=$mysqli->query($query);
										while($row=$resultado->fetch_assoc()){ ?>
											<td><?php echo $row['id'];?></td>
											<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
											<td><?php echo $row['curp'];?></td>							
											<td><?php $ss=$row['id_sexo'];
												if ($ss==1) 
													echo "PAPÁ";
												else 
													echo "MAMÁ"
												
											  ?></td>
											
										</tr>
									
										<?php } ?>
										
									</tbody>
			 					</table></div>
							</div><!--box gene -->
		<form id="sas" name="sas" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registrar" type="submit" value="Registrar" >
			<input class="button fit" type="submit" name="cancelar" value="Cancelar" >
		</ul>
	</div>
</form>


		
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