	<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idBenef = $_GET['id'];

	

$de="SELECT id_depto from departamentos where id='$idDEPTO'";
$ede=$mysqli->query($de);
while ($row=$ede->fetch_assoc()) {
	$id_ddd=$row['id_depto'];
}
$sqlBenef="SELECT benef_unidad.folio, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.curp, benef_unidad.apellido_m, benef_unidad.fecha_nac, benef_unidad.sexo, benef_unidad.calle, benef_unidad.telefono, departamentos.responsable, municipios.municipio, localidades.localidad, benef_unidad.fecha_reg from localidades, municipios, benef_unidad, departamentos where benef_unidad.id='$idBenef' and municipios.id=benef_unidad.municipio and localidades.id=benef_unidad.localidad and departamentos.id=benef_unidad.respo_reg";
	$esqlBenef=$mysqli->query($sqlBenef);

/*$val="SELECT nna_centros.id_centro, centros.nombre, nna_centros.motivo, nna_centros.fecha_ing from centros, nna_centros where centros.id=nna_centros.id_centro and nna_centros.id_nna='$idNNA'";
$eval=$mysqli->query($val);
$rows2=$eval->num_rows;

while ($row=$eval->fetch_assoc()) {
	$nomC=$row['nombre'];
	$idC=$row['id_centro'];
	$motivo=$row['motivo'];
	$fechaI=$row['fecha_ing'];
}*/

?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Perfil UIENNAVD</title>
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
					<?php while($row=$esqlBenef->fetch_assoc()){ ?>
						<h2><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m'];  ?> </h2>
					
						<h5>FECHA DE REGISTRO: <?php echo $row['fecha_reg']; ?></h5>
					<div class="row uniform">
						
							<div class="6u 12u$(xsmall)">
							<ul class="alt">
								<div class="box">
								<li><h4>Folio: </h4><?php echo $row['folio'];  ?> </li>
								<li><h4>Curp: </h4><?php echo $row['curp']; ?></li>	
								<li><h4>Sexo: </h4><?php echo $row['sexo'];  ?> </li>
								<li><h4>Fecha de nacimiento: </h4><?php echo $row['fecha_nac'];  ?> </li>		
								
								</div>
							</ul>
							</div>
							
							<div class="6u 12u$(xsmall)">
								<ul class="alt">
								<div class="box">
										
									<li><h4>Dirección</h4>										
										<strong>Municipio: </strong><?php echo $row['municipio'];  ?><br>										
										<strong>Localidad:</strong><?php echo $row['localidad'];  ?><br>
										<strong>Calle: </strong><?php echo $row['calle']; ?></li>
									<li><h4>Telefono: </h4><?php echo $row['telefono'];  ?> </li>
									<li><h4>Responsable de registro: </h4><?php echo $row['responsable'];  ?> </li>
											
								</div>
							</div>
					</div>
					<br>
						<?php 
							} 
  						  
						   ?>
						 
						<?php  ?>	
						<?php if (empty($rows2)) { ?>
						
						<?php }else { ?>
							<div class="box" onclick="location='listaxcentro.php?id=<?php echo $idC; ?>'">
									NNA en <?php echo $nomC; ?><br>
									<strong>Motivo:</strong> <?php echo $motivo; ?><br>
									<strong>Fecha de ingreso:</strong> <?php echo $fechaI; ?>
							</div>							
						<?php } ?>
						 
						<br>
                         <?php if ($id_ddd==16) { ?> 
                         <input type="button" name="eliminar" class="button fit" value="eliminar beneficiario" onclick="location='eliminar_benef.php<?php $_SESSION['idBenef']=$idBenef;?>'">
                         <input type="button" name="editar" class="button special fit" value="editar" onclick="location='editar_benef_unidad.php?id=<?php echo $idBenef;?>'">

							<input type="button" name="asignar_curso" class="button fit" value="REGISTRAR VISITA" onclick="location='visitas_unidad.php?id=<?php echo $idBenef; ?>'">
													
							<?php } else if ($id_ddd==7) { ?>
								
								
							<input type="button" name="editar" class="button special fit" value="editar" onclick="location='editar_benef_unidad.php?id=<?php echo $idBenef;?>'">

							<input type="button" name="asignar_curso" class="button fit" value="REGISTRAR VISITA" onclick="location='visitas_unidad.php?id=<?php echo $idBenef; ?>'">

                            <?php }else { } ?>
                        <input type="button" name="HISTORIAL" class="button fit" value="HISTORIAL" onclick="location='visitasXbeneficiario.php?id=<?php echo $idBenef; ?>'">
                     	
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
										<li><a href="logout.php">Cerrar sesión</a></li>
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
										<li><a href="logout.php" >Cerrar sesión</a></li>
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