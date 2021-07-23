<?php
	ob_start();
	session_start();
	require 'conexion.php';

	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable, id_depto FROM departamentos WHERE id= '$idDEPTO'";
	$idHisto=$_GET['id'];
	
	$result=$mysqli->query($sql);
	while ($row=$result->fetch_assoc()) {
		
		$listaUsuarios="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, 
        usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, 
        historial.fecha_salida, historial.atencion_brindada, historial.responsable, historial.asunto_aud, historial.respuesta_aud 
         FROM historial, usuarios, depto
         WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id='$idHisto'";
    $rUsuarios=$mysqli->query($listaUsuarios);
	}
	$dep="SELECT id_depto, id_personal FROM departamentos WHERE id='$idDEPTO'";
	$edep=$mysqli->query($dep);
	while ($row=$edep->fetch_assoc()) {
		$idd=$row['id_depto'];
		$idP=$row['id_personal'];
	}
   $bandera = false;
	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Asunto-Respuesta</title>
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
												
												<li><strong>Fecha de entrada: </strong><?php echo $row['fecha_ingreso'];?></li> 
                                                <li><strong>Fecha de salida: </strong><?php echo $row['fecha_salida'];?></li> 
                                                <li><strong>Atención brindada: </strong><?php echo $row['atencion_brindada'];?></li> 
                                                <div class=row>
			<div class="6u 12u$(xsmall)">Asunto: 
				<textarea name="Asunto" maxlength="500" rows="5" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled="true">
                <?php echo $row['asunto_aud'];?>
                </textarea>
			</div>
		
			<div class="6u 12u$(xsmall)">Respuesta:
			<textarea name="Respuesta" maxlength="500" rows="5" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled="true">
            <?php echo $row['respuesta_aud'];?>
            </textarea>				
			</div>
		</div>

                                                <?php  $usuario=$row['id_usuario'];} ?>												
											</ul>	
                                            <input class="button fit" type="button" name="cancelar" value="regresar" onclick="location='historial_usuario.php?id=<?php echo $usuario;?>'" >											</form>			
									
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
									<p class="copyright">&copy; DIF Hidalgo </p>
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