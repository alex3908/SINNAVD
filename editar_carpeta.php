<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idCarpeta = $_GET['id'];
	
	$dis="SELECT id, distrits from distritos";
	$edis=$mysqli->query($dis);
	$del="SELECT id, delito from delitos";
	$edel=$mysqli->query($del);
	
	$query = "SELECT casos.folio_c, carpeta_inv.nuc, carpeta_inv.distrito, carpeta_inv.fecha_inicio as fecha_ini, distritos.distrits as dis, carpeta_inv.municipio_d, municipios.municipio, carpeta_inv.id_delito, delitos.delito, carpeta_inv.imputado, carpeta_inv.relacion, carpeta_inv.mesa, carpeta_inv.estado, carpeta_inv.fecha_reg from casos, distritos, municipios, delitos, carpeta_inv where carpeta_inv.id='$idCarpeta' and carpeta_inv.id_caso=casos.id and distritos.id=carpeta_inv.distrito and municipios.id=carpeta_inv.municipio_d and delitos.id=carpeta_inv.id_delito"; 
	$resultado=$mysqli->query($query);
	$resultado3=$mysqli->query($query);
	$resultado4=$mysqli->query($query);
	$resultado5=$mysqli->query($query);
	$resultado6=$mysqli->query($query);
	
	$fec=date("j/n/Y");

	if(!empty($_POST))
	{

	
		
		$distrits = $_POST['distrits'];
		$municipio = $_POST['municipio'];
		$fecha_ini = $_POST['fecha_ini'];
		$delito = $_POST['delito'];
		$imputado = mysqli_real_escape_string($mysqli,$_POST['imputado']);		
		$relacion_i = $_POST['relacion_i'];		
		$mesa = mysqli_real_escape_string($mysqli,$_POST['mesa']);
		
			$sqlNino = "UPDATE carpeta_inv set fecha_inicio='$fecha_ini', distrito='$distrits', municipio_d='$municipio', id_delito='$delito', imputado='$imputado', relacion='$relacion_i', mesa='$mesa', fecha_act='$fec' WHERE id='$idCarpeta'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: perfil_carpeta.php?id=$idCarpeta");
			
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

						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>	




								<?php while($row=$resultado->fetch_assoc()){ ?>
								
																		
						 
						
									
										<div class="row uniform">
										<div class="6u 12u$(xsmall)" align="center">
										<h1></h1><h1></h1>
										<h1>NUC: <?php echo $row['nuc'];  ?></h1>
										<?php $est=$row['estado'];
										if ($est==20) { ?>
										 <h3>Etapa: Investigación inicial</h3>
										<?php }else if ($est==40) { ?>
										  <h3>Etapa: Investigación complementaria</h3>
										<?php }else if ($est==60) { ?>
										  <h3>Etapa: Intermedia</h3>
										<?php }else if ($est==80) { ?>
										  <h3>Etapa: Juicio</h3>
										<?php }else if ($est==100) { ?>
										  <h3>Etapa: Ejecución</h3>
										<?php } ?>
										 	</div>
										 	<div class="6u 12u$(xsmall)" align="center">
										 		<?php if ($est==20) { ?>
										<img src="images/G20.png" width="200">
										<?php }else if ($est==40) { ?>
										 <img src="images/G40.png" width="200">
										<?php }else if ($est==60) { ?>
										 <img src="images/G60.png" width="200">
										<?php }else if ($est==80) { ?>
										 <img src="images/G80.png" width="200">
										<?php }else if ($est==100) { ?>
										 <img src="images/G100.png" width="200">
										<?php } ?>
										 			 </div>
										 			 </div>
										
									
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="row uniform">
							<div class="6u 12u$(xsmall)">
								<div class="box">
									<ul class="alt">
										<li><h4>Folio de caso: </h4><?php echo $row['folio_c'];  ?> </li>
										<li><h4>Fecha de inicio: </h4> 
										<input name='fecha_ini' type='date' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['fecha_ini']; ?>" required></li>
										<li><h4>Distrito judicial: </h4>
										<div class="select-wrapper">
											<select id="distrits" name="distrits" onchange="red(this);" required>
												<option value="<?php echo $row['distrito'];  ?>"><?php echo $row['dis'];  ?></option>
												<?php } while ($row=$edis->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>">
												<?php echo $row['distrits']; ?></option>
												<?php } ?>
											</select>
										</div></li><?php while($row=$resultado4->fetch_assoc()){ ?>
										<li><h4>Municipio del delito: </h4><div class="select-wrapper">
											<select id="municipio" name="municipio"  required>
												<option value="<?php echo $row['municipio_d'];  ?> "><?php echo $row['municipio'];  ?></option>
												<?php 
												}
												$mun="SELECT id, municipio from municipios";
												$emun=$mysqli->query($mun);
											
												while ($row=$emun->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
												<?php } ?>
											</select>
										</div> </li>
									</ul>
								</div>
							</div>
							<div class="6u 12u$(xsmall)">
								<div class="box">
									<ul class="alt"><?php while($row=$resultado5->fetch_assoc()){ ?>
										<li><h4>Delito: </h4><div class="select-wrapper">
											<select id="delito" name="delito" required>
												<option value="<?php echo $row['id_delito'];  ?> "><?php echo $row['delito'];  ?> </option>
												<?php } while ($row=$edel->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['delito']; ?></option>
												<?php } ?>
											</select>
										</div></li>
										<li><h4>Imputado: </h4> <?php while($row=$resultado3->fetch_assoc()){ ?>
										<input name='imputado' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['imputado']; ?>" required></li>
										<li><h4>Relación: </h4><div class="select-wrapper">
											<select id="relacion_i" name="relacion_i" required>
												<option value="<?php echo $row['relacion'];  ?> "><?php echo $row['relacion'];  ?> </option>
												<option value="MAMÁ">MAMÁ</option>
												<option value="PAPÁ">PAPÁ</option>
												<option value="HERMANO(A)">HERMANO(A)</option>
												<option value="TIO(A)">TIO(A)</option>
												<option value="PRIMO(A)">PRIMO(A)</option>
												<option value="ABUELO(A)">ABUELO(A)</option>
												<option value="VECINO(A)">VECINO(A)</option>
												<option value="DESCONOCIDO">DESCONOCIDO</option>
												<option value="AMIGO(A)">AMIGO(A)</option>
												<option value="COMPAÑERO(A) DE ESCUELA">COMPAÑERO(A) DE ESCUELA</option>
												<option value="CONOCIDO(A)">CONOCIDO(A)</option>
												<option value="MAESTRO(A)">MAESTRO(A)</option>
												<option value="PADRINO">PADRINO</option>
												<option value="MADRINA">MADRINA</option>
												<option value="PADRASTRO">PADRASTRO</option>
												<option value="MADRASTRA">MADRASTRA</option>
											</select>
										</div></li>
										<li><h4>Mesa: </h4> 
										<input name='mesa' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['mesa']; ?>" required></li>
									</ul>
								</div>
							</div>
							<div class="12u$">
								<ul class="actions">
									<input class="button special fit" name="registar" type="submit" value="Guardar" >
									<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
								</ul>
							</div>
						</div>
						</form>
							
							<br>
							
						<br>
						<?php 
							} ?>
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
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												<li><a href="registro_personal.php">Alta</a></li>
												
												
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